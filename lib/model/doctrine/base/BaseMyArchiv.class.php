<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('MyArchiv', 'doctrine');

/**
 * BaseMyArchiv
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $archiv_id
 * @property clob $personal_comments
 * @property Archiv $Archiv
 * @property sfGuardUser $User
 * 
 * @method integer     getArchivId()          Returns the current record's "archiv_id" value
 * @method clob        getPersonalComments()  Returns the current record's "personal_comments" value
 * @method Archiv      getArchiv()            Returns the current record's "Archiv" value
 * @method sfGuardUser getUser()              Returns the current record's "User" value
 * @method MyArchiv    setArchivId()          Sets the current record's "archiv_id" value
 * @method MyArchiv    setPersonalComments()  Sets the current record's "personal_comments" value
 * @method MyArchiv    setArchiv()            Sets the current record's "Archiv" value
 * @method MyArchiv    setUser()              Sets the current record's "User" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMyArchiv extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('my_archiv');
        $this->hasColumn('archiv_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('personal_comments', 'clob', null, array(
             'type' => 'clob',
             ));


        $this->index('dokument_index', array(
             'fields' => 
             array(
              0 => 'archiv_id',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Archiv', array(
             'local' => 'archiv_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('sfGuardUser as User', array(
             'local' => 'created_by',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $signable0 = new Doctrine_Template_Signable();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($signable0);
        $this->actAs($timestampable0);
    }
}