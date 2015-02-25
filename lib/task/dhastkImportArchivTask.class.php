<?php

class dhastkImportArchivTask extends dhastkImporterTask {

    protected function configure() {
        // add your own arguments here
        // TODO if no resource is set use queue
        $this->addArguments(array(
            new sfCommandArgument('resource', sfCommandArgument::REQUIRED, 'xml file'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'backend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('dryrun', null, sfCommandOption::PARAMETER_OPTIONAL, 'no DB operations', false),
        ));

        $this->namespace = 'dhastk';
        $this->name = 'importArchiv';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:importArchiv|INFO] task imports the Archiv XML to the database.
Call it with:

  [php symfony dhastk:importArchiv|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $this->createConfiguration($options['application'], $options['env']);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if($options['dryrun'] !== false){
           $this->logBlock('DryRun: no inserts will be made', 'INFO'); 
        }
        $AI = new ArchivImport(array('dryrun' => $options['dryrun']));
        $start = microtime(true);
        try {
            $this->logBlock('Start Import', 'INFO');
            $AI->import(sfConfig::get('app_import_archiv') . $arguments['resource']);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }
        $end = microtime(true);
        $logs = $AI->getLog();
        $logs[DhastkImporter::TYPECOMMON][DhastkImporter::LOGDURATION] = ($end - $start);
        $tek_errors = (isset($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGERROR])?count($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGERROR]):0);
        $tek_news = (isset($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGNEW])?count($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGNEW]):0);
        $tek_updates = (isset($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGUPDATE])?count($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGUPDATE]):0);
        $this->logBlock('Tektoniken: ' . $tek_errors . ' Fehler, ' . $tek_updates . ' Updates, ' . $tek_news . ' Neu', 'INFO');
        //verbose
        if ($tek_errors > 0) {
            foreach ($logs[DhastkImporter::TYPETEKTONIK][DhastkImporter::LOGERROR] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        $best_errors = (isset($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGERROR])?count($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGERROR]):0);
        $best_news = (isset($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGNEW])?count($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGNEW]):0);
        $best_updates = (isset($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGUPDATE])?count($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGUPDATE]):0);
        $this->logBlock('Bestände: ' . $best_errors . ' Fehler, ' . $best_updates . ' Updates, ' . $best_news . ' Neu', 'INFO');
        $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
        //verbose
        if ($best_errors > 0) {
            foreach ($logs[DhastkImporter::TYPEBESTAENDE][DhastkImporter::LOGERROR] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        //postwork
        // write log file
        $this->writeLogFile($logs, sfConfig::get('app_import_archiv'), $arguments['resource'], $options['dryrun']);
    }

}
