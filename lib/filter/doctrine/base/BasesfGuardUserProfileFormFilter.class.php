<?php

/**
 * sfGuardUserProfile filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'title_front'         => new sfWidgetFormFilterInput(),
      'title_rear'          => new sfWidgetFormFilterInput(),
      'institution'         => new sfWidgetFormFilterInput(),
      'person_strasse'      => new sfWidgetFormFilterInput(),
      'person_plz'          => new sfWidgetFormFilterInput(),
      'person_ort'          => new sfWidgetFormFilterInput(),
      'person_tel'          => new sfWidgetFormFilterInput(),
      'role'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'person_support'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'institution_support' => new sfWidgetFormFilterInput(),
      'institution_ort'     => new sfWidgetFormFilterInput(),
      'institution_strasse' => new sfWidgetFormFilterInput(),
      'institution_plz'     => new sfWidgetFormFilterInput(),
      'institution_tel'     => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'user_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'title_front'         => new sfValidatorPass(array('required' => false)),
      'title_rear'          => new sfValidatorPass(array('required' => false)),
      'institution'         => new sfValidatorPass(array('required' => false)),
      'person_strasse'      => new sfValidatorPass(array('required' => false)),
      'person_plz'          => new sfValidatorPass(array('required' => false)),
      'person_ort'          => new sfValidatorPass(array('required' => false)),
      'person_tel'          => new sfValidatorPass(array('required' => false)),
      'role'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'person_support'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'institution_support' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'institution_ort'     => new sfValidatorPass(array('required' => false)),
      'institution_strasse' => new sfValidatorPass(array('required' => false)),
      'institution_plz'     => new sfValidatorPass(array('required' => false)),
      'institution_tel'     => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'user_id'             => 'ForeignKey',
      'title_front'         => 'Text',
      'title_rear'          => 'Text',
      'institution'         => 'Text',
      'person_strasse'      => 'Text',
      'person_plz'          => 'Text',
      'person_ort'          => 'Text',
      'person_tel'          => 'Text',
      'role'                => 'Number',
      'status'              => 'Number',
      'person_support'      => 'Number',
      'institution_support' => 'Number',
      'institution_ort'     => 'Text',
      'institution_strasse' => 'Text',
      'institution_plz'     => 'Text',
      'institution_tel'     => 'Text',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'created_by'          => 'ForeignKey',
      'updated_by'          => 'ForeignKey',
    );
  }
}
