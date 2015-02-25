<?php

/**
 * import verzeichnungseinheit action.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2012-06-04
 */
class verzeichnungseinheitAction extends sfActions {

    /**
     * Import logs
     *
     * @var type array
     */
    protected $logs = array();

    public function execute($request) {
        if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('notice', 'CSV-Datei fehlt!');
            $this->redirect('import/index');
        }
        $v = sfZendQueue::getInstance('validation.verzeichnungseinheit');
        $v->send(sfConfig::get('app_import_verzeichnungseinheit') . $filename);
        // redirect to index
        $this->getUser()->setFlash('notice', $filename . ' wird importiert');
        $this->redirect('import/index');
    }

    /**
     * Import Dokument CSV file
     *
     * @deprecated use job queue instead
     * @param type $request
     */
    public function executeOld($request) {

        ini_set('memory_limit', '4048M');

        if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('notice', 'CSV-Datei fehlt!');
            $this->redirect('import/index');
        }

        $filename = $request->getParameter('file', false);
        $rows = file(sfConfig::get('app_import_verzeichnungseinheit') . $filename);

        // Bestand
        $bestand_sig = explode(';', $rows[0]);
        $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($bestand_sig[1]);
        $laufzeit = $bestand->getLaufzeit();
        $vorlagentyp_id = 2; // digitales Bild
        // Folders
        $this->sourcepath = sfConfig::get('app_import_dokument') . $bestand->getSlug();
        $this->destpath = sfConfig::get('app_documents_org') . $bestand->getSlug();

        // mysqli db connection (faster!!!)
        $mysqli = new mysqli("p:localhost", sfConfig::get('app_db_user'), sfConfig::get('app_db_password'), sfConfig::get('app_db_database'));
        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }
        $mysqli->set_charset("utf8");

        $user_id = $this->getUser()->getGuardUser()->getId();
        $einsteller = 'Historisches Archiv der Stadt Köln';

        // sortable plugin: position (unique)
        $result = $mysqli->query("SELECT max(position) AS position FROM dokument");
        $position = $result->fetch_object()->position + 1;

        foreach ($rows as $row) {
            $document = explode(';', $row);

            $sql = "SELECT count(*) FROM dokument WHERE filename = '{$document[0]}' AND bestand_sig = '{$document[1]}' AND signatur = '{$document[2]}' AND pos = '{$document[3]}'";
            $result = $mysqli->query($sql);
            $dokument = $result->fetch_array();

            if ($dokument[0] == 0) {
                // get Verzeichnungseinheit (ve)
                $sql = "SELECT id, archiv_id, signatur, bestand_sig FROM verzeichnungseinheit WHERE signatur = '{$document[2]}' AND bestand_sig = '{$document[1]}'";
                $result = $mysqli->query($sql);
                $verzeichnungseinheit = $result->fetch_object();

                // insert new Dokument
                $sql = "INSERT INTO dokument (archiv_id, bestand_sig, signatur, verzeichnungseinheit_id, filename, pos, datierung, vorlagentyp_id, einsteller, created_by, updated_by, created_at, updated_at, status, import, position)
        VALUES ($verzeichnungseinheit->archiv_id, '{$document[1]}', '{$document[2]}', $verzeichnungseinheit->id, '{$document[0]}', $document[3], '{$laufzeit}', $vorlagentyp_id, '{$einsteller}', $user_id, $user_id, now(), now(), 1, 1, $position)";
                if (!$mysqli->query($sql)) {
                    die('Konnte Datensatz nicht einfügen: ' . $mysqli->error);
                }
                #$this->logs['Dokument']['neu'][] = $row;
                // @todo move source file to dest folder...
                $position++;
            } else {
                $this->logs['Dokument']['bereits vorhanden'][] = $row;
            }
        }

        // write log file
        $extension = explode('.', $filename);
        $logfile = sfConfig::get('app_import_verzeichnungseinheit') . $extension[0] . '.import.log';
        if (file_exists($logfile) && is_file($logfile) && is_writable($logfile)) {
            unlink($logfile);
        }
        $file = fopen($logfile, 'w');
        $content = $this->getPartial('import/logfile', array('title' => 'CSV', 'logs' => $this->logs, 'filename' => $logfile));
        fwrite($file, $content);
        fclose($file);

        echo "<pre>";
        print_r($this->logs);
        die();

        // redirect to index
        $this->getUser()->setFlash('notice', $logfile);
        $this->redirect('import/index');
    }

}