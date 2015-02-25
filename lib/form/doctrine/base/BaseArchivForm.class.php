<?php

/**
 * Archiv form base class.
 *
 * @method Archiv getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseArchivForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'signatur'               => new sfWidgetFormInputText(),
      'name'                   => new sfWidgetFormInputText(),
      'type'                   => new sfWidgetFormInputText(),
      'beschreibung'           => new sfWidgetFormTextarea(),
      'user_description'       => new sfWidgetFormTextarea(),
      'contactperson'          => new sfWidgetFormInputText(),
      'contactperson_filename' => new sfWidgetFormInputText(),
      'count_ve'               => new sfWidgetFormInputText(),
      'count_docs'             => new sfWidgetFormInputText(),
      'count_userdocs'         => new sfWidgetFormInputText(),
      'status'                 => new sfWidgetFormInputText(),
      'lft'                    => new sfWidgetFormInputText(),
      'rgt'                    => new sfWidgetFormInputText(),
      'level'                  => new sfWidgetFormInputText(),
      'created_by'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'signatur'               => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 255)),
      'type'                   => new sfValidatorInteger(array('required' => false)),
      'beschreibung'           => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'user_description'       => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'contactperson'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contactperson_filename' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'count_ve'               => new sfValidatorInteger(array('required' => false)),
      'count_docs'             => new sfValidatorInteger(array('required' => false)),
      'count_userdocs'         => new sfValidatorInteger(array('required' => false)),
      'status'                 => new sfValidatorInteger(array('required' => false)),
      'lft'                    => new sfValidatorInteger(array('required' => false)),
      'rgt'                    => new sfValidatorInteger(array('required' => false)),
      'level'                  => new sfValidatorInteger(array('required' => false)),
      'created_by'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('archiv[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Archiv';
  }

}
