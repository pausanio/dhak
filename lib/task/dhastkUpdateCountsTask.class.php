<?php

/**
 * Archiv count task
 *
 * @usage $ ./symfony dhastk:update-counts
 *
 */
class updatesCountsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'backend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('dryrun', null, sfCommandOption::PARAMETER_OPTIONAL, 'no DB operations', false)
        ));

        $this->namespace = 'dhastk';
        $this->name = 'update-counts';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:update-counts|INFO] updates all count_ve and count_docs in Verzeichnungsheit, Bestand, etc.
Call it with:

  [php symfony dhastk:update-counts|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        #ini_set('memory_limit', '256M');

        if ($options['dryrun'] !== false) {
            $this->logBlock('DryRun: no inserts will be made', 'INFO');
        }

        $this->createConfiguration($options['application'], $options['env']);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $C = new DokumentCounter();
        $start = microtime(true);
        try {
            $C->count();
            $C->addCounts();
        } catch (Exception $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }
        $end = microtime(true);
        $logs = $C->getLog();

        $errors = (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['fehler'])?count($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['fehler']):0);
        $messages = (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['message'])?count($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['message']):0);
        $additions = (isset($logs[DhastkImporter::TYPETEKTONIK]['message'])?count($logs[DhastkImporter::TYPETEKTONIK]['message']):0);
        $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
        $this->logBlock($errors . ' Fehler, ' . $messages . ' Counts '. $additions . ' Additions', 'INFO');
        //verbose
        if ($errors > 0) {
            foreach ($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['fehler'] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        if ($messages > 0) {
            foreach ($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT]['message'] as $value) {
                $this->logBlock($value, 'INFO');
            }
        }
        if ($additions > 0) {
            foreach ($logs[DhastkImporter::TYPETEKTONIK]['message'] as $value) {
                $this->logBlock($value, 'INFO');
            }
        }
        //TODO write logfile
        $this->logBlock('Ready.', 'INFO');
    }

}
