<?php

/**
 * validate verzeichnungseinheit action.
 *
 * - Doppelte Bildverknüpfungen kommen vor, da:
 *   „Diese jeweiligen Bilder zeigen nämlich die Rückseite der einen
 *   Verzeichnungseinheit sowie die Vorderseite der anderen Verzeichnungseinheit.
 *   So kommt es, dass ein Bild zwei Verzeichnungseinheiten zugeordnet ist.“
 *   [Benjamin Bussmann]
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2012-05-31
 */
class verzeichnungseinheitvalidationAction extends sfActions {

    /**
     * Import logs
     *
     * @var type array
     */
    protected $logs = array();

    /**
     * Import Dokument CSV file
     *
     * @param type $request
     */
    public function execute($request) {
        if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('notice', 'CSV-Datei fehlt!');
            $this->redirect('import/index');
        }

        $I = new DokumentImport();

        try {
            $I = new DokumentImport();
            $I->validate(sfConfig::get('app_import_verzeichnungseinheit') . $filename, true);
            $logs = $I->getLog();
// write log file
            $logfile = sfConfig::get('app_import_verzeichnungseinheit') . $filename . '.log';
            if (file_exists($logfile) && is_file($logfile) && is_writable($logfile)) {
                unlink($logfile);
            }
            $file = fopen($logfile, 'w');
            $content = $this->getPartial('import/logfile', array('title' => 'CSV', 'logs' => $logs, 'filename' => $logfile));
            fwrite($file, $content);
            fclose($file);
// redirect to index
            $this->getUser()->setFlash('notice', $logfile);
            $this->redirect('import/index');
        } catch (\Exception $exc) {
//            echo $exc->getMessage();
//            echo $exc->getTraceAsString();
            $this->getUser()->setFlash('error', $exc->getMessage());
            $this->redirect('import/index');
        }
    }

}