<?php

/**
 * Vorgang filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseVorgangFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'bestand_sig'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      've_signatur'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'laufzeit'     => new sfWidgetFormFilterInput(),
      'titel'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bestellsig'   => new sfWidgetFormFilterInput(),
      'umfang'       => new sfWidgetFormFilterInput(),
      'archivgutart' => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'bestand_sig'  => new sfValidatorPass(array('required' => false)),
      've_signatur'  => new sfValidatorPass(array('required' => false)),
      'laufzeit'     => new sfValidatorPass(array('required' => false)),
      'titel'        => new sfValidatorPass(array('required' => false)),
      'bestellsig'   => new sfValidatorPass(array('required' => false)),
      'umfang'       => new sfValidatorPass(array('required' => false)),
      'archivgutart' => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_by'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('vorgang_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vorgang';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'bestand_sig'  => 'Text',
      've_signatur'  => 'Text',
      'laufzeit'     => 'Text',
      'titel'        => 'Text',
      'bestellsig'   => 'Text',
      'umfang'       => 'Text',
      'archivgutart' => 'Text',
      'status'       => 'Number',
      'created_by'   => 'ForeignKey',
      'updated_by'   => 'ForeignKey',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
