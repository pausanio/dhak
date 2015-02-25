<?php

/**
 * PatenobjektPhoto filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePatenobjektPhotoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'patenobjekt_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Patenobjekt'), 'add_empty' => true)),
      'filename'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'position'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'patenobjekt_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Patenobjekt'), 'column' => 'id')),
      'filename'       => new sfValidatorPass(array('required' => false)),
      'position'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('patenobjekt_photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PatenobjektPhoto';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'patenobjekt_id' => 'ForeignKey',
      'filename'       => 'Text',
      'position'       => 'Number',
    );
  }
}
