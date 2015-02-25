<?php

/**
 * Patenobjekt form base class.
 *
 * @method Patenobjekt getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePatenobjektForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'type'          => new sfWidgetFormInputText(),
      'titel'         => new sfWidgetFormInputText(),
      'beschreibung'  => new sfWidgetFormTextarea(),
      'inhalt'        => new sfWidgetFormTextarea(),
      'zustand'       => new sfWidgetFormTextarea(),
      'restaurierung' => new sfWidgetFormTextarea(),
      'massnahmen'    => new sfWidgetFormTextarea(),
      'status'        => new sfWidgetFormInputText(),
      'verfuegbar'    => new sfWidgetFormInputCheckbox(),
      'tekt_nr'       => new sfWidgetFormInputText(),
      'bestand_sig'   => new sfWidgetFormInputText(),
      've_signatur'   => new sfWidgetFormInputText(),
      'created_by'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'          => new sfValidatorInteger(),
      'titel'         => new sfValidatorString(array('max_length' => 255)),
      'beschreibung'  => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'inhalt'        => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'zustand'       => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'restaurierung' => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'massnahmen'    => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'verfuegbar'    => new sfValidatorBoolean(array('required' => false)),
      'tekt_nr'       => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'bestand_sig'   => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      've_signatur'   => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'created_by'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('patenobjekt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Patenobjekt';
  }

}
