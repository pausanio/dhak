<?php

class dhastkFindDuplicateSignaturesBestandTask extends dhastkImporterTask
{

    protected $failed = 0;

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'backend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        ));

        $this->namespace = 'dhastk';
        $this->name = 'findDuplicateSignaturesBestand';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:findDuplicateSignaturesBestand|INFO] task finds duplicate Signatures.
Call it with:

  [php symfony dhastk:findDuplicateSignaturesBestand|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {

        $this->createConfiguration($options['application'], $options['env']);

        $this->logBlock('Starte Überprüfung', 'INFO');
        $start = microtime(true);

        $finder = new sfFinder;
        $i = 0;
        foreach ($finder->name('*.xml')->in(sfConfig::get('app_import_bestand')) AS $file) {
            if (is_file($file)) {
                $this->findDuplicateSignatures($file);
                $i++;
            }
        }

        $end = microtime(true);
        $this->logBlock('Von insgesamt ' . $i . ' geprüften Dateien sind ' . $this->failed . ' fehlerhaft.', 'INFO');
        $this->logBlock('Zeit: ' . ($end - $start) . ' s', 'INFO');
    }

    protected function findDuplicateSignatures($file)
    {
        $dom = new DomDocument();
        $dom->load($file);
        $tags = $dom->getElementsByTagName('Signatur');

        $signatures = array();
        foreach ($tags as $tag) {
            $signatures[] = (string) trim($tag->nodeValue);
        }

        $duplicates = $this->array_not_unique($signatures);
        if (count($duplicates) > 0) {
            $this->failed++;
            $this->logBlock(basename($file) . ' : ' . implode(', ', $duplicates), 'ERROR');
            #if (!copy($file, sfConfig::get('app_import_bestand') . '../signature_duplicates/' . basename($file))) {
            #    $this->logBlock(basename($file) . ' konnte nicht kopiert werden.', 'WARNING');
            #}
        } else {
            #if (!copy($file, sfConfig::get('app_import_bestand') . '../signature_unique/' . basename($file))) {
            #    $this->logBlock(basename($file) . ' konnte nicht kopiert werden.', 'INFO');
            #}
        }

        return true;
    }

    protected function array_not_unique($input)
    {

        $duplicates = array();
        $processed = array();
        $return = array();

        foreach ($input as $i) {
            if (in_array($i, $processed)) {
                if (!in_array($i, $duplicates)) {
                    $duplicates[] = (string) trim($i);
                }
            } else {
                $processed[] = (string) trim($i);
            }
        }

        foreach ($duplicates as $duplicate) {
            if ($duplicate != '_' && $duplicate != 'Verweis' && $duplicate != '') {
                $return[] = $duplicate;
            }
        }

        return $return;
    }

}
