<?php

/**
 * import bestand action.
 *
 * Voraussetzungen:
 * - Bestände liegen unterhalb einer Tektonik und besitzen eine eindeutige Signatur
 * - Tektoniken besitzen eine eindeutige Nr (Signatur)
 * - Elemente ohne Signatur erhalten die jeweilige Sigantur der übergeordneten Ebene
 *
 * Updates:
 * - Tektonik: Namens- und Beschreibungs-Änderungen werden geupdated
 * - Bestand: Name, Laufzeit, Bestand_Inhalt, Umfang, Bem, Bestandsgeschichte, Sperrvermerk werden geupdated
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2012-05-31
 */
class bestandAction extends sfActions {

    /**
     * Import logs
     *
     * @var type array
     */
    protected $logs = array();

    public function execute($request) {
       if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('notice', 'XML-Datei fehlt!');
            $this->redirect('import/index');
        }
        $v = sfZendQueue::getInstance('validation.bestand');
        $v->send(sfConfig::get('app_import_bestand') . $filename);
        // redirect to index
        $this->getUser()->setFlash('notice', $filename .' wird importiert');
        $this->redirect('import/index');
    }
    /**
     * Import action
     *
     * @deprecated use job queue instead
     * @param type $request
     */
    public function executeOld($request) {
        if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('notice', 'XML-Datei fehlt!');
            $this->redirect('import/index');
        }

        $BI = new ArchivImport(array('dryrun' => true));
        try {
            $BI->import(sfConfig::get('app_import_bestand') . $filename);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }
        $logs = $BI->getLog();

        // write log file
        $extension = explode('.', $filename);
        $logfile = sfConfig::get('app_import_bestand') . $extension[0] . '.log';

        if (file_exists($logfile) && is_file($logfile) && is_writable($logfile)) {
            unlink($logfile);
        }

        $file = fopen($logfile, 'w');
        $content = $this->getPartial('import/logfile', array('title' => 'Bestand ' . $BI->getSignatur(), 'logs' => $logs, 'filename' => $logfile));
        fwrite($file, $content);
        fclose($file);

        // rename import file
        rename(sfConfig::get('app_import_bestand') . $filename, sfConfig::get('app_import_bestand') . '_' . $filename);

        // redirect to index
        $this->getUser()->setFlash('notice', $logfile);
        $this->redirect('import/index');
    }

    /**
     * Save Klassifikation
     *
     * @param xml_object $tektonik
     * @param type $parent_name
     * @return int
     */
    protected function saveKlassifikation($klassifikation = false, $parent_id = false) {
        if (false === $klassifikation || false === $parent_id) {
            $this->getUser()->setFlash('notice', 'saveKlassifikation: Fehlender Parameter!');
            $this->redirect('import/index');
        }
        if (empty($klassifikation->Klass_Titel)) {
            $this->getUser()->setFlash('notice', 'saveKlassifikation: Fehlender Klassifikationsname');
            $this->redirect('import/index');
        }

        #$this->logs['klassifikation']['gefunden'][] = $klassifikation->Klass_Titel;
        // unique = klass_titel + type = 3 + bestand signature
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('c')
                ->where('c.name = ?', $klassifikation->Klass_Titel)
                ->andWhere('c.signatur = ?', $this->bestand_signatur)
                ->andWhere('c.type = ?', 3)
                ->fetchOne();

        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->name = $klassifikation->Klass_Titel;
            $node->type = 3;
            $node->signatur = $this->bestand_signatur;
            $node->getNode()->insertAsLastChildOf(Doctrine_Core::getTable('Archiv')->findOneById($parent_id));
        }

        // Get Verzeichnungseinheiten
        if ($klassifikation->Verzeichnungseinheiten) {
            foreach ($klassifikation->Verzeichnungseinheiten->children() as $verzeichnungseinheit) {
                switch ($verzeichnungseinheit->getName()) {
                    case 'Sachakte':
                        #$this->logs['verzeichnungseinheiten']['sachakte']['gefunden'][] = $verzeichnungseinheit->Titel . '(' . $verzeichnungseinheit->Signatur . ')';
                        $this->saveVerzeichnungseinheit($verzeichnungseinheit, 'Sachakte', $node->getId());
                        break;
                    case 'Urkunde':
                        #$this->logs['verzeichnungseinheiten']['urkunde']['gefunden'][] = $verzeichnungseinheit->Titel . '(' . $verzeichnungseinheit->Signatur . ')';
                        $this->saveVerzeichnungseinheit($verzeichnungseinheit, 'Urkunde', $node->getId());
                        break;
                    case 'Bandserie':
                        #$this->logs['verzeichnungseinheiten']['bandserie']['gefunden'][] = $verzeichnungseinheit->Titel;
                        $this->saveBandserie($verzeichnungseinheit, $node->getId());
                        break;
                    default:
                        $this->logs['verzeichnungseinheiten']['errors'][] = 'Unbekannte Struktureinheit: ' . $verzeichnungseinheit->getName();
                        break;
                }
            }
        }

        return $node->getId();
    }

    /**
     * Get sub Klassifikationen
     *
     * @param type $xmlObj
     * @param type $parent
     */
    protected function getChildrenRecursive($xmlObj = false, $parent_id = false) {
        if (false === $xmlObj || false === $parent_id) {
            $this->getUser()->setFlash('notice', 'getChildrenRecursive: Kein Objekt oder Parent');
            $this->redirect('import/index');
        }
        foreach ($xmlObj->children() as $klassifikation) {
            if ($klassifikation->getName() == 'Klassifikation') {
                $parent = $this->saveKlassifikation($klassifikation, $parent_id);
                $this->getChildrenRecursive($klassifikation, $parent);
            }
        }
    }

    /**
     * Save Verzeichnungseinheit
     *
     * Verzeichnungseinheiten haben nicht zwingend einen Titel (Urkunden in Best. 214)
     * bei fehlendem Titel wird die Sigantur verwendet
     *
     * @param type $veinheit
     * @param type $type
     * @param type $archiv_id
     */
    protected function saveVerzeichnungseinheit($veinheit = false, $type, $archiv_id) {

        #$this->logs['verzeichnungseinheit']['gefunden'][] = $veinheit->Titel . ' (' . $veinheit->Signatur . ')';

        if (empty($veinheit->Signatur)) {
            $this->logs['verzeichnungseinheit']['errors'][] = 'Fehlende Signatur: ' . $veinheit->Titel;
            return false;
        }
        if (empty($veinheit->Titel)) {
            $this->logs['verzeichnungseinheit']['warnung'][] = 'Kein Titel: ' . $veinheit->Signatur;
            $veinheit->Titel = $veinheit->Signatur;
        }

        // unique = archiv_id + signatur
        $item = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery('c')
                ->where('c.archiv_id = ?', $archiv_id)
                ->andWhere('c.signatur = ?', $veinheit->Signatur)
                ->fetchOne();

        if (!$item) {
            $item = new Verzeichnungseinheit();
            $item->archiv_id = trim($archiv_id);
            $item->signatur = trim($veinheit->Signatur);
            $item->laufzeit = trim($veinheit->Laufzeit->LZ_Text);
            $item->beschreibung = trim($veinheit->Beschreibung);
            $item->titel = trim($veinheit->Titel);
            $item->sperrvermerk = trim($veinheit->Sperrvermerk);
            $item->bestellsig = trim($veinheit->Bestellsig);
            $item->archivgutart = trim($type);
            $item->altsig = trim($veinheit->Altsig);
            $item->umfang = trim($veinheit->Umfang);
            $item->bem = trim($veinheit->Bem);
            $item->bestand_sig = trim($this->bestand_signatur);
            $item->save();
            $this->logs['verzeichnungseinheit']['new'][] = $item->titel . ' (' . $item->signatur . ')';
        }
    }

    /**
     * Save Bandserie
     *
     * @param type $veinheit
     * @param type $archiv_id
     */
    protected function saveBandserie($veinheit = false, $archiv_id) {
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('a')
                ->where('a.name = ?', $veinheit->Serientitel)
                ->andWhere('a.signatur = ?', $this->bestand_signatur)
                ->andWhere('a.type = ?', 4)
                ->fetchOne();

        if (!$node) {
            $node = new Archiv();
            $node->name = trim($veinheit->Serientitel);
            $node->type = 4;
            $node->signatur = trim($this->bestand_signatur);
            $node->getNode()->insertAsLastChildOf(Doctrine_Core::getTable('Archiv')->findOneById($archiv_id));
            $this->logs['verzeichnungseinheiten']['bandserie']['neu'][] = $node->getName() . ' (' . $node->getId() . ')';
        }
        // save children
        foreach ($veinheit->children() as $band) {
            switch ($band->getName()) {
                case 'Sachakte':
                    $this->saveVerzeichnungseinheit($band, 'Sachakte', $node->getId());
                    break;
                case 'Urkunde':
                    $this->saveVerzeichnungseinheit($band, 'Urkunde', $node->getId());
                    break;
                default:
                    $this->logs['verzeichnungseinheiten']['errors'][] = 'Unbekannte Struktureinheit: ' . $band->getName() . ' in ' . $node->name;
                    break;
            }
        }
    }

}