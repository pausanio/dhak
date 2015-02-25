<?php

/**
 * HaProjekte form base class.
 *
 * @method HaProjekte getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseHaProjekteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'projekt_title'      => new sfWidgetFormTextarea(),
      'projekt_type'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HaProjekttypen'), 'add_empty' => false)),
      'projekt_einsteller' => new sfWidgetFormInputText(),
      'projekt_bestand'    => new sfWidgetFormTextarea(),
      'projekt_notiz'      => new sfWidgetFormTextarea(),
      'status'             => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'created_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'updated_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'projekt_title'      => new sfValidatorString(array('required' => false)),
      'projekt_type'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('HaProjekttypen'))),
      'projekt_einsteller' => new sfValidatorString(array('max_length' => 255)),
      'projekt_bestand'    => new sfValidatorString(array('required' => false)),
      'projekt_notiz'      => new sfValidatorString(array('required' => false)),
      'status'             => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'created_by'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'updated_by'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ha_projekte[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HaProjekte';
  }

}
