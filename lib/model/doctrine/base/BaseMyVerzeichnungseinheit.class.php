<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('MyVerzeichnungseinheit', 'doctrine');

/**
 * BaseMyVerzeichnungseinheit
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $verzeichnungseinheit_id
 * @property string $viewer_settings
 * @property clob $personal_comments
 * @property Verzeichnungseinheit $Verzeichnungseinheit
 * @property sfGuardUser $User
 * 
 * @method integer                getVerzeichnungseinheitId()  Returns the current record's "verzeichnungseinheit_id" value
 * @method string                 getViewerSettings()          Returns the current record's "viewer_settings" value
 * @method clob                   getPersonalComments()        Returns the current record's "personal_comments" value
 * @method Verzeichnungseinheit   getVerzeichnungseinheit()    Returns the current record's "Verzeichnungseinheit" value
 * @method sfGuardUser            getUser()                    Returns the current record's "User" value
 * @method MyVerzeichnungseinheit setVerzeichnungseinheitId()  Sets the current record's "verzeichnungseinheit_id" value
 * @method MyVerzeichnungseinheit setViewerSettings()          Sets the current record's "viewer_settings" value
 * @method MyVerzeichnungseinheit setPersonalComments()        Sets the current record's "personal_comments" value
 * @method MyVerzeichnungseinheit setVerzeichnungseinheit()    Sets the current record's "Verzeichnungseinheit" value
 * @method MyVerzeichnungseinheit setUser()                    Sets the current record's "User" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMyVerzeichnungseinheit extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('my_verzeichnungseinheit');
        $this->hasColumn('verzeichnungseinheit_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('viewer_settings', 'string', 4000, array(
             'type' => 'string',
             'length' => 4000,
             ));
        $this->hasColumn('personal_comments', 'clob', null, array(
             'type' => 'clob',
             ));


        $this->index('dokument_index', array(
             'fields' => 
             array(
              0 => 'verzeichnungseinheit_id',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Verzeichnungseinheit', array(
             'local' => 'verzeichnungseinheit_id',
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