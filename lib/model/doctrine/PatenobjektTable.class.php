<?php

/**
 * PatenobjektTable
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektTable extends Doctrine_Table
{

  /**
   * Static types
   *
   * @var type
   */
  static public $types = array(
    1 => 'Sammelpatenschaften',
    3 => 'Mit Pinsel und Skalpell',
    4 => 'Dicke Bretter bohren'
  );

  /**
   * Static status
   *
   * @var type
   */
  static public $status = array(
    0 => 'intern',
    1 => 'public'
  );

  /**
   * Returns the type
   *
   * @return type
   */
  public function getTypes()
  {
    return self::$types;
  }

    /**
     * Filter Query
     *
     * @param $query
     * @param $value
     * @param $field
     * @return mixed
     */
    public function addFilterQuery($query, $value, $field)
  {
    $rootAlias = $query->getRootAlias();
    $query->where($rootAlias.'.'.$field.' = ?',$value);
    return $query;
  }

  /**
   * Returns the type
   *
   * @return type
   */
  public function getStatus()
  {
    return self::$status;
  }

  /**
   * Returns an instance of this class.
   *
   * @return object PatenobjektTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Patenobjekt');
  }

}
