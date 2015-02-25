<?php
/**
 * scanner class for logfile collection of dhastk import
 * 
 * @author Ivo Bathke
 */
class DhastkLogFileCollection {

    protected $dir;
    protected $ext;
    protected $pathImportLog;
    protected $pathImportFail;
    protected $pathImportQueue;
    protected $pathValidationLog;
    protected $pathValidationFail;
    protected $pathValidationQueue;

    const LOGEXT = '.log';

    function __construct($dir, $ext = '*') {

        $this->dir = $dir;
        $this->ext = $ext;
        $this->pathImportLog = $this->dir . '/' . sfConfig::get('app_import_logsimport');
        $this->pathImportFail = $this->pathImportLog . '/'.sfConfig::get('app_import_logsfaildir');
        $this->pathImportQueue = $this->dir . 'import';
        $this->pathValidationLog = $this->dir . sfConfig::get('app_import_logsvalidation');
        $this->pathValidationFail = $this->pathValidationLog . '/'.sfConfig::get('app_import_logsfaildir');
        $this->pathValidationQueue = $this->dir . 'validation';
    }

    public function scan() {
        $files = glob($this->dir . '*.' . $this->ext);
        $ret = array();
        if ($files) {
            foreach ($files as $file) {
                $LF = new DhastkLogFile();
                $LF->file = $file;
                $LF->updated_at = date('d.m.Y H:i:s', filemtime($file));
                $LF->created_at = date('d.m.Y H:i:s', filectime($file));
                $LF->size = round((filesize($file) / 1000), 2) . ' KB';
                
                $fileName = pathinfo($file, PATHINFO_BASENAME);
                if (file_exists($this->pathImportLog . '/' . $fileName . self::LOGEXT)) {
                    $LF->status[] = DhastkLogFile::IMPORTED;
                    $LF->import = true;
                } elseif (file_exists($this->pathImportFail. '/' . $fileName . self::LOGEXT)) {
                    $LF->status[] = DhastkLogFile::IMPORTEDFAIL;
                    $LF->import = true;
                }
                if (file_exists($this->pathImportQueue . '/' . $fileName)) {
                    $LF->status[] = DhastkLogFile::QUEUED4IMPORT;
                }
                if (file_exists($this->pathValidationLog . '/' . $fileName . self::LOGEXT)) {
                    $LF->status[] = DhastkLogFile::VALIDATED;
                    $LF->validation = true;
                } elseif (file_exists($this->pathValidationFail. '/' . $fileName . self::LOGEXT)) {
                    $LF->status[] = DhastkLogFile::VALIDATEDFAIL;
                    $LF->validation = true;
                }
                if (file_exists($this->pathValidationQueue . '/' . $fileName)) {
                    $LF->status[] = DhastkLogFile::QUEUED4VALIDATION;
                }
                
                if (empty($LF->status)) {
                    $LF->status = array(DhastkLogFile::IDLE);
                }
                $ret[] = $LF;
            }
        }
        return $ret;
    }

    public function getDir() {
        return $this->dir;
    }

    public function setDir($dir) {
        $this->dir = $dir;
    }

    public function getExt() {
        return $this->ext;
    }

    public function setExt($ext) {
        $this->ext = $ext;
    }

    public function getPathImportLog() {
        return $this->pathImportLog;
    }

    public function setPathImportLog($pathImportLog) {
        $this->pathImport = $pathImportLog;
    }

    public function getPathImportFail() {
        return $this->pathImportFail;
    }

    public function setPathImportFail($pathImportFail) {
        $this->pathImportFail = $pathImportFail;
    }

    public function getPathImportQueue() {
        return $this->pathImportQueue;
    }

    public function setPathImportQueue($pathImportQueue) {
        $this->pathImportQueue = $pathImportQueue;
    }

    public function getPathValidationLog() {
        return $this->pathValidationLog;
    }

    public function setPathValidationLog($pathValidationLog) {
        $this->pathValidationLog = $pathValidationLog;
    }

    public function getPathValidationFail() {
        return $this->pathValidationFail;
    }

    public function setPathValidationFail($pathValidationFail) {
        $this->pathValidationFail = $pathValidationFail;
    }

    public function getPathValidationQueue() {
        return $this->pathValidationQueue;
    }

    public function setPathValidationQueue($pathValidationQueue) {
        $this->pathValidationQueue = $pathValidationQueue;
    }

}