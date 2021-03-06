<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CmsText', 'doctrine');

/**
 * BaseCmsText
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $module
 * @property string $name
 * @property clob $text
 * @property string $comment
 * 
 * @method string  getModule()  Returns the current record's "module" value
 * @method string  getName()    Returns the current record's "name" value
 * @method clob    getText()    Returns the current record's "text" value
 * @method string  getComment() Returns the current record's "comment" value
 * @method CmsText setModule()  Sets the current record's "module" value
 * @method CmsText setName()    Sets the current record's "name" value
 * @method CmsText setText()    Sets the current record's "text" value
 * @method CmsText setComment() Sets the current record's "comment" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCmsText extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cms_text');
        $this->hasColumn('module', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('text', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('comment', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $signable0 = new Doctrine_Template_Signable();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($signable0);
        $this->actAs($timestampable0);
    }
}