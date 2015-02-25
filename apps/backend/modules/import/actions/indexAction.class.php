<?php

/**
 * import index action.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2012-05-31
 */
class indexAction extends sfActions
{

    /**
     * Index action
     *
     * @param type $request
     */
    public function execute($request)
    {
        $this->files_archiv = $this->readDirectory('archiv', 'xml');
        $this->logs_archiv = $this->readDirectory('archiv', 'log');
        $this->files_bestaende = $this->loadLogfiles('bestand', 'xml');
        #$this->logs_bestaende = $this->readDirectory('bestand', 'log');
        $this->files_verzeichnungseinheiten = $this->loadLogfiles('verzeichnungseinheit', 'csv'); 
        #$this->files_verzeichnungseinheiten = $this->readDirectory('verzeichnungseinheit', 'csv');
        #$this->logs_verzeichnungseinheiten = $this->readDirectory('verzeichnungseinheit', 'log');
        #$this->txt_verzeichnungseinheiten = $this->readDirectory('verzeichnungseinheit', 'txt');
    }
    
    protected function loadLogfiles($folder, $type) {
        $dir = sfConfig::get('app_import_' . $folder);
        $Collection = new DhastkLogFileCollection($dir, $type);
        return $Collection->scan();
    }

    /**
     * Read directory
     */
    protected function readDirectory($folder = false, $type = false)
    {
        if ($folder == false || $type == false) {
            return;
        }
        $dir = sfConfig::get('app_import_' . $folder);

        $dir_res = openDir($dir);
        $files = array();
        while ($file = readDir($dir_res)) {
            $_ext = explode('.', $file);
            $ext = end($_ext);
            if ($file != '.' && $file != '..' && $type == $ext) {
                $fileValues = array(
                    'file' => $file,
                    'status' => array(),
                    'validation' => false,
                    'import' => false,
                    'updated_at' => date('d.m.Y H:i:s', filemtime($dir . '/' . $file)),
                    'created_at' => date('d.m.Y H:i:s', filectime($dir . '/' . $file)),
                    'size' => round((filesize($dir . '/' . $file) / 1000), 2) . ' KB'
                );
                /*
                 * scan for file in
                 * logs/import
                 * logs/validation
                 * validation
                 * import
                 */

                if (file_exists($dir . '/' . sfConfig::get('app_import_logsimport') . '/' . $file . '.log')) {
                    $fileValues['status'][] = 'imported';
                    $fileValues['import'] = true;
                } elseif (file_exists($dir . '/' . sfConfig::get('app_import_logsimport') . '/fail/' . $file . '.log')) {
                    $fileValues['status'][] = 'imported';
                    $fileValues['import'] = true;
                }
                if (file_exists($dir . '/' . sfConfig::get('app_import_logsvalidation') . '/' . $file . '.log')) {
                    $fileValues['status'][] = 'validated';
                    $fileValues['validation'] = true;
                } elseif (file_exists($dir . '/' . sfConfig::get('app_import_logsvalidation') . '/fail/' . $file . '.log')) {
                    $fileValues['status'][] = 'validated_fail';
                    $fileValues['validation'] = true;
                }
                if (file_exists($dir . '/validation/' . $file)) {
                    $fileValues['status'][] = 'validated';
                }
                if (file_exists($dir . '/import/' . $file)) {
                    $fileValues['status'][] = 'queued4import';
                }
                if (empty($fileValues['status'])) {
                    $fileValues['status'] = array('idle');
                }
                $files[] = $fileValues;
            }
        }
        closeDir($dir_res);
        return $files;
    }

}

