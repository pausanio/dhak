<?php

/**
 * Import Archiv Action.
 *
 * Voraussetzungen:
 * - SAFT-XML-Struktur: Tektonik > Untertektonik > (Untertektonik > ..) > Bestand > (Unterbestand > ..)
 *
 * Ergebnis:
 * - XML -> Nested Set
 * - Strukturebenen-Typen: 0 = Root, 1 = Tektonik, 2 = Bestand, 3 = Klassifikation, 4 = Bandserie, 5 = Verzeichniseinheit
 * - Bestand-Metadaten werden in der Tabelle 'bestand' gesichert
 *
 * Update:
 * - Nur über SAFT-XML des Historischen Archivs der Stadt Köln!
 * - Wenn Signatur vorhanden: Name + Beschreibung in Tabelle 'archiv' wird aktualisiert,
 *   Bestand-Metadaten werden über Bestand-SAFT-XML geupdated
 * - Elemente ohne Signatur erhalten die jeweilige Sigantur der übergeordneten Ebene
 *
 * Hinweise:
 * - XML-Strings müssen 'getrimmt' werden, da in der SAFT-Datei Zeilenumbrüche vorhanden sein können
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @author     Ivo Bathke <ivo.bathke@gmail.com>
 * @since      2012-05-31
 *
 * @todo Unterbestände besitzen teilweise gleiche Signaturen wie ihre Eltern. Richtig?
 *       Vorerst so gelöst, dass Zuordnung über Signatur, Type und Name
 *       Achtung: bestand_sig in 'bestand' unique = false gesetzt.
 */
class ArchivImport extends DhastkImporter
{

    function import($file)
    {
        if (!file_exists($file)) {
            throw new Exception('No such file: ' . $file);
        }
        $xml = simplexml_load_file($file);

        $treeObject = Doctrine_Core::getTable('Archiv')->getTree();

        // create tree root if not exist
        if (!$root = Doctrine_Core::getTable('Archiv')->findOneBy('level', 0)) {
            $root = new Archiv();
            $root->setName('DHAK');
            $root->setType(DhastkImporter::INTROOT);
            $root->setSignatur('ARCHIV');
            if ($this->dryrun === false) {
                $treeObject->createRoot($root);
            }
        }

        $this->iterateArchiv($xml, $root->getSignatur(), $root->getType());
    }

    /**
     * Import/ Update Archiv
     *
     * @param SimpleXMLElement $xml
     * @param type $parent_signatur
     * @param type $parent_type
     * @param type $parent_name
     * @return boolean
     */
    protected function iterateArchiv(&$xml, $parent_signatur = false, $parent_type = false, $parent_name = false)
    {
        if (!$xml instanceof SimpleXMLElement) {
            return false;
        }
        foreach ($xml->children() as $child) {
            $recursive = false;
            if (trim($child->getName()) == 'Tektonik') {
                $node = $this->saveTektonik($child, $parent_signatur);
                $recursive = true;
            } else {
                if (trim($child->getName()) == 'Bestand') {
                    $node = $this->saveBestand($child, $parent_signatur, $parent_type);
                    //wenn parent Bestand dann, $->Bestandsname übernehmen
                    $parent_name = $node->name;
                    $recursive = true;
                }
            }
            if ($recursive && $node !== false) {
                $this->iterateArchiv($child, $node->getSignatur(), $node->getType(), $parent_name);
            }
        }
        return false;
    }

