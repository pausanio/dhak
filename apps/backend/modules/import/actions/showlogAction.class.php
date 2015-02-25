<?php

/**
 * import show log action.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Ivo Bathke
 * @since      2013-02-16
 */
class showlogAction extends sfActions {

    /**
     * Show action
     *
     * @param type $request
     */
    public function execute($request) {
        $this->file = $request->getParameter('file');
        $this->type = $request->getParameter('type');
        $this->folder = $request->getParameter('folder');
        $Collection = new DhastkLogFileCollection(sfConfig::get('app_import_' . $this->folder));
        $logfile = FALSE;
        switch ($this->type) {
            case DhastkLogFile::VALIDATED:
                if (file_exists($Collection->getPathValidationLog() . '/' . $this->file . DhastkLogFileCollection::LOGEXT)) {
                    $logfile = $Collection->getPathValidationLog() . '/' . $this->file . DhastkLogFileCollection::LOGEXT;
                } elseif (file_exists($Collection->getPathValidationFail(). '/' . $this->file . DhastkLogFileCollection::LOGEXT)) {
                    $logfile = $Collection->getPathValidationFail() . '/'. $this->file . DhastkLogFileCollection::LOGEXT;
                }
                break;
            case DhastkLogFile::IMPORTED:
                if (file_exists($Collection->getPathImportLog() . '/' . $this->file . DhastkLogFileCollection::LOGEXT)) {
                    $logfile = $Collection->getPathImportLog() . '/' . $this->file . DhastkLogFileCollection::LOGEXT;
                } elseif (file_exists($Collection->getPathImportFail(). '/' . $this->file . DhastkLogFileCollection::LOGEXT)) {
                    $logfile = $Collection->getPathImportFail() . '/'. $this->file . DhastkLogFileCollection::LOGEXT;
                }
                break;
            default:
                $this->redirect404();
                break;
        }
        if (!$this->file) {
            $this->redirect404();
        }

        if ($logfile !== false) {
            $this->created_at = date('d.m.Y H:i:s', filectime($logfile));
            $this->content = unserialize(file_get_contents($logfile));
        }
    }

}