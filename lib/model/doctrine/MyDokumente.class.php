<?php

/**
 * MyDokumente
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class MyDokumente extends BaseMyDokumente
{

    public function getByUserAndDokumentIds($userId, $veId, $dokId){
        $query = $this->createQuery()
            ->where('created_by = ?', (int)$userId);
        if (!empty($veId)) {
            $query->andWhere('verzeichnungseinheit_id = ?', (int)$veId);
        } else {
            $query->andWhere('dokument_id = ?', (int)$dokId);
        }
        return $query->fetchOne();
    }

}