    /**
     * Save Tektonik
     *
     * @param xml_object $tektonik
     * @param type $parent_name string
     * @return boolean
     */
    protected function saveTektonik($tektonik = false, $parent = false)
    {
        if (false === $tektonik || false === $parent) {
            throw new Exception('saveTektonik: Tektonik oder Parent fehlt!');
        }

        if (empty($tektonik->Tekt_Titel)) {
            $this->logError(DhastkImporter::TYPETEKTONIK, array(
                'Fehler' => 'Fehlender Tektoniktitel',
                'Tekt_Nr' => trim($tektonik->Tekt_Nr)
            ));
            return false;
        }
        if (empty($tektonik->Tekt_Nr)) {
            $this->logError(DhastkImporter::TYPETEKTONIK, array(
                'Fehler' => 'Fehlende Tektoniknummer',
                'Tekt_Titel' => trim($tektonik->Tekt_Titel)
            ));
            return false;
        }

        // unique tektonik: Tekt_Nr + node type = 1
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('a')
                ->where('a.signatur = ?', trim($tektonik->Tekt_Nr))
                ->andWhere('a.type = ?', DhastkImporter::INTARCHIV)
                ->fetchOne();
        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->type = DhastkImporter::INTARCHIV;
            $node->name = trim($tektonik->Tekt_Titel);
            $node->signatur = trim($tektonik->Tekt_Nr);
            $node->beschreibung = trim($tektonik->Tekt_Beschreibung);

            if ($parent == 'ARCHIV') {
                $parent_node = Doctrine_Core::getTable('Archiv')->findOneBy('level', 0);
            } else {
                $parent_node = Doctrine_Core::getTable('Archiv')
                        ->createQuery('a')
                        ->where('a.signatur = ?', $parent)
                        ->andWhere('a.type = ?', DhastkImporter::INTARCHIV)
                        ->fetchOne();
            }
            if ($this->dryrun === false) {
                $node->getNode()->insertAsLastChildOf($parent_node);
            }

            $this->logNew(DhastkImporter::TYPETEKTONIK, array(
                'Tekt_Nr' => trim($tektonik->Tekt_Nr),
                'Tekt_Titel' => trim($tektonik->Tekt_Titel),
                'Tekt_Beschreibung' => trim($tektonik->Tekt_Beschreibung)
            ));
        } else {
            $this->logUpdate(DhastkImporter::TYPETEKTONIK, array(
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
            ));

            /* NO update :-/
              if ($node->getName() != trim($tektonik->Tekt_Titel) || $node->getBeschreibung() != trim($tektonik->Tekt_Beschreibung)) {
              $node->setName(trim($tektonik->Tekt_Titel));
              $node->setBeschreibung(trim($tektonik->Tekt_Beschreibung));
              if ($this->dryrun === false) {
              $node->save();
              }
              $this->logUpdate(DhastkImporter::TYPETEKTONIK, array(
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
              ));
              } */
        }
        return $node;
    }

    /**
     * Save Bestand
     *
     * @param type $bestand
     * @param type $parent
     * @return boolean
     */
    protected function saveBestand($bestand = false, $parent_signature = false, $parent_type = false, $parent_name = false)
    {
        if (false === $bestand || false === $parent_signature || false === $parent_type) {
            throw new Exception('saveBestand: fehlende(r) Parameter');
        }

        if (empty($bestand->Bestand_Sig->Sig_alphanum) or empty($bestand->Bestand_Sig->Sig_alphanum)) {
            $this->logError(DhastkImporter::TYPEBESTAENDE, array(
                'Fehler' => 'Fehlende Bestandsignatur',
                'Bestandsname' => $bestand->Bestandsname
            ));
            return false;
        }

        $bestand_signature = trim($bestand->Bestand_Sig->Sig_nr) . ' ' . trim($bestand->Bestand_Sig->Sig_alphanum) . trim($bestand->Bestand_Sig->Hilfsfeld);

        if (empty($bestand->Bestandsname)) {
            $this->logError(DhastkImporter::TYPEBESTAENDE, array(
                'Fehler' => 'Fehlender Bestandsname',
                'Bestandsignatur' => $bestand_signature
            ));
            return false;
        }

        // unique bestand: bestand signature + node type = 2 + name
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery()
                ->where('signatur = ?', $bestand_signature)
                ->andWhere('type = ?', DhastkImporter::INTBESTAND)
                ->andWhere('name = ?', trim($bestand->Bestandsname))
                ->fetchOne();
        $this->conn->beginTransaction();
        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->signatur = trim($bestand_signature);
            $node->name = trim($bestand->Bestandsname);
            $node->beschreibung = trim($bestand->Bestand_Inhalt);
            $node->type = DhastkImporter::INTBESTAND;

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

            if ($this->dryrun === false) {
                if (!$parent_node) {
                    throw new Exception('saveBestand: parent_node nicht gefunden! (signatur: ' . $parent_signature . ' / type: ' . $parent_type . ')');
                }
                $node->getNode()->insertAsLastChildOf($parent_node);
            }

            // insert new bestand model
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
                if ($this->dryrun === false) {
                    $bestand_model->save();
                }
                $this->logNew(DhastkImporter::TYPEBESTAENDE, array('Name' => trim($bestand->Bestandsname) . ' (' . trim($node->signatur) . ')'));
            }
        } else {
            $this->logUpdate(DhastkImporter::TYPEBESTAENDE, array(
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
            ));
            /* NO update node :-/
              if ($node->getBeschreibung() != trim($bestand->Bestand_Inhalt)) {
              $node->setBeschreibung(trim($bestand->Bestand_Inhalt));
              if ($this->dryrun === false) {
              $node->save();
              }
              $this->logUpdate(DhastkImporter::TYPEBESTAENDE, array(
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
              ));
              } */
        }
        $this->conn->commit();
        return $node;
    }

    public function getName()
    {
        return 'archiv';
    }

}
