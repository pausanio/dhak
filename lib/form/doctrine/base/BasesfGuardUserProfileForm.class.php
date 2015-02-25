<?php

/**
 * sfGuardUserProfile form base class.
 *
 * @method sfGuardUserProfile getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'user_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'title_front'         => new sfWidgetFormInputText(),
      'title_rear'          => new sfWidgetFormInputText(),
      'institution'         => new sfWidgetFormInputText(),
      'person_strasse'      => new sfWidgetFormInputText(),
      'person_plz'          => new sfWidgetFormInputText(),
      'person_ort'          => new sfWidgetFormInputText(),
      'person_tel'          => new sfWidgetFormInputText(),
      'role'                => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormInputText(),
      'person_support'      => new sfWidgetFormInputText(),
      'institution_support' => new sfWidgetFormInputText(),
      'institution_ort'     => new sfWidgetFormInputText(),
      'institution_strasse' => new sfWidgetFormInputText(),
      'institution_plz'     => new sfWidgetFormInputText(),
      'institution_tel'     => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'created_by'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'title_front'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'title_rear'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'institution'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'person_strasse'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'person_plz'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'person_ort'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'person_tel'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'role'                => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorInteger(array('required' => false)),
      'person_support'      => new sfValidatorInteger(array('required' => false)),
      'institution_support' => new sfValidatorInteger(array('required' => false)),
      'institution_ort'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'institution_strasse' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'institution_plz'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'institution_tel'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
      'created_by'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }

}
