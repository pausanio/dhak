<?php

/**
 * Archiv form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class BackendArchivForm extends BaseArchivForm
{

    public function configure()
    {
        $this->removeFields();

        $this->widgetSchema->setLabels(array(
            'contactperson' => 'Ansprechpartner',
            'contactperson_filename' => 'Bild des Ansprechpartners',
            'user_description' => 'Ergänzende Informationen'
        ));

        $this->widgetSchema['contactperson_filename'] = new sfWidgetFormInputFileEditable(array(
            'label' => 'Foto',
            'file_src' => sfConfig::get('app_archivcontact_web') . $this->getObject()->getContactpersonFilename(),
            'edit_mode' => !$this->isNew() && $this->getObject()->getContactpersonFilename() == true,
            'delete_label' => 'Bild entfernen',
            'template' => '<img src="%file%"><br>%input%'
        ));

        $this->validatorSchema['contactperson_filename'] = new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('app_archivcontact_filesystem'),
            'validated_file_class' => 'ValidatedOriginalnameFile',
            'mime_types' => 'web_images'
                ), array(
            'invalid' => 'Ungültige Datei.',
            'required' => 'Datei erforderlich.',
            'mime_types' => 'Der Dateityp wird nicht unterstützt.'
        ));

        $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
            'label' => 'Status',
            'choices' => Doctrine::getTable('Archiv')->getStatus(),
            'expanded' => false,
        ));

        # ?? $this->validatorSchema['contactperson_filename'] = new sfValidatorBoolean();

        $this->widgetSchema->setHelp('contactperson_filename', 'Unterstütze Bildformate: jpg, jpeg, gif, png; Achtung Bilder werden nicht automatisch skaliert!');
    }

    protected function removeFields()
    {
        unset(
                $this['created_at'], $this['created_by'], $this['updated_at'], $this['updated_by'], $this['signatur'], $this['name'], $this['beschreibung'], $this['count_ve'], $this['count_docs'], $this['count_userdocs'], $this['lft'], $this['rgt'], $this['level'], $this['type']
        );
    }

}
