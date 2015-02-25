<?php

/**
 * Patenobjekt
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer
 * @since      2012-03-12
 */
class Patenobjekt extends BasePatenobjekt
{

  public function getTypeName()
  {
    $types = Doctrine_Core::getTable('Patenobjekt')->getTypes();
    return $this->getType() ? $types[$this->getType()] : '';
  }

  public function getStatusName()
  {
    $status = Doctrine_Core::getTable('Patenobjekt')->getStatus();
    return $this->getStatus() ? $status[$this->getStatus()] : '';
  }

  public static function getObjectsByType($type = 1)
  {
    $q = Doctrine_Core::getTable('Patenobjekt')
        ->createQuery('o')
        ->select('*')
        ->from('Patenobjekt o')
        ->leftJoin('o.PatenobjektPhotos p')
        ->where('o.status=?', 1)
        ->orderBy('o.verfuegbar DESC, o.tekt_nr, o.titel, p.position');

    if (!is_null($type)) {
      $q->andWhere("o.type = ?", array($type));
    }
    return $q;
  }

}
