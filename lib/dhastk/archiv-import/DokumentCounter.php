<?php

/**
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author Ivo Bathke <ivo.bathke@gmail.com>
 * @since      2013-02-20
 */
class DokumentCounter extends DhastkImporter {

    protected $archiv = false;

    /**
     * count all VE & Dokument for archiv item for navi
     * count all VE & Dokument per node
     * count dokument & user_docs
     */
    public function count() {
        //reset counter
        $this->conn->execute('UPDATE archiv SET count_ve = 0, count_docs = 0, count_userdocs = 0');

        $this->archiv = Doctrine_Core::getTable('Archiv')->createQuery()->execute();
        foreach ($this->archiv as $node) {
            $doc_count = 0;
            $userdoc_count = 0;
            $pdo = $this->conn->execute('SELECT id
                                         FROM verzeichnungseinheit 
                                         WHERE archiv_id = ' . $node->getId());
            $pdo->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $result = $pdo->fetchAll();
            if ($result) {
                $vz_count = count($result);
                foreach ($result as $res) {
                    //count all normal doks
                    $qc = "SELECT COUNT(id) count
                           FROM dokument
                           WHERE verzeichnungseinheit_id = " . (int) $res['id'] . "
                           AND usergenerated = 0";
                    $pdo_c = $this->conn->execute($qc);
                    $pdo_c->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $doc_c = $pdo_c->fetchAll();
                    if ($doc_c) {
                        if ($doc_c[0]['count'] > 0) {
                            $doc_count += $doc_c[0]['count'];
                        }
                    }
                    //count all usergenerated doks
                    $qc = "SELECT COUNT(id) count
                           FROM dokument
                           WHERE verzeichnungseinheit_id = " . (int) $res['id'] . "
                           AND usergenerated = 1";
                    $pdo_c = $this->conn->execute($qc);
                    $pdo_c->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $doc_c = $pdo_c->fetchAll();
                    if ($doc_c) {
                        if ($doc_c[0]['count'] > 0) {
                            $userdoc_count += $doc_c[0]['count'];
                        }
                    }
                }
                if ($this->dryrun === false) {
                    $node->count_ve = (int) $vz_count;
                    $node->count_docs = (int) $doc_count;
                    $node->count_userdocs = (int) $userdoc_count;
                    $node->save();
                }
                $this->logMessage(DhastkImporter::TYPEVERZEICHNISEINHEIT, $node->signatur . ' - ' . $node->name . ': VZs: ' . $vz_count . ', Docs: ' . $doc_count. ', UserDocs: ' . $userdoc_count);
            } else {
                //count userDoks for none VE
                $qc = "SELECT COUNT(id) count
                       FROM dokument
                       WHERE verzeichnungseinheit_id IS NULL 
                       AND archiv_id = " . (int) $node->id . "
                       AND usergenerated = 1";
                $pdo_c = $this->conn->execute($qc);
                $pdo_c->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                $doc_c = $pdo_c->fetchAll();
                if ($doc_c) {
                    if ($doc_c[0]['count'] > 0) {
                        $userdoc_count += $doc_c[0]['count'];
                    }
                }
                if ($this->dryrun === false) {
                    $node->count_userdocs = (int) $userdoc_count;
                    $node->save();
                }
                $this->logWarning(DhastkImporter::TYPEVERZEICHNISEINHEIT, array(DhastkImporter::LOGWARNING => 'No VZs found for ' . $node->signatur));
                $this->logMessage($node->type, $node->signatur . ' - ' . $node->name . ': UserDocs: ' . $userdoc_count);
            }
        }
    }

    /**
     * add all children counts to current node
     */
    public function addCounts() {
        if ($this->archiv === false) {
            $this->archiv = Doctrine_Core::getTable('Archiv')->createQuery()->execute();
        }
        foreach ($this->archiv as $node) {
            $pdo = $this->conn->execute("SELECT id, count_ve, count_docs, count_userdocs
                                        FROM archiv
                                        WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . "
                                        ORDER BY lft ASC");
            $pdo->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $result = $pdo->fetchAll();

            $addCountVE = 0;
            $addCountDok = 0;
            $addCountUserDok = 0;

            if ($result) {
                foreach ($result as $res) {
                    $addCountVE += $res['count_ve'];
                    $addCountDok += $res['count_docs'];
                    $addCountUserDok += $res['count_userdocs'];
                }
            }

            if ($addCountVE != $node->count_ve || $addCountDok != $node->count_docs || $addCountUserDok != $node->count_userdocs) {
                if ($this->dryrun === false) {
                    $node->count_ve = (int) $addCountVE;
                    $node->count_docs = (int) $addCountDok;
                    $node->count_userdocs = (int) $addCountUserDok;
                    $node->save();
                }
                $this->logMessage(DhastkImporter::TYPETEKTONIK, 'Addition: ' . $node->signatur . ' - ' . $node->name . ': VEs: ' . $addCountVE . ', Docs: ' . $addCountDok . ', UserDocs: ' . $addCountUserDok);
            }
        }
    }

    public function getName() {
        return 'dokumentcounter';
    }

}
