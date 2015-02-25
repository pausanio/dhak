<?php

/**
 * MyVerzeichnungseinheit form base class.
 *
 * @method MyVerzeichnungseinheit getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMyVerzeichnungseinheitForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'verzeichnungseinheit_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'add_empty' => true)),
      'viewer_settings'         => new sfWidgetFormTextarea(),
      'personal_comments'       => new sfWidgetFormTextarea(),
      'created_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'updated_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'verzeichnungseinheit_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'required' => false)),
      'viewer_settings'         => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'personal_comments'       => new sfValidatorString(array('required' => false)),
      'created_by'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'updated_by'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('my_verzeichnungseinheit[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyVerzeichnungseinheit';
  }

}
