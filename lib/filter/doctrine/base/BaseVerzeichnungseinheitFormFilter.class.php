<?php

/**
 * Verzeichnungseinheit filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseVerzeichnungseinheitFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'archiv_id'        => new sfWidgetFormFilterInput(),
      'bestand_sig'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bestand'), 'add_empty' => true)),
      'signatur'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'laufzeit'         => new sfWidgetFormFilterInput(),
      'beschreibung'     => new sfWidgetFormFilterInput(),
      'titel'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sperrvermerk'     => new sfWidgetFormFilterInput(),
      'bestellsig'       => new sfWidgetFormFilterInput(),
      'archivgutart'     => new sfWidgetFormFilterInput(),
      'altsig'           => new sfWidgetFormFilterInput(),
      'umfang'           => new sfWidgetFormFilterInput(),
      'enthaelt'         => new sfWidgetFormFilterInput(),
      'bem'              => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_description' => new sfWidgetFormFilterInput(),
      'created_by'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'archiv_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bestand_sig'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Bestand'), 'column' => 'id')),
      'signatur'         => new sfValidatorPass(array('required' => false)),
      'laufzeit'         => new sfValidatorPass(array('required' => false)),
      'beschreibung'     => new sfValidatorPass(array('required' => false)),
      'titel'            => new sfValidatorPass(array('required' => false)),
      'sperrvermerk'     => new sfValidatorPass(array('required' => false)),
      'bestellsig'       => new sfValidatorPass(array('required' => false)),
      'archivgutart'     => new sfValidatorPass(array('required' => false)),
      'altsig'           => new sfValidatorPass(array('required' => false)),
      'umfang'           => new sfValidatorPass(array('required' => false)),
      'enthaelt'         => new sfValidatorPass(array('required' => false)),
      'bem'              => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_description' => new sfValidatorPass(array('required' => false)),
      'created_by'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('verzeichnungseinheit_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Verzeichnungseinheit';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'archiv_id'        => 'Number',
      'bestand_sig'      => 'ForeignKey',
      'signatur'         => 'Text',
      'laufzeit'         => 'Text',
      'beschreibung'     => 'Text',
      'titel'            => 'Text',
      'sperrvermerk'     => 'Text',
      'bestellsig'       => 'Text',
      'archivgutart'     => 'Text',
      'altsig'           => 'Text',
      'umfang'           => 'Text',
      'enthaelt'         => 'Text',
      'bem'              => 'Text',
      'status'           => 'Number',
      'user_description' => 'Text',
      'created_by'       => 'ForeignKey',
      'updated_by'       => 'ForeignKey',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
