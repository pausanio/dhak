<?php

/**
 * Verzeichnungseinheit
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Verzeichnungseinheit extends BaseVerzeichnungseinheit {

    /**
     * gets DocumentCount
     *
     * @todo this should become a schema relation
     * @return int count
     */
    public function getCountDocs() {
        $q = "SELECT COUNT(id) AS count
              FROM dokument
              WHERE verzeichnungseinheit_id = " . (int) $this->id;
        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $db->execute($q);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $r = $result->fetchAll();
        if ($r) {
            return $r[0]['count'];
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getSignaturTitle() {
        return $this->getBestandSig() .' - '. $this->getSignatur() . ', ' . $this->getTitel();
    }

    /**
     * @return string
     */
    public function getSignaturSlug() {
        return str_replace(array('/',':',"\r", "\t", "\n"), '~', $this->getBestandSig() .'+'. $this->getSignatur() . '+' . substr($this->getTitel(), 0, 50));
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getMyVerzeichnungseinheitenForUser($userId){
        return Doctrine_Core::getTable('MyVerzeichnungseinheit')
            ->createQuery()
            ->where('created_by = ?', (int)$userId)
            ->andWhere('verzeichnungseinheit_id = ?', (int)$this->getId())
            ->fetchOne();
    }

}
