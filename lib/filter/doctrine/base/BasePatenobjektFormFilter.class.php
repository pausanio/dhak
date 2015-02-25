<?php

/**
 * Patenobjekt filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePatenobjektFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'titel'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'beschreibung'  => new sfWidgetFormFilterInput(),
      'inhalt'        => new sfWidgetFormFilterInput(),
      'zustand'       => new sfWidgetFormFilterInput(),
      'restaurierung' => new sfWidgetFormFilterInput(),
      'massnahmen'    => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verfuegbar'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tekt_nr'       => new sfWidgetFormFilterInput(),
      'bestand_sig'   => new sfWidgetFormFilterInput(),
      've_signatur'   => new sfWidgetFormFilterInput(),
      'created_by'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'titel'         => new sfValidatorPass(array('required' => false)),
      'beschreibung'  => new sfValidatorPass(array('required' => false)),
      'inhalt'        => new sfValidatorPass(array('required' => false)),
      'zustand'       => new sfValidatorPass(array('required' => false)),
      'restaurierung' => new sfValidatorPass(array('required' => false)),
      'massnahmen'    => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'verfuegbar'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tekt_nr'       => new sfValidatorPass(array('required' => false)),
      'bestand_sig'   => new sfValidatorPass(array('required' => false)),
      've_signatur'   => new sfValidatorPass(array('required' => false)),
      'created_by'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('patenobjekt_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Patenobjekt';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'type'          => 'Number',
      'titel'         => 'Text',
      'beschreibung'  => 'Text',
      'inhalt'        => 'Text',
      'zustand'       => 'Text',
      'restaurierung' => 'Text',
      'massnahmen'    => 'Text',
      'status'        => 'Number',
      'verfuegbar'    => 'Boolean',
      'tekt_nr'       => 'Text',
      'bestand_sig'   => 'Text',
      've_signatur'   => 'Text',
      'created_by'    => 'ForeignKey',
      'updated_by'    => 'ForeignKey',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
