<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('HaProjekte', 'doctrine');

/**
 * BaseHaProjekte
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $projekt_title
 * @property integer $projekt_type
 * @property string $projekt_einsteller
 * @property string $projekt_bestand
 * @property string $projekt_notiz
 * @property integer $status
 * @property HaProjekttypen $HaProjekttypen
 * @property sfGuardUser $User
 * 
 * @method string         getProjektTitle()       Returns the current record's "projekt_title" value
 * @method integer        getProjektType()        Returns the current record's "projekt_type" value
 * @method string         getProjektEinsteller()  Returns the current record's "projekt_einsteller" value
 * @method string         getProjektBestand()     Returns the current record's "projekt_bestand" value
 * @method string         getProjektNotiz()       Returns the current record's "projekt_notiz" value
 * @method integer        getStatus()             Returns the current record's "status" value
 * @method HaProjekttypen getHaProjekttypen()     Returns the current record's "HaProjekttypen" value
 * @method sfGuardUser    getUser()               Returns the current record's "User" value
 * @method HaProjekte     setProjektTitle()       Sets the current record's "projekt_title" value
 * @method HaProjekte     setProjektType()        Sets the current record's "projekt_type" value
 * @method HaProjekte     setProjektEinsteller()  Sets the current record's "projekt_einsteller" value
 * @method HaProjekte     setProjektBestand()     Sets the current record's "projekt_bestand" value
 * @method HaProjekte     setProjektNotiz()       Sets the current record's "projekt_notiz" value
 * @method HaProjekte     setStatus()             Sets the current record's "status" value
 * @method HaProjekte     setHaProjekttypen()     Sets the current record's "HaProjekttypen" value
 * @method HaProjekte     setUser()               Sets the current record's "User" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseHaProjekte extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ha_projekte');
        $this->hasColumn('projekt_title', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('projekt_type', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('projekt_einsteller', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('projekt_bestand', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('projekt_notiz', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'notnull' => true,
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('HaProjekttypen', array(
             'local' => 'projekt_type',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as User', array(
             'local' => 'created_by',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $signable0 = new Doctrine_Template_Signable();
        $this->actAs($timestampable0);
        $this->actAs($signable0);
    }
}