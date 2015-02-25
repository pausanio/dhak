<?php

abstract class dhastkImporterTask extends sfBaseTask {

    protected function writeLogFile(array $logs, $path, $file, $validation) {
        if ($validation === false) {
            $path .= sfConfig::get('app_import_logsimport');
        } else {
            $path .= sfConfig::get('app_import_logsvalidation');
        }
        foreach ($logs as $k => $value) {
            if (isset($value[DhastkImporter::LOGERROR])) {
                $path .= '/' . sfConfig::get('app_import_logsfaildir');
            }
        }
        $file = pathinfo($file, PATHINFO_BASENAME);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return file_put_contents($path . '/' . $file . '.log', serialize($logs));
    }
    
}