<?php

/**
 * import archiv action.
 *
 * Voraussetzungen:
 * - SAFT-XML-Struktur: Tektonik > Untertektonik > (Untertektonik > ..) > Bestand > (Unterbestand > ..)
 *
 * Ergebnis:
 * - XML -> Nested Set
 * - Strukturebenen-Typen: 1 = Tektonik, 2 = Bestand, 3 = Klassifikation, 4 = Bandserie
 * - Bestand-Metadaten werden in der Tabelle 'bestand' gesichert
 *
 * Update:
 * - Nur über SAFT-XML des Historischen Archivs der Stadt Köln!
 * - Wenn Signatur vorhanden: Name + Beschreibung in Tabelle 'archiv' wird aktualisiert,
 *   Bestand-Metadaten werden über Bestand-SAFT-XML geupdated
 * - Elemente ohne Signatur erhalten die jeweilige Sigantur der übergeordneten Ebene
 * - Die Signatur wird urlized und dient als Folder für die Digitalisate der jeweiligen Archiv-Ebene
 *
 * Hinweise:
 * - XML-Strings müssen 'getrimmt' werden, da in der SAFT-Datei Zeilenumbrüche vorhanden sein können
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2012-05-31
 *
 * @todo Unterbestände besitzen teilweise gleiche Signaturen wie ihre Eltern. Richtig?
 *       Vorerst so gelöst, dass Zuordnung über Signatur, Type und Name
 *       Achtung: bestand_sig in 'bestand' unique = false gesetzt.
 */
class archivAction extends sfActions {

    /**
     * Import logs
     *
     * @var type array
     */
    protected $logs = array();

    /**
     * Import Archiv XML file
     *
     * @param type $request
     */
    public function execute($request) {
        if (!$filename = $request->getParameter('file', false)) {
            $this->getUser()->setFlash('error', 'Archiv-Import: fehlende XML-Datei!');
            $this->redirect('import/index');
        }

        $AI = new ArchivImport(array('dryrun' => true));
        try {
            $AI->import(sfConfig::get('app_import_archiv') . $filename);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }
        $logs = $AI->getLog();

        // write log file
        $logfile = sfConfig::get('app_import_archiv') . $filename . '.log';

        if (file_exists($logfile) && is_file($logfile) && is_writable($logfile)) {
            unlink($logfile);
        }

        $file = fopen($logfile, 'w');
        $content = $this->getPartial('import/logfile', array('title' => 'Beständeübersicht', 'logs' => $logs, 'filename' => $logfile));
        fwrite($file, $content);
        fclose($file);

        // rename import file
        rename(sfConfig::get('app_import_archiv') . $filename, sfConfig::get('app_import_archiv') . '_' . $filename);

        $this->getUser()->setFlash('notice', $logfile);
        $this->redirect('import/index');
    }

    /**
     * Save Tektonik
     *
     * @param xml_object $tektonik
     * @param type $parent_name string
     * @return boolean
     */
    protected function saveTektonik($tektonik = false, $parent = false) {
        if (false === $tektonik || false === $parent) {
            $this->getUser()->setFlash('error', 'saveTektonik: Tektonik oder Parent fehlt!');
            $this->redirect('import/index');
        }

        if (empty($tektonik->Tekt_Titel)) {
            $this->logs['tektonik']['fehler'][] = array(
                'Fehler' => 'Fehlender Tektoniktitel',
                'Tekt_Nr' => trim($tektonik->Tekt_Nr)
            );
            return false;
        }
        if (empty($tektonik->Tekt_Nr)) {
            $this->logs['tektonik']['fehler'][] = array(
                'Fehler' => 'Fehlende Tektoniknummer',
                'Tekt_Titel' => trim($tektonik->Tekt_Titel)
            );
            return false;
        }

        // unique tektonik: Tekt_Nr + node type = 1
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('a')
                ->where('a.signatur = ?', trim($tektonik->Tekt_Nr))
                ->andWhere('a.type = ?', 1)
                ->fetchOne();

        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->type = 1;
            $node->name = trim($tektonik->Tekt_Titel);
            $node->signatur = trim($tektonik->Tekt_Nr);
            $node->beschreibung = trim($tektonik->Tekt_Beschreibung);
            $node->folder = Doctrine_Inflector::urlize(trim($tektonik->Tekt_Nr));

            if ($parent == 'ARCHIV') {
                #die('parent (root): '. $parent);
                $parent_node = Doctrine_Core::getTable('Archiv')->findOneBy('level', 0);
            } else {
                #die('parent: '. $parent);
                $parent_node = Doctrine_Core::getTable('Archiv')
                        ->createQuery('a')
                        ->where('a.signatur = ?', $parent)
                        ->andWhere('a.type = ?', 1)
                        ->fetchOne();
            }
            $node->getNode()->insertAsLastChildOf($parent_node);
            $this->logs['tektonik']['neu'][] = array(
                'Tekt_Nr' => trim($tektonik->Tekt_Nr),
                'Tekt_Titel' => trim($tektonik->Tekt_Titel),
                'Tekt_Beschreibung' => trim($tektonik->Tekt_Beschreibung)
            );
        } else {
            // update
            if ($node->getName() != trim($tektonik->Tekt_Titel)
                    || $node->getBeschreibung() != trim($tektonik->Tekt_Beschreibung)) {
                $this->logs['tektonik']['aktualisierung'][] = array(
                    'vorher' => array(
                        'Tekt_Nr' => $node->getSignatur(),
                        'Tekt_Titel' => $node->getName(),
                        'Tekt_Beschreibung' => $node->getBeschreibung()
                    ),
                    'jetzt' => array(
                        'Tekt_Nr' => trim($tektonik->Tekt_Nr),
                        'Tekt_Titel' => trim($tektonik->Tekt_Titel),
                        'Tekt_Beschreibung' => trim($tektonik->Tekt_Beschreibung)
                    )
                );
                $node->setName(trim($tektonik->Tekt_Titel));
                $node->setBeschreibung(trim($tektonik->Tekt_Beschreibung));
                $node->save();
            }
        }
        return true;
    }

    /**
     * Get Sub-Tektoniken and Bestaende
     *
     * @param type $xmlObj xml object
     * @param type $parent
     */
    protected function getChildrenRecursive($xmlObj) {

        if (false === $xmlObj) {
            $this->getUser()->setFlash('error', 'getChildrenRecursive: XML-Objekt fehlt!');
            $this->redirect('import/index');
        }

        foreach ($xmlObj->children() as $tektonik) {
            if (trim($tektonik->getName()) == 'Tektonik') {
                $this->saveTektonik($tektonik, trim($xmlObj->Tekt_Nr));
                if ($tektonik->Bestand) {
                    foreach ($tektonik->Bestand as $bestand) {
                        $this->saveBestand($bestand, trim($tektonik->Tekt_Nr), 1);
                        $this->getChildrenBestand($bestand);
                    }
                }
                $this->getChildrenRecursive($tektonik);
            }
        }
    }

    /**
     * Save Bestand
     *
     * @param type $bestand
     * @param type $parent
     * @return boolean
     */
    protected function saveBestand($bestand = false, $parent_signature = false, $parent_type = false, $parent_name = false) {
        if (false === $bestand
                || false === $parent_signature
                || false === $parent_type) {
            $this->getUser()->setFlash('notice', 'saveBestand: fehlende(r) Parameter');
            $this->redirect('import/index');
        }

        if (empty($bestand->Bestand_Sig->Sig_alphanum) or empty($bestand->Bestand_Sig->Sig_alphanum)) {
            $this->logs['bestaende']['fehler'][] = array(
                'Fehler' => 'Fehlende Bestandsignatur',
                'Bestandsname' => $bestand->Bestandsname
            );
            return false;
        }

        $bestand_signature = trim($bestand->Bestand_Sig->Sig_nr) . ' ' . trim($bestand->Bestand_Sig->Sig_alphanum) . trim($bestand->Bestand_Sig->Hilfsfeld);

        if (empty($bestand->Bestandsname)) {
            $this->logs['bestaende']['fehler'][] = array(
                'Fehler' => 'Fehlender Bestandsname',
                'Bestandsignatur' => $bestand_signature
            );
            return false;
        }

        // unique bestand: bestand signature + node type = 2 (+ name )
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery()
                ->where('signatur = ?', $bestand_signature)
                ->andWhere('type = ?', 2)
                ->andWhere('name = ?', trim($bestand->Bestandsname))
                ->fetchOne();

        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->signatur = trim($bestand_signature);
            $node->name = trim($bestand->Bestandsname);
            $node->beschreibung = trim($bestand->Bestand_Inhalt);
            $node->type = 2;
            $node->folder = Doctrine_Inflector::urlize(trim($bestand_signature));

            if ($parent_name) {
                $parent_node = Doctrine_Core::getTable('Archiv')
                        ->createQuery()
                        ->where('signatur = ?', trim($parent_signature))
                        ->andWhere('type = ?', $parent_type)
                        ->andWhere('name = ?', $parent_name)
                        ->fetchOne();
            } else {
                $parent_node = Doctrine_Core::getTable('Archiv')
                        ->createQuery()
                        ->where('signatur = ?', trim($parent_signature))
                        ->andWhere('type = ?', $parent_type)
                        ->fetchOne();
            }

            if (!$parent_node) {
                $this->getUser()->setFlash('notice', 'saveBestand: parent_node nicht gefunden! (signatur: ' . $parent_signature . ' / type: ' . $parent_type . ')');
                $this->redirect('import/index');
            }

            $node->getNode()->insertAsLastChildOf($parent_node);

            // insert new bestand model
            #if (!$bestand_model = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($node->signatur)) {
            if (!$bestand_model = Doctrine_Core::getTable('Bestand')->createQuery()->where('bestand_sig = ?', $node->signatur)->andWhere('bestandsname = ?', $node->name)->fetchOne()) {
                // add new bestand object
                $bestand_model = new Bestand();
                $bestand_model->archiv_id = trim($node->getId());
                $bestand_model->bestand_sig = trim($node->signatur);
                $bestand_model->bestandsname = trim($bestand->Bestandsname);
                $bestand_model->laufzeit = trim($bestand->Laufzeit);
                $bestand_model->bestand_inhalt = trim($bestand->Bestand_Inhalt);
                $bestand_model->umfang = trim($bestand->Umfang);
                $bestand_model->bem = trim($bestand->Bem);
                $bestand_model->bestandsgeschichte = trim($bestand->Bestandsgeschichte);
                $bestand_model->sperrvermerk = trim($bestand->Sperrvermerk);
                $bestand_model->save();
                $this->logs['bestaende']['new'][] = trim($bestand->Bestandsname) . ' (' . trim($node->signatur) . ')';
            }
        } else {
            // update node
            if ($node->getBeschreibung() != trim($bestand->Bestand_Inhalt)) {
                $this->logs['bestand']['aktualisierung'][] = array(
                    'vorher' => array(
                        'Signatur' => $node->getSignatur(),
                        'Name' => $node->getName(),
                        'Beschreibung' => $node->getBeschreibung()
                    ),
                    'jetzt' => array(
                        'Signatur' => trim($bestand_signature),
                        'Name' => trim($bestand->Bestandsname),
                        'Beschreibung' => trim($bestand->Bestand_Inhalt)
                    )
                );
                #$node->setName(trim($bestand->Bestandsname));
                $node->setBeschreibung(trim($bestand->Bestand_Inhalt));
                $node->save();
            }
        }
        return true;
    }

    /**
     * Get sub Bestaende
     *
     * @param type $xmlObj
     */
    protected function getChildrenBestand($xmlObj) {
        $parent_signatur = trim($xmlObj->Bestand_Sig->Sig_nr) . ' ' . trim($xmlObj->Bestand_Sig->Sig_alphanum) . trim($xmlObj->Bestand_Sig->Hilfsfeld);
        foreach ($xmlObj->children() as $bestand) {
            if ($bestand->getName() == 'Bestand') {
                $this->saveBestand($bestand, $parent_signatur, 2, $xmlObj->Bestandsname);
                $this->getChildrenBestand($bestand);
            }
        }
    }

}