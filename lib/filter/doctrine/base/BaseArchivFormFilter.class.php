<?php

/**
 * Archiv filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseArchivFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'signatur'               => new sfWidgetFormFilterInput(),
      'name'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                   => new sfWidgetFormFilterInput(),
      'beschreibung'           => new sfWidgetFormFilterInput(),
      'user_description'       => new sfWidgetFormFilterInput(),
      'contactperson'          => new sfWidgetFormFilterInput(),
      'contactperson_filename' => new sfWidgetFormFilterInput(),
      'count_ve'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'count_docs'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'count_userdocs'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lft'                    => new sfWidgetFormFilterInput(),
      'rgt'                    => new sfWidgetFormFilterInput(),
      'level'                  => new sfWidgetFormFilterInput(),
      'created_by'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'signatur'               => new sfValidatorPass(array('required' => false)),
      'name'                   => new sfValidatorPass(array('required' => false)),
      'type'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'beschreibung'           => new sfValidatorPass(array('required' => false)),
      'user_description'       => new sfValidatorPass(array('required' => false)),
      'contactperson'          => new sfValidatorPass(array('required' => false)),
      'contactperson_filename' => new sfValidatorPass(array('required' => false)),
      'count_ve'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'count_docs'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'count_userdocs'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_by'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Creator'), 'column' => 'id')),
      'updated_by'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updator'), 'column' => 'id')),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('archiv_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Archiv';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'signatur'               => 'Text',
      'name'                   => 'Text',
      'type'                   => 'Number',
      'beschreibung'           => 'Text',
      'user_description'       => 'Text',
      'contactperson'          => 'Text',
      'contactperson_filename' => 'Text',
      'count_ve'               => 'Number',
      'count_docs'             => 'Number',
      'count_userdocs'         => 'Number',
      'status'                 => 'Number',
      'lft'                    => 'Number',
      'rgt'                    => 'Number',
      'level'                  => 'Number',
      'created_by'             => 'ForeignKey',
      'updated_by'             => 'ForeignKey',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
