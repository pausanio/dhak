<?php

/**
 * VerzeichnungseinheitTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class VerzeichnungseinheitTable extends Doctrine_Table
{

  /**
   * Returns an instance of this class.
   *
   * @return object VerzeichnungseinheitTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Verzeichnungseinheit');
  }

  public function retrieveBackendVerzeichnungseinheitList(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    return $q;
  }

}