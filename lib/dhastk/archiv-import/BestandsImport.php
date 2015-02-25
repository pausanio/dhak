<?php

/**
 * Import Findmittel
 *
 * Voraussetzungen:
 * - Bestände besitzen eine eindeutige Signatur
 * - Tektoniken besitzen eine eindeutige Nr (Signatur)
 * - Elemente ohne Signatur erhalten die jeweilige Sigantur der übergeordneten Ebene
 *
 * Updates:
 * - Tektonik: Namens- und Beschreibungs-Änderungen werden geupdated
 * - Bestand: Name, Laufzeit, Bestand_Inhalt, Umfang, Bem, Bestandsgeschichte, Sperrvermerk werden geupdated
 * - Klassifikationen: werden in den Tree/Archiv geinsert
 * - Verzeichniseinheit: insert/update Verzeichniseinheit
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @author     Ivo Bathke <ivo.bathke@gmail.com>
 * @since      2012-05-31
 */
class BestandsImport extends DhastkImporter
{

    protected $bestand_signatur;

    function import($resource)
    {
        if (!file_exists($resource)) {
            throw new Exception('No such file: ' . $resource);
        }

        $this->findDuplicateSignatures($resource);

        $xml = simplexml_load_file($resource);

        // Update Bestand object
        $findmittel = array(
            'fm_name' => (string) $xml->Findmittel_Info->FM_Name,
            'fm_sig' => (string) $xml->Findmittel_Info->FM_Sig,
            'laufzeit' => (string) $xml->Findmittel_Info->Laufzeit->LZ_Text,
            'einleitung' => (string) $xml->Findmittel_Info->Einleitung->Text,
            'text' => (string) $xml->Findmittel_Info->Text,
            'rechtsstatus' => (string) $xml->Findmittel_Info->Bestand_Info->Rechtsstatus,
            'umfang' => (string) $xml->Findmittel_Info->Bestand_Info->Umfang,
            'bem' => (string) $xml->Findmittel_Info->Bestand_Info->Bem,
            'sperrvermerk' => (string) $xml->Findmittel_Info->Sperrvermerk,
            'sperrvermerk_art' => (string) $xml->Findmittel_Info->Sperrvermerk['Art'],
            'sperrvermerk_datum' => (string) $xml->Findmittel_Info->Sperrvermerk->Datum
        );
        if ($xml->Findmittel_Info->Bestand_Info->Abg_Stelle) {
            foreach ($xml->Findmittel_Info->Bestand_Info->Abg_Stelle as $abg_stelle) {
                $findmittel['abg_stelle'][] = (string) $abg_stelle;
            }
            $findmittel['abg_stelle'] = implode("; ", $findmittel['abg_stelle']);
        } else {
            $findmittel['abg_stelle'] = '';
        }

        if (!$archiv = Doctrine_Core::getTable('Archiv')->findOneBySignatur($findmittel['fm_sig'])) {
            throw new Exception('Archiv_Model: Der angegebene Bestand (' . $findmittel['fm_sig'] . ') konnte nicht gefunden werden.');
        }

        if (!$bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($findmittel['fm_sig'])) {
            throw new Exception('Bestand_Model: Der angegebene Bestand (' . $findmittel['fm_sig'] . ') konnte nicht gefunden werden.');
        }

        #$this->conn->beginTransaction();

        $bestand->setLaufzeit($findmittel['laufzeit']);
        $bestand->setBestandInhalt($findmittel['einleitung']);
        $bestand->setUmfang($findmittel['umfang']);
        $bestand->setBem($findmittel['bem']);
        $bestand->setSperrvermerk($findmittel['Sperrvermerk']);
        $bestand->setAbgStelle($findmittel['abg_stelle']);
        if ($this->dryrun === false) {
            $bestand->save();
        }
        $this->logUpdate(DhastkImporter::TYPEFINDMITTEL, $findmittel);
        $this->bestand_signatur = $bestand->getBestandSig();
        $this->importKlassifikationen($xml, $archiv->getId());

        #$this->conn->commit();
    }

    protected function importKlassifikationen(SimpleXMLElement &$xml, $parentId)
    {
        if ($xml->Klassifikation) {
            foreach ($xml->Klassifikation as $klassifikation) {
                $klassifikation_id = $this->saveKlassifikation($klassifikation, $parentId);
                $this->importKlassifikationen($klassifikation, $klassifikation_id);
            }
        } else {
            return false;
        }
    }

    /**
     * Save Klassifikation
     *
     * @param xml_object $tektonik
     * @param type $parent_name
     * @return int
     */
    protected function saveKlassifikation($klassifikation = false, $parent_id = false)
    {
        if (false === $klassifikation || false === $parent_id) {
            throw new Exception('saveKlassifikation: Fehlender Parameter!');
        }
        if (empty($klassifikation->Klass_Titel)) {
            throw new Exception('saveKlassifikation: Fehlender Klassifikationsname');
        }
        // unique = klass_titel + type = 3 + bestand signature
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('c')
                ->where('c.name = ?', $klassifikation->Klass_Titel)
                ->andWhere('c.signatur = ?', $this->bestand_signatur)
                ->andWhere('c.type = ?', DhastkImporter::INTKLASSIFIKATION)
                ->fetchOne();

        if (!$node) {
            // add new archiv node
            $node = new Archiv();
            $node->name = $klassifikation->Klass_Titel;
            $node->type = DhastkImporter::INTKLASSIFIKATION;
            $node->signatur = $this->bestand_signatur;
            if ($this->dryrun === false) {
                $node->getNode()->insertAsLastChildOf(Doctrine_Core::getTable('Archiv')->findOneById($parent_id));
            }
            $this->logNew(DhastkImporter::TYPEKLASSIFIKATION, array('Titel' => (string) $node->name, 'Signatur' => (string) $node->signatur));
        } else {
            //TODO update ?
            // $this->logUpdate(DhastkImporter::TYPEKLASSIFIKATION, array('Titel' => $node->name, 'Signatur' => $node->signatur));
        }

        // Get Verzeichnungseinheiten
        if ($klassifikation->Verzeichnungseinheiten) {
            foreach ($klassifikation->Verzeichnungseinheiten->children() as $verzeichnungseinheit) {
                switch ($verzeichnungseinheit->getName()) {
                    case 'Sachakte':
                    case 'Urkunde':
                    case 'Foto_Plakat':
                    case 'Karte':
                    case 'Film':
                    case 'Grundbuch_akte':
                        $this->saveVerzeichnungseinheit($verzeichnungseinheit, $node->getId());
                        break;
                    case 'Bandserie':
                        #$this->logs['verzeichnungseinheiten']['bandserie']['gefunden'][] = $verzeichnungseinheit->Titel;
                        $this->saveBandserie($verzeichnungseinheit, $node->getId());
                        break;
                    default:
                        $this->logError(DhastkImporter::TYPEVERZEICHNISEINHEIT, array('Fehler' => 'Unbekannte Struktureinheit: ' . $verzeichnungseinheit->getName()));
                        break;
                }
            }
        }

        return $node->getId();
    }

    /**
     * Save Verzeichnungseinheit
     *
     * Verzeichnungseinheiten haben nicht zwingend einen Titel (Urkunden in Best. 214)
     * bei fehlendem Titel wird die Sigantur verwendet
     *
     * @param type $veinheit
     * @param type $archiv_id
     */
    protected function saveVerzeichnungseinheit($veinheit = false, $archiv_id)
    {

        if (empty($veinheit->Signatur)) {
            $this->logError(DhastkImporter::TYPEVERZEICHNISEINHEIT, array('Fehler' => 'Fehlende Signatur: ' . $veinheit->Titel));
            return false;
        }

        // Verweise
        if ($veinheit->Signatur == '_' || $veinheit->Signatur == 'Verweis') {
            $this->saveVerweise($veinheit, $archiv_id);
        }

        // unique = archiv_id + signatur
        $item = Doctrine_Core::getTable('verzeichnungseinheit')
                ->createQuery('c')
                ->where('c.archiv_id = ?', $archiv_id)
                ->andWhere('c.signatur = ?', trim($veinheit->Signatur))
                ->fetchOne();

        $logtype = 'update';
        if (!$item) {
            $item = new Verzeichnungseinheit();
            $logtype = 'new';
        }

        /**
         * Hilfsfelder
         *
         * <Hilfsfeld Art="Archivgutart">Urkunde_vormodern</Hilfsfeld>
         * <Hilfsfeld Art="Titel">Lorem ipsum</Hilfsfeld> z. B. bei Archivgutart=Grundbuch_akte
         */
        $titel = (string) trim($veinheit->Titel);
        $archivgutart = '';
        foreach ($veinheit->Hilfsfeld as $hilfsfeld) {
            foreach ($hilfsfeld->attributes() as $attr => $val) {
                if ($val == 'Archivgutart') {
                    $archivgutart = (string) trim($hilfsfeld);
                }
                if ($val == 'Titel') {
                    $titel = (string) trim($hilfsfeld);
                }
            }
        }

        if (empty($titel)) {
            $titel = $veinheit->Signatur;
        }

        $item->archiv_id = $archiv_id;
        $item->signatur = trim((string) $veinheit->Signatur);
        $item->laufzeit = trim((string) $veinheit->Laufzeit->LZ_Text);
        $item->beschreibung = trim((string) $veinheit->Beschreibung);
        $item->titel = $titel;
        $item->sperrvermerk = trim((string) $veinheit->Sperrvermerk);
        $item->bestellsig = trim((string) $veinheit->Bestellsig);

        // XML: <Hilfsfeld Art="Archivgutart">Urkunde_vormodern</Hilfsfeld>
        $item->archivgutart = $archivgutart;
        $item->altsig = trim((string) $veinheit->Altsig);
        $item->umfang = trim((string) $veinheit->Umfang);
        $item->enthaelt = trim((string) $veinheit->Enthaelt); // Best. 30V
        $item->bem = trim((string) $veinheit->Bem);
        $item->bestand_sig = trim((string) $this->bestand_signatur);
        if ($this->dryrun === false) {
            $item->save();
        }

        /*
          echo "\n --------------------------------------- \n";
          echo "\n signatur: " . $item->signatur;
          echo "\n titel: " . $item->titel;
          echo "\n archivgutart: " . $item->archivgutart;
         */

        // Import <Vorgang_Sachakte>
        if ($veinheit->Vorgang_Sachakte) {
            $this->saveVorgangSachakte($veinheit);
        }

        $logdata = array('Titel' => (string) $item->titel, 'Signatur' => (string) $item->signatur);
        if ($logtype == 'new') {
            $this->logNew(DhastkImporter::TYPEVERZEICHNISEINHEIT, $logdata);
        } else {
            $this->logUpdate(DhastkImporter::TYPEVERZEICHNISEINHEIT, $logdata);
        }
    }

    /**
     * Save Bandserie
     *
     * @param type $veinheit
     * @param type $archiv_id
     */
    protected function saveBandserie($veinheit = false, $archiv_id)
    {
        $node = Doctrine_Core::getTable('Archiv')
                ->createQuery('a')
                ->where('a.name = ?', trim($veinheit->Serientitel))
                ->andWhere('a.signatur = ?', trim($this->bestand_signatur))
                ->andWhere('a.type = ?', DhastkImporter::INTBANDSERIE)
                ->fetchOne();

        if (!$node) {
            $node = new Archiv();
            $node->name = trim($veinheit->Serientitel);
            $node->type = DhastkImporter::INTBANDSERIE;
            $node->signatur = trim($this->bestand_signatur);
            if ($this->dryrun === false) {
                $node->getNode()->insertAsLastChildOf(Doctrine_Core::getTable('Archiv')->findOneById($archiv_id));
            }
            $this->logNew(DhastkImporter::TYPEVERZEICHNISEINHEIT, array('Name' => $node->getName(),
                'ID' => $node->getId()));
        } elseif (trim($veinheit->Serientitel) != $node->name) {
            $node->name = trim($veinheit->Serientitel);
            if ($this->dryrun === false) {
                $node->save();
            }
            $this->logUpdate(DhastkImporter::TYPEVERZEICHNISEINHEIT, array('Name' => $node->getName(),
                'ID' => $node->getId()));
        }
        //TODO update if Serientitel differ
        // save children
        foreach ($veinheit->children() as $band) {
            switch ($band->getName()) {
                case 'Sachakte':
                case 'Urkunde':
                case 'Foto_Plakat':
                case 'Karte':
                case 'Film':
                case 'Grundbuch_akte':
                    $this->saveVerzeichnungseinheit($band, $node->getId());
                    break;
                case 'Bandserie':
                    echo "ACHTUNG SUB-Bandserien werden falsch im Baum abgelegt. Bitte manuel verschieben :-/";
                    $this->saveBandserie($band, $node->getId());
                    break;
                case 'Serientitel':
                    continue;
                default:
                    $this->logError(DhastkImporter::TYPEVERZEICHNISEINHEIT, array('Fehler' => 'Unbekannte Struktureinheit: ' . $band->getName() . ' in ' . $node->name));
                    break;
            }
        }
    }

    /**
     * Import/ Update Vorgang
     *
     * @todo logging :-)
     *
     * @param type $veinheit
     * @return boolean
     */
    protected function saveVorgangSachakte($veinheit = false)
    {
        if (false === $veinheit) {
            return false;
        }

        $vorgang = Doctrine_Core::getTable('Vorgang')
                ->createQuery()
                ->where('bestand_sig = ?', $this->bestand_signatur)
                ->andWhere('ve_signatur = ?', trim($veinheit->Signatur))
                ->fetchOne();
        if ($vorgang) {
            $vorgang->setLaufzeit(trim($veinheit->Vorgang_Sachakte->Laufzeit->LZ_Text));
            $vorgang->setTitel(trim($veinheit->Vorgang_Sachakte->Titel));
            $vorgang->setBestellsig(trim($veinheit->Vorgang_Sachakte->Bestellsig));
            $vorgang->setArchivgutArt(trim($veinheit->Vorgang_Sachakte->Hilfsfeld));
            $vorgang->setUmfang(trim($veinheit->Vorgang_Sachakte->Umfang));
            if ($this->dryrun === false) {
                $vorgang->save();
            }
        } else {
            $vorgang = new Vorgang();
            $vorgang->setBestandSig($this->bestand_signatur);
            $vorgang->setVeSignatur(trim($veinheit->Signatur));
            $vorgang->setLaufzeit(trim($veinheit->Vorgang_Sachakte->Laufzeit->LZ_Text));
            $vorgang->setTitel(trim($veinheit->Vorgang_Sachakte->Titel));
            $vorgang->setBestellsig(trim($veinheit->Vorgang_Sachakte->Bestellsig));
            $vorgang->setArchivgutArt(trim($veinheit->Vorgang_Sachakte->Hilfsfeld));
            $vorgang->setUmfang(trim($veinheit->Vorgang_Sachakte->Umfang));
            if ($this->dryrun === false) {
                $vorgang->save();
            }
        }
    }

    /**
     * Import Verweis
     *
     * @param type $veinheit
     * @param type $archiv_id
     * @return boolean
     */
    protected function saveVerweise($veinheit = false, $archiv_id = false)
    {
        if (false === $veinheit || false === $archiv_id) {
            return false;
        }

        if ($this->dryrun === false) {
            $verweise = Doctrine_Core::getTable('Verweis')
                    ->createQuery()
                    ->where('archiv_id = ?', $archiv_id)
                    ->execute();
            if ($verweise) {
                foreach ($verweise as $verweis) {
                    $verweis->delete();
                }
            }
        }

        $verweis = new Verweis();
        $verweis->archiv_id = $archiv_id;
        $verweis->laufzeit = trim((string) $veinheit->Laufzeit->LZ_Text);
        $verweis->beschreibung = trim((string) $veinheit->Beschreibung);
        $verweis->titel = trim((string) $veinheit->Titel);
        $verweis->sperrvermerk = trim((string) $veinheit->Sperrvermerk);
        $verweis->bestellsig = trim((string) $veinheit->Bestellsig);
        $verweis->archivgutart = trim((string) $veinheit->Hilfsfeld);
        $verweis->altsig = trim((string) $veinheit->Altsig);
        $verweis->umfang = trim((string) $veinheit->Umfang);
        $verweis->bem = trim((string) $veinheit->Bem);
        $verweis->bestand_sig = trim((string) $this->bestand_signatur);

        if ($this->dryrun === false) {
            $verweis->save();
        }
    }

    public function getSignatur()
    {
        return $this->bestand_signatur;
    }

    public function getName()
    {
        return 'bestand';
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
            throw new Exception('Der Bestand kann nicht importiert werden! Signatur-Duplkate gefunden: ' . implode(', ', $duplicates));
            return false;
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
