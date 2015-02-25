<?php

/**
 * Vorgang form base class.
 *
 * @method Vorgang getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVorgangForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'bestand_sig'  => new sfWidgetFormInputText(),
      've_signatur'  => new sfWidgetFormInputText(),
      'laufzeit'     => new sfWidgetFormInputText(),
      'titel'        => new sfWidgetFormTextarea(),
      'bestellsig'   => new sfWidgetFormInputText(),
      'umfang'       => new sfWidgetFormInputText(),
      'archivgutart' => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'created_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'bestand_sig'  => new sfValidatorString(array('max_length' => 63)),
      've_signatur'  => new sfValidatorString(array('max_length' => 63)),
      'laufzeit'     => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'titel'        => new sfValidatorString(array('max_length' => 4000)),
      'bestellsig'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'umfang'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'archivgutart' => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
      'created_by'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('vorgang[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vorgang';
  }

}
