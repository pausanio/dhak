<?php

/**
 * Bestand form base class.
 *
 * @method Bestand getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBestandForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'archiv_id'          => new sfWidgetFormInputText(),
      'bestand_sig'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'add_empty' => false)),
      'bestandsname'       => new sfWidgetFormTextarea(),
      'laufzeit'           => new sfWidgetFormInputText(),
      'bestand_inhalt'     => new sfWidgetFormTextarea(),
      'umfang'             => new sfWidgetFormTextarea(),
      'bem'                => new sfWidgetFormTextarea(),
      'bestandsgeschichte' => new sfWidgetFormTextarea(),
      'sperrvermerk'       => new sfWidgetFormInputText(),
      'abg_stelle'         => new sfWidgetFormInputText(),
      'rechtsstatus'       => new sfWidgetFormInputText(),
      'status'             => new sfWidgetFormInputText(),
      'created_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'archiv_id'          => new sfValidatorInteger(array('required' => false)),
      'bestand_sig'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'))),
      'bestandsname'       => new sfValidatorString(array('max_length' => 512)),
      'laufzeit'           => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'bestand_inhalt'     => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'umfang'             => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'bem'                => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'bestandsgeschichte' => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'sperrvermerk'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'abg_stelle'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'rechtsstatus'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'             => new sfValidatorInteger(array('required' => false)),
      'created_by'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('bestand[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bestand';
  }

}
