<?php

class dhastkImportBestandTask extends dhastkImporterTask
{

    protected function configure()
    {
        // // add your own arguments here
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
        $this->name = 'importBestand';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:importBestand|INFO] task imports the Bestand XML to the database.
Call it with:

  [php symfony dhastk:importBestand|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {

        $this->createConfiguration($options['application'], $options['env']);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if ($options['dryrun'] !== false) {
            $this->logBlock('DryRun: no inserts will be made', 'INFO');
        }
        $I = new BestandsImport(array('dryrun' => $options['dryrun']));

        try {
            $this->logBlock('Start Import', 'INFO');

            if (is_dir(sfConfig::get('app_import_bestand') . $arguments['resource'])) {
                $this->logBlock('Resource is Dir', 'INFO');
                if ($this->askConfirmation('Import whole Dir? (y/n)')) {
                    $finder = new sfFinder;
                    foreach ($finder->name('*.xml')->in(sfConfig::get('app_import_bestand') . $arguments['resource']) AS $file) {
                        if (is_file($file)) {
                            $start = microtime(true);
                            $I->import($file);
                            $end = microtime(true);
                            $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
                            $logs = $I->getLog();
                            $this->logAndOutput('Bestand ' . $I->getSignatur(), $file, $logs);
                            $I->resetLogs();
                        }
                    }
                }
            } else {
                $start = microtime(true);
                $I->import(sfConfig::get('app_import_bestand') . $arguments['resource']);
                $end = microtime(true);
                $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
                $logs = $I->getLog();
                $logs[DhastkImporter::TYPECOMMON][DhastkImporter::LOGDURATION] = ($end - $start);
                $this->logAndOutput('Bestand ' . $I->getSignatur(), sfConfig::get('app_import_bestand') . $arguments['resource'], $logs, $options['dryrun']);
            }
            return true;
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
            return false;
        }
    }

    protected function logAndOutput($title, $file, $logs, $dryrun = false)
    {
        $best_updates = (isset($logs[DhastkImporter::TYPEFINDMITTEL][DhastkImporter::LOGUPDATE]) ? count($logs[DhastkImporter::TYPEFINDMITTEL][DhastkImporter::LOGUPDATE]) : 0);
        $this->logBlock('Findemittel: ' . $best_updates . ' Updates', 'INFO');

        $vz_errors = (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGERROR]) ? count($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGERROR]) : 0);
        $vz_news = (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGNEW]) ? count($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGNEW]) : 0);
        $vz_updates = (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGUPDATE]) ? count($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGUPDATE]) : 0);
        $this->logBlock('VerzeichnisEinheit: ' . $vz_errors . ' Fehler, ' . $vz_updates . ' Updates, ' . $vz_news . ' Neu', 'INFO');
        //verbose
        if ($vz_errors > 0) {
            foreach ($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGERROR] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        if (isset($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGWARNING])) {
            foreach ($logs[DhastkImporter::TYPEVERZEICHNISEINHEIT][DhastkImporter::LOGWARNING] as $value) {
                $this->logBlock($value, 'ERROR');
            }
        }
        //postwork
        //write log file
        $this->writeLogFile($logs, sfConfig::get('app_import_bestand'), $file, $dryrun);
    }

}
