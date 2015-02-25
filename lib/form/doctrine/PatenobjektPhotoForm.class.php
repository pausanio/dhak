<?php

/**
 * PatenobjektPhoto form.
 *
 * @see http://di-side.com/di-side/symfony-embedrelation-one-to-many-doctrine-relations/
 * @see https://symfonyguide.wordpress.com/tag/embed-form/
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektPhotoForm extends BasePatenobjektPhotoForm
{

  /**
   * Configures the form
   */
  public function configure()
  {

    $this->useFields(array('filename', 'position'));

    $this->setValidator('filename', new sfValidatorFile(array(
      'mime_types' => 'web_images',
      'path' => sfConfig::get('app_photos_patenobjekt_org'),
      'required' => false,
      'validated_file_class' => 'customValidatedFile'
    )));

    $this->setWidget('filename', new sfWidgetFormInputFileEditable(array(
      'label' => 'Bild',
      'file_src' => '/images/patenobjekt/thumb/' . $this->getObject()->filename,
      'edit_mode' => !$this->isNew(),
      'is_image' => true,
      'with_delete' => false,
      'template' => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
    )));

    $this->validatorSchema['position']->setOption('required', false);

    // delete checkbox
    if ($this->object->exists()) {

      $this->setWidget('filename', new sfWidgetFormInputFileEditable(array(
        'label' => 'Bild',
        'file_src' => '/images/patenobjekt/thumb/' . $this->getObject()->filename,
        'edit_mode' => !$this->isNew(),
        'is_image' => true,
        'with_delete' => false,
        'template' => '<div>%file%</div>',
      )));

      $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
      $this->validatorSchema['delete'] = new sfValidatorPass();
    }
  }

}
