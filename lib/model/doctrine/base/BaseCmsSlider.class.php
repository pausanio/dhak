<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CmsSlider', 'doctrine');

/**
 * BaseCmsSlider
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $url
 * @property string $button_text
 * @property clob $text
 * @property string $image
 * @property integer $layout
 * @property boolean $is_active
 * 
 * @method string    getTitle()       Returns the current record's "title" value
 * @method string    getUrl()         Returns the current record's "url" value
 * @method string    getButtonText()  Returns the current record's "button_text" value
 * @method clob      getText()        Returns the current record's "text" value
 * @method string    getImage()       Returns the current record's "image" value
 * @method integer   getLayout()      Returns the current record's "layout" value
 * @method boolean   getIsActive()    Returns the current record's "is_active" value
 * @method CmsSlider setTitle()       Sets the current record's "title" value
 * @method CmsSlider setUrl()         Sets the current record's "url" value
 * @method CmsSlider setButtonText()  Sets the current record's "button_text" value
 * @method CmsSlider setText()        Sets the current record's "text" value
 * @method CmsSlider setImage()       Sets the current record's "image" value
 * @method CmsSlider setLayout()      Sets the current record's "layout" value
 * @method CmsSlider setIsActive()    Sets the current record's "is_active" value
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCmsSlider extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cms_slider');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('url', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('button_text', 'string', 42, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 42,
             ));
        $this->hasColumn('text', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('image', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('layout', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $signable0 = new Doctrine_Template_Signable();
        $sortable0 = new Doctrine_Template_Sortable();
        $this->actAs($timestampable0);
        $this->actAs($signable0);
        $this->actAs($sortable0);
    }
}