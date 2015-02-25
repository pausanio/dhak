<?php

/**
 * HaProjekte filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHaProjekteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'projekt_title'      => new sfWidgetFormFilterInput(),
      'projekt_type'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HaProjekttypen'), 'add_empty' => true)),
      'projekt_einsteller' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'projekt_bestand'    => new sfWidgetFormFilterInput(),
      'projekt_notiz'      => new sfWidgetFormFilterInput(),
      'status'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'updated_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'projekt_title'      => new sfValidatorPass(array('required' => false)),
      'projekt_type'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('HaProjekttypen'), 'column' => 'id')),
      'projekt_einsteller' => new sfValidatorPass(array('required' => false)),
      'projekt_bestand'    => new sfValidatorPass(array('required' => false)),
      'projekt_notiz'      => new sfValidatorPass(array('required' => false)),
      'status'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'updated_by'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ha_projekte_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HaProjekte';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'projekt_title'      => 'Text',
      'projekt_type'       => 'ForeignKey',
      'projekt_einsteller' => 'Text',
      'projekt_bestand'    => 'Text',
      'projekt_notiz'      => 'Text',
      'status'             => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'created_by'         => 'ForeignKey',
      'updated_by'         => 'ForeignKey',
    );
  }
}
