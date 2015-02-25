<?php

class dhastkImportDokumentTask extends dhastkImporterTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('resource', sfCommandArgument::REQUIRED, 'csv file'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'backend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('dryrun', null, sfCommandOption::PARAMETER_OPTIONAL, 'no DB operations', false),
        ));

        $this->namespace = 'dhastk';
        $this->name = 'importDokument';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:importDokument|INFO] task imports the Dokument CSV to the database.
Call it with:

  [php symfony dhastk:importDokument|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $this->createConfiguration($options['application'], $options['env']);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if ($options['dryrun'] !== false) {
            $this->logBlock('DryRun: no inserts will be made', 'INFO');
        }

        try {
            $this->logBlock('Start Import', 'INFO');
            $I = new DokumentImport(array('dryrun' => $options['dryrun']));
            $start = microtime(true);
            $I->import(sfConfig::get('app_import_verzeichnungseinheit') . $arguments['resource']);
            $end = microtime(true);
            $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
            $logs = $I->getLog();
            $logs[DhastkImporter::TYPECOMMON][DhastkImporter::LOGDURATION] = ($end - $start);
            $this->logAndOutput('Dokument ' . $arguments['resource'], sfConfig::get('app_import_verzeichnungseinheit') . $arguments['resource'], $logs, $options['dryrun']);
        } catch (\Exception $exc) {
//            echo $exc->getMessage();
            $this->logBlock($exc->getMessage(), 'ERROR');
//            echo $exc->getTraceAsString();
        }
    }

    protected function logAndOutput($title, $file, $logs, $dryrun = false) {
        if (isset($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGMESSAGE])) {
            foreach ($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGMESSAGE] as $m) {
                $this->logBlock($m, 'INFO');
            }
        }
        $vz_errors = (isset($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGERROR]) ? count($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGERROR]) : 0);
        $vz_news = (isset($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGNEW]) ? count($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGNEW]) : 0);
        $vz_updates = (isset($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGUPDATE]) ? count($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGUPDATE]) : 0);
        $this->logBlock('Dokumente: ' . $vz_errors . ' Fehler, ' . $vz_updates . ' Updates, ' . $vz_news . ' Neu', 'INFO');
        //verbose
        if ($vz_errors > 0) {
            foreach ($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGERROR] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        if (isset($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGWARNING])) {
            foreach ($logs[DhastkImporter::TYPEDOKUMENT][DhastkImporter::LOGWARNING] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        //postwork: write log file
        $this->writeLogFile($logs, sfConfig::get('app_import_verzeichnungseinheit'), $file, $dryrun);
    }

}
