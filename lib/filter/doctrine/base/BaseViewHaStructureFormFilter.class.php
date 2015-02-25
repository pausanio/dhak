<?php

/**
 * ViewHaStructure filter form base class.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Norman Fiedler
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseViewHaStructureFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tekt_titel'   => new sfWidgetFormFilterInput(),
      'bestandsname' => new sfWidgetFormFilterInput(),
      'v_titel'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tekt_titel'   => new sfValidatorPass(array('required' => false)),
      'bestandsname' => new sfValidatorPass(array('required' => false)),
      'v_titel'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('view_ha_structure_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ViewHaStructure';
  }

  public function getFields()
  {
    return array(
      'tekt_nr'      => 'Text',
      'tekt_titel'   => 'Text',
      'bestand_sig'  => 'Text',
      'bestandsname' => 'Text',
      'signatur'     => 'Text',
      'v_titel'      => 'Text',
    );
  }
}
