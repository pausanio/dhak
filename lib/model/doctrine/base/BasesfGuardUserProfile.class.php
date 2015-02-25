<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('sfGuardUserProfile', 'doctrine');

/**
 * BasesfGuardUserProfile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $title_front
 * @property string $title_rear
 * @property string $institution
 * @property string $person_strasse
 * @property string $person_plz
 * @property string $person_ort
 * @property string $person_tel
 * @property integer $role
 * @property integer $status
 * @property integer $person_support
 * @property integer $institution_support
 * @property string $institution_ort
 * @property string $institution_strasse
 * @property string $institution_plz
 * @property string $institution_tel
 * @property sfGuardUser $User
 * 
 * @method integer            getUserId()              Returns the current record's "user_id" value
 * @method string             getTitleFront()          Returns the current record's "title_front" value
 * @method string             getTitleRear()           Returns the current record's "title_rear" value
 * @method string             getInstitution()         Returns the current record's "institution" value
 * @method string             getPersonStrasse()       Returns the current record's "person_strasse" value
 * @method string             getPersonPlz()           Returns the current record's "person_plz" value
 * @method string             getPersonOrt()           Returns the current record's "person_ort" value
 * @method string             getPersonTel()           Returns the current record's "person_tel" value
 * @method integer            getRole()                Returns the current record's "role" value
 * @method integer            getStatus()              Returns the current record's "status" value
 * @method integer            getPersonSupport()       Returns the current record's "person_support" value
 * @method integer            getInstitutionSupport()  Returns the current record's "institution_support" value
 * @method string             getInstitutionOrt()      Returns the current record's "institution_ort" value
 * @method string             getInstitutionStrasse()  Returns the current record's "institution_strasse" value
 * @method string             getInstitutionPlz()      Returns the current record's "institution_plz" value
 * @method string             getInstitutionTel()      Returns the current record's "institution_tel" value
 * @method sfGuardUser        getUser()                Returns the current record's "User" value
 * @method sfGuardUserProfile setUserId()              Sets the current record's "user_id" value
 * @method sfGuardUserProfile setTitleFront()          Sets the current record's "title_front" value
 * @method sfGuardUserProfile setTitleRear()           Sets the current record's "title_rear" value
 * @method sfGuardUserProfile setInstitution()         Sets the current record's "institution" value
 * @method sfGuardUserProfile setPersonStrasse()       Sets the current record's "person_strasse" value
 * @method sfGuardUserProfile setPersonPlz()           Sets the current record's "person_plz" value
 * @method sfGuardUserProfile setPersonOrt()           Sets the current record's "person_ort" value
 * @method sfGuardUserProfile setPersonTel()           Sets the current record's "person_tel" value
 * @method sfGuardUserProfile setRole()                Sets the current record's "role" value
 * @method sfGuardUserProfile setStatus()              Sets the current record's "status" value
 * @method sfGuardUserProfile setPersonSupport()       Sets the current record's "person_support" value
 * @method sfGuardUserProfile setInstitutionSupport()  Sets the current record's "institution_support" value
 * @method sfGuardUserProfile setInstitutionOrt()      Sets the current record's "institution_ort" value
 * @method sfGuardUserProfile setInstitutionStrasse()  Sets the current record's "institution_strasse" value
 * @method sfGuardUserProfile setInstitutionPlz()      Sets the current record's "institution_plz" value
 * @method sfGuardUserProfile setInstitutionTel()      Sets the current record's "institution_tel" value
 * @method sfGuardUserProfile setUser()                Sets the current record's "User" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasesfGuardUserProfile extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sf_guard_user_profile');
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('title_front', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('title_rear', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('institution', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('person_strasse', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('person_plz', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('person_ort', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('person_tel', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('role', 'integer', 2, array(
             'type' => 'integer',
             'default' => '5',
             'notnull' => true,
             'length' => 2,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'default' => '0',
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('person_support', 'integer', 1, array(
             'type' => 'integer',
             'default' => '0',
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('institution_support', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 1,
             ));
        $this->hasColumn('institution_ort', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('institution_strasse', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('institution_plz', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('institution_tel', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $signable0 = new Doctrine_Template_Signable();
        $this->actAs($timestampable0);
        $this->actAs($signable0);
    }
}