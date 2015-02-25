<?php

/**
 * Dokument form base class.
 *
 * @method Dokument getObject() Returns the current form's model object
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDokumentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'archiv_id'               => new sfWidgetFormInputText(),
      'bestand_sig'             => new sfWidgetFormInputText(),
      'signatur'                => new sfWidgetFormInputText(),
      'verzeichnungseinheit_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'add_empty' => true)),
      'titel'                   => new sfWidgetFormInputText(),
      'beschreibung'            => new sfWidgetFormTextarea(),
      'filename'                => new sfWidgetFormInputText(),
      'datierung'               => new sfWidgetFormInputText(),
      'date_day'                => new sfWidgetFormInputText(),
      'date_month'              => new sfWidgetFormInputText(),
      'date_year'               => new sfWidgetFormInputText(),
      'position'                => new sfWidgetFormInputText(),
      'vorlagentyp_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Vorlagentyp'), 'add_empty' => true)),
      'kommentar'               => new sfWidgetFormTextarea(),
      'folio'                   => new sfWidgetFormInputText(),
      'copyright'               => new sfWidgetFormTextarea(),
      'einsteller'              => new sfWidgetFormInputText(),
      'status'                  => new sfWidgetFormInputText(),
      'usergenerated'           => new sfWidgetFormInputText(),
      'user_description'        => new sfWidgetFormTextarea(),
      'validated'               => new sfWidgetFormInputText(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
      'created_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'add_empty' => true)),
      'updated_by'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'archiv_id'               => new sfValidatorInteger(array('required' => false)),
      'bestand_sig'             => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'signatur'                => new sfValidatorString(array('max_length' => 63, 'required' => false)),
      'verzeichnungseinheit_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Verzeichnungseinheit'), 'required' => false)),
      'titel'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'beschreibung'            => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'filename'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'datierung'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'date_day'                => new sfValidatorInteger(array('required' => false)),
      'date_month'              => new sfValidatorInteger(array('required' => false)),
      'date_year'               => new sfValidatorInteger(array('required' => false)),
      'position'                => new sfValidatorInteger(array('required' => false)),
      'vorlagentyp_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Vorlagentyp'), 'required' => false)),
      'kommentar'               => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'folio'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'copyright'               => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'einsteller'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'                  => new sfValidatorInteger(array('required' => false)),
      'usergenerated'           => new sfValidatorInteger(array('required' => false)),
      'user_description'        => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'validated'               => new sfValidatorInteger(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
      'created_by'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Creator'), 'required' => false)),
      'updated_by'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updator'), 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Dokument', 'column' => array('position', 'verzeichnungseinheit_id')))
    );

    $this->widgetSchema->setNameFormat('dokument[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Dokument';
  }

}
