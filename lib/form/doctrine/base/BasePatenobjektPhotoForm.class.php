<?php

/**
 * PatenobjektPhoto form base class.
 *
 * @method PatenobjektPhoto getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePatenobjektPhotoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'patenobjekt_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Patenobjekt'), 'add_empty' => true)),
      'filename'       => new sfWidgetFormInputText(),
      'position'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'patenobjekt_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Patenobjekt'), 'required' => false)),
      'filename'       => new sfValidatorString(array('max_length' => 255)),
      'position'       => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('patenobjekt_photo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PatenobjektPhoto';
  }

}
