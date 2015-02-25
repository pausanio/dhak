<?php

/**
 * Import Dokumente / Verzeichnungseinheiten
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @author     Ivo Bathke <ivo.bathke@gmail.com>
 * @since      2013-03-05
 */
class DokumentImport extends DhastkImporter
{

  function import($resource, $validate = true)
  {
    if ($validate) {
      //validate CSV
      $file = $this->validate($resource);
    } else {
      $file = $this->loadFile($resource);
    }

    foreach ($file as $line) {

      // get Verzeichnungseinheit (ve)
      $vz = Doctrine_Core::getTable('verzeichnungseinheit')
          ->createQuery('v')
          ->where('v.signatur = ?', trim($line[2]))
          ->andWhere('v.bestand_sig = ?', trim($line[1]))
          ->fetchOne();
      if (!$vz) {
        throw new Exception('Kein VE gefunden für: ' . $line[2] . ' & ' . $line[1]);
      }

      $doc = Doctrine_Core::getTable('dokument')
          ->createQuery('d')
          ->where('d.filename = ?', trim($line[0]))
          ->andWhere('d.signatur = ?', trim($line[2]))
          ->andWhere('d.bestand_sig = ?', trim($line[1]))
          ->andWhere('d.position = ?', trim($line[3]))
          ->fetchOne();
      if (!$doc) {
        //new
        $doc = new Dokument();
        $logtype = 'new';
      } else {
        $logtype = 'update';
      }
      $doc->archiv_id = $vz->archiv_id;
      $doc->bestand_sig = trim($line[1]);
      $doc->signatur = trim($line[2]);
      $doc->verzeichnungseinheit_id = $vz->id;
      $doc->filename = trim($line[0]);
      $doc->position = trim($line[3]);
      $doc->vorlagentyp_id = 2; // digitales Bild
      $doc->validated = 1;
      $doc->status = 1;
      if ($this->dryrun === false) {
        $doc->save();
      }
      if ($logtype == 'new') {
        $this->logNew(DhastkImporter::TYPEDOKUMENT, array('File' => $doc->filename,
          'Signatur' => $doc->signatur));
      } else {
        $this->logUpdate(DhastkImporter::TYPEDOKUMENT, array('File' => $doc->filename,
          'Signatur' => $doc->signatur));
      }
    }
  }

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
  public function validate($resource, $write2file = false)
  {

    $file = $this->loadFile($resource);

    // convert array
    foreach ($file as $row) {
      $row = array_map(function ($val) {
            return utf8_encode($val);
          }, $row);

      if (!empty($row[0]) || !empty($row[1]) || !empty($row[2]) || !empty($row[3])) {
        $documents[] = array(
          'file' => trim($row[0]), #trim(str_replace('.pdf', '.jpeg', $row[0]))
          'bestand_sig' => trim($row[1]),
          've_sig' => trim($row[2]),
          'pos' => trim($row[3])
        );
      }
    }
    $this->logMessage(DhastkImporter::TYPEDOKUMENT, 'Dokumente gesamt: ' . count($documents));

    // check Bestand
    foreach ($documents as $document) {
      $sigs[] = $document['bestand_sig'];
    }
    if (count(array_unique($sigs)) > 1) {
      throw new Exception('Es wurden mehrere Bestände gefunden: ' . join(', ', array_unique($bestand)));
    }
    if (!$bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($sigs[0])) {
      throw new Exception('Der Bestand ' . $sigs[0] . ' existiert nicht.');
    }

    // check Verzeichnungseinheiten
    foreach ($documents as $document) {
      $verzeichnungseinheiten[] = $document['ve_sig'];
    }
    $verzeichnungseinheiten = array_unique($verzeichnungseinheiten);

    foreach ($verzeichnungseinheiten as $verzeichnungseinheit) {

      $_ve = Doctrine_Core::getTable('Verzeichnungseinheit')
          ->createQuery()
          ->where('signatur = ?', $verzeichnungseinheit)
          ->andWhere('bestand_sig = ?', $bestand->getBestandSig())
          ->fetchOne();
      if (!$_ve) {
        // unset rows with not available Verzeichnunseinheiten
        foreach ($documents as $key => $document) {
          if ($document['ve_sig'] == $verzeichnungseinheit) {
            unset($documents[$key]);
            $this->logError(DhastkImporter::TYPEDOKUMENT, array('Fehler' => 'VZ fehlt: ' . $verzeichnungseinheit));
          }
        }
      }
    }

    // check filenames
//        foreach ($documents as $key => $document) {
//            if (!file_exists($importpath . '/' . $document['file'])) {
//                // unset rows with not available images
//                unset($documents[$key]);
//                $this->logError(DhastkImporter::TYPEDOKUMENT, array('Fehler' => 'Bild fehlt: ' . $document['file'] . ' (' . $document['bestand_sig'] . ' - ' . $document['ve_sig'] . ')'));
//            }
//        }

    $this->logMessage(DhastkImporter::TYPEDOKUMENT, 'Dokumente valid: ' . count($documents));

    if ($write2file) {
      // write validated csv file
      $extension = explode('.', $resource);
      array_pop($extension);
      $valfile = join('.', $extension) . '.valid.csv';
      if (file_exists($valfile) && is_file($valfile) && is_writable($valfile)) {
        unlink($valfile);
      }
      $file = fopen($valfile, 'w');
      foreach ($documents as $document) {
        fputs($file, trim($document['file']) . ';' . trim($document['bestand_sig']) . ';' . trim($document['ve_sig']) . ';' . trim($document['pos']) . "\n");
      }
      fclose($file);
      return true;
    } else {
      //remove keys
      foreach ($documents as $key => $value) {
        $documents[$key] = array_values($value);
      }
      return $documents;
    }
  }

  protected function loadFile($resource)
  {
    try {
      $file = new \SplFileObject($resource, 'rb');
    } catch (\RuntimeException $e) {
      throw new Exception(sprintf('Error opening file "%s".', $resource));
    }

    $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
    $file->setCsvControl(';');

    return $file;
  }
  
  public function getName() {
      return 'verzeichnungseinheit';
  }

}
