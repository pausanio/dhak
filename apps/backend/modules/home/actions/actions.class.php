<?php

/**
 * home actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage news
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class homeActions extends sfActions
{

    public function executeMovefiles(sfWebRequest $request)
    {
        die('best-10 ist umgezogen :=)');
        $path_source = sfConfig::get('app_dokument_pdf') . 'best-10/';
        $path_target = sfConfig::get('app_dokument_pdf') . 'best-10b/';

        $documents = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('bestand_sig = ?', 'Best. 10B')
                ->andWhere('usergenerated = ?', 0)
                ->andWhere('status = ?', 1)
                ->limit(4000)
                ->execute();

        $i = 0;
        foreach ($documents as $document) {
            $filename = $document->getFilename();
            if (!empty($filename)) {
                //$filename = str_replace(".pdf", ".jpg", $filename);
                if (file_exists($path_target . $filename)) {
                    $document->setStatus(0);
                    $document->save();
                } else {
                    if (@rename($path_source . $filename, $path_target . $filename)) {
                        $i++;
                    }
                    $document->setStatus(0);
                    $document->save();
                }
            }
        }
        echo "$i Files verschoben";
        die();
    }

    public function executeMove(sfWebRequest $request)
    {
        die('hello.');

        // Acc. 2 Personalia - id: 3984
        // gehört unter
        // Acc. 2 Deutsches Gesundheitsmuseum, Zentralinstitut für Gesundheitserziehung, Köln-Merheim - id: 4045

        $archiv = Doctrine_Core::getTable('Archiv')->find(3984);
        $other = Doctrine_Core::getTable('Archiv')->find(4045);

        #$archiv->getNode()->moveAsNextSiblingOf($other);
        $archiv->getNode()->moveAsLastChildOf($other);
        $archiv->save();

        die($archiv->getName() . ' verschoben in ' . $other->getName());
    }

    public function executeIndex(sfWebRequest $request)
    {

        // urlize test
        #$dummy = "Best. 30/V";
        #echo Doctrine_Inflector::urlize($dummy); die();
        // remove archiv node
        #$nodes = Doctrine_Core::getTable('Archiv')->createQuery()->where('signatur = ?','Best. 223')->execute();
        #foreach ($nodes as $node) {
        #  echo "<br>". $node->getId();
        #  $node->getNode()->delete();
        #}

        $this->dokument_total = $this->count('SELECT count(id) FROM dokument');
        $this->dokument_import = $this->count('SELECT count(id) FROM dokument WHERE usergenerated = 0');
        $this->dokument_user = $this->count('SELECT count(id) FROM dokument WHERE usergenerated = 1');

        $this->bestand = $this->count('SELECT count(id) FROM bestand');
        $this->ves = $this->count('SELECT count(id) FROM verzeichnungseinheit');

        $this->dokument_unknown = $this->count('SELECT count(id) FROM dokument WHERE archiv_id IS NULL');
        $this->dokument_nobestand = $this->count('SELECT count(id) FROM dokument WHERE bestand_sig = ""');
        $this->dokument_nove = $this->count('SELECT count(id) FROM dokument WHERE verzeichnungseinheit_id IS NULL');

        $this->user_total = $this->count('SELECT count(id) FROM sf_guard_user');
        $this->projekt_total = $this->count('SELECT count(id) FROM ha_projekte');
        $this->patenobjekt_total = $this->count('SELECT count(id) FROM patenobjekt');
    }

    protected function count($query = false)
    {
        if (false === $query) {
            return NULL;
        }
        $conn = Doctrine_Manager::connection();
        $pdo = $conn->execute($query);
        $pdo->setFetchMode(Doctrine_Core::FETCH_NUM);
        $result = $pdo->fetchAll();
        return $result[0][0];
    }

    public function executeDev(sfWebRequest $request)
    {
        die();
        // urlize test
        #$dummy = "Best. 30/V";
        #echo Doctrine_Inflector::urlize($dummy); die();
        // remove archiv node
        #$nodes = Doctrine_Core::getTable('Archiv')->createQuery()->where('signatur = ?','Best. 223')->execute();
        #foreach ($nodes as $node) {
        #  echo "<br>". $node->getId();
        #  $node->getNode()->delete();
        #}
        // add X-Best. 6000
        #$parent_node = Doctrine_Core::getTable('Archiv')->find(1);
        #$node = new Archiv();
        #$node->type = 2;
        #$node->name = 'Unterlagen ohne Zuordnung zur Tektonik';
        #$node->signatur = 'X-Best. 6000';
        #$node->beschreibung = 'Der Bestand enthält Unterlagen, die nach dem Einsturz des Archivs vom 3. März 2009 zunächst nicht mehr ihrer früheren Signatur zugeordnet werden konnten, und bei denen keine Zuordnung zu einem anderen Punkt möglich war, weil die Provenienz nicht feststellbar ist.';
        #$node->getNode()->insertAsLastChildOf($parent_node);
        #echo $node->getId();
        #die();
    }

    public function executeAssignuserdata(sfWebRequest $request)
    {
        die('Hallo Welt!');

        $usergenerated = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('usergenerated = ?', 1)
                ->where('archiv_id IS NULL')
                ->limit(1000)
                ->execute();

        $i = 0;
        foreach ($usergenerated as $doc) {
            $ve = Doctrine_Core::getTable('Verzeichnungseinheit')
                    ->createQuery()
                    ->where('bestand_sig = ?', $doc->getBestandSig())
                    ->andWhere('signatur = ?', $doc->getSignatur())
                    ->execute()
                    ->getFirst();
            if ($ve) {
                $source = sfConfig::get('app_dokument_user_filesystem_old') . $doc->getFilename();
                $dest = sfConfig::get('app_dokument_user_filesystem') . 'u_' . $doc->getFilename();
                if (!copy($source, $dest)) {
                    echo "<br>" . $doc->getFilename() . " konnte nicht kopiert werden.";
                } else {
                    $doc->setFilename('u_' . $doc->getFilename());
                }
                $doc->setArchivId($ve->getArchivId());
                $doc->setVerzeichnungseinheitId($ve->getId());
                $i++;
                sleep(1);
            }
            $doc->setValidated(1);
            $doc->save();
        }

        echo '<br>' . $i . ' von ' . $usergenerated->count() . ' konnten zugeordnet werden.';
        die();
    }

}
