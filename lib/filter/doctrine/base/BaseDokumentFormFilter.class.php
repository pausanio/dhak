<?php

/**
 * Dokument filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDokumentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'archiv_id'               => new sfWidgetFormFilterInput(),
      'bestand_sig'             => new sfWidgetFormFilterInput(),
      'signatur'                => new sfWidgetFormFilterInput(),
      'verzeichnungseinheit_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'add_empty' => true)),
      'titel'                   => new sfWidgetFormFilterInput(),
      'beschreibung'            => new sfWidgetFormFilterInput(),
      'filename'                => new sfWidgetFormFilterInput(),
      'datierung'               => new sfWidgetFormFilterInput(),
      'date_day'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_month'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_year'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'position'                => new sfWidgetFormFilterInput(),
      'vorlagentyp_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Vorlagentyp'), 'add_empty' => true)),
      'kommentar'               => new sfWidgetFormFilterInput(),
      'folio'                   => new sfWidgetFormFilterInput(),
      'copyright'               => new sfWidgetFormFilterInput(),
      'einsteller'              => new sfWidgetFormFilterInput(),
      'status'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'usergenerated'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_description'        => new sfWidgetFormFilterInput(),
      'validated'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'archiv_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bestand_sig'             => new sfValidatorPass(array('required' => false)),
      'signatur'                => new sfValidatorPass(array('required' => false)),
      'verzeichnungseinheit_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'column' => 'id')),
      'titel'                   => new sfValidatorPass(array('required' => false)),
      'beschreibung'            => new sfValidatorPass(array('required' => false)),
      'filename'                => new sfValidatorPass(array('required' => false)),
      'datierung'               => new sfValidatorPass(array('required' => false)),
      'date_day'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date_month'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date_year'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vorlagentyp_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Vorlagentyp'), 'column' => 'id')),
      'kommentar'               => new sfValidatorPass(array('required' => false)),
      'folio'                   => new sfValidatorPass(array('required' => false)),
      'copyright'               => new sfValidatorPass(array('required' => false)),
      'einsteller'              => new sfValidatorPass(array('required' => false)),
      'status'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'usergenerated'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_description'        => new sfValidatorPass(array('required' => false)),
      'validated'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('dokument_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Dokument';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'archiv_id'               => 'Number',
      'bestand_sig'             => 'Text',
      'signatur'                => 'Text',
      'verzeichnungseinheit_id' => 'ForeignKey',
      'titel'                   => 'Text',
      'beschreibung'            => 'Text',
      'filename'                => 'Text',
      'datierung'               => 'Text',
      'date_day'                => 'Number',
      'date_month'              => 'Number',
      'date_year'               => 'Number',
      'position'                => 'Number',
      'vorlagentyp_id'          => 'ForeignKey',
      'kommentar'               => 'Text',
      'folio'                   => 'Text',
      'copyright'               => 'Text',
      'einsteller'              => 'Text',
      'status'                  => 'Number',
      'usergenerated'           => 'Number',
      'user_description'        => 'Text',
      'validated'               => 'Number',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
      'created_by'              => 'ForeignKey',
      'updated_by'              => 'ForeignKey',
    );
  }
}
