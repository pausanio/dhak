<?php

/**
 * HaNews form base class.
 *
 * @method HaNews getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseHaNewsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'news_einsteller' => new sfWidgetFormInputText(),
      'news_title'      => new sfWidgetFormInputText(),
      'news_text'       => new sfWidgetFormTextarea(),
      'publish_date'    => new sfWidgetFormDateTime(),
      'image'           => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'created_by'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'news_einsteller' => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'news_title'      => new sfValidatorString(array('max_length' => 255)),
      'news_text'       => new sfValidatorString(),
      'publish_date'    => new sfValidatorDateTime(array('required' => false)),
      'image'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'created_by'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ha_news[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HaNews';
  }

}
