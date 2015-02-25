<?php

/**
 * CmsSlider form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Ivo Bathke
 */
class CmsSliderForm extends BaseCmsSliderForm
{

    public function configure()
    {
        parent::configure();

        unset($this->widgetSchema['created_at'], $this->widgetSchema['updated_at']);
        unset($this->validatorSchema['created_at'], $this->validatorSchema['updated_at']);

        $this->widgetSchema->setLabels(array(
            'url' => 'URL',
            'button_text' => 'Button-Text'
        ));

        $this->widgetSchema['text'] = new sfWidgetFormTextareaTinyMCE(array(
            'width' => sfConfig::get('app_tinymce_options_width'),
            'height' => sfConfig::get('app_tinymce_options_height'),
            'config' => sfConfig::get('app_tinymce_options')));


        $this->setValidator('button_text', new sfValidatorString(
                array(
            'max_length' => 42,
            'required' => true
                ), array(
            'required' => 'Bitte einen Text für die Button-Beschriftung eingeben!',
            'max_length' => 'Die Beschriftung darf maximal 342 Zeichen lang sein.'
                )
        ));

        $this->setDefault('button_text', 'Mehr erfahren');

        $this->widgetSchema['image'] = new sfWidgetFormInputFileEditable(array(
            'label' => 'Slider Bild',
            'delete_label' => 'Bild löschen',
            'file_src' => $this->getObject()->getImageSrc(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'template' => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
        ));


        $this->widgetSchema['layout'] = new sfWidgetFormChoice(array(
            'choices' => array(0 => 'Bild links', 1 => 'Vollbild', 2 => 'Bild rechts'),
            'expanded' => false)
        );

        $this->validatorSchema['image'] = new sfValidatorFile(array(
            'path' => sfConfig::get('app_cms_slider_imagefile_org'),
            'mime_types' => 'web_images',
            'required' => false,
            'validated_file_class' => 'ValidatedOriginalnameFile',
            'mime_types' => array('image/jpeg', 'image/png', 'image/x-png', 'image/gif')
                ), array(
            'mime_types' => 'Der Dateityp wird nicht unterstützt.'
                )
        );
        $this->validatorSchema['image_delete'] = new sfValidatorPass();

        $this->widgetSchema->setHelp('url', 'Bitte vollständige URL angeben (z.B. http://www.dfg.de)');
        $this->widgetSchema->setHelp('image', 'Erlaubte Formate: gif, png, jpg');
    }

}

