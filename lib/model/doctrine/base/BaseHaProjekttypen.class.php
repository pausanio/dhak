<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('HaProjekttypen', 'doctrine');

/**
 * BaseHaProjekttypen
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $HaProjekte
 * 
 * @method string              getName()       Returns the current record's "name" value
 * @method Doctrine_Collection getHaProjekte() Returns the current record's "HaProjekte" collection
 * @method HaProjekttypen      setName()       Sets the current record's "name" value
 * @method HaProjekttypen      setHaProjekte() Sets the current record's "HaProjekte" collection
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseHaProjekttypen extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ha_projekttypen');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('HaProjekte', array(
             'local' => 'id',
             'foreign' => 'projekt_type'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $signable0 = new Doctrine_Template_Signable();
        $this->actAs($timestampable0);
        $this->actAs($signable0);
    }
}