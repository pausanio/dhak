<?php

/**
 * Dokument form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer
 */
class DokumentForm extends BaseDokumentForm
{

  public function configure()
  {

    parent::configure();

    unset(
        $this['created_at'], $this['created_by'], $this['updated_at'], $this['updated_by'], $this['signatur'], $this['bestand_sig'], $this['user_description'], $this['position'], $this['validated']
    );

    $this->widgetSchema->setLabels(array(
      'verzeichnungseinheit_id' => 'Verzeichnungseinheit',
      'titel' => 'Titel',
      'beschreibung' => 'Beschreibung',
      'filename' => 'Bild-Datei',
      'datierung' => 'Datierung (Freitext)',
      'date_day' => 'Tag',
      'date_month' => 'Monat',
      'date_year' => 'Jahr',
      'position' => 'Position',
      'vorlagentyp_id' => 'Vorlagentyp',
      'kommentar' => 'Kommentar zur Vorlage',
      'folio' => 'Folio',
      'copyright' => 'Bildrechte',
      'einsteller' => 'Eingestellt von',
      'status' => 'Status'
    ));

    $this->widgetSchema['archiv_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['verzeichnungseinheit_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['usergenerated'] = new sfWidgetFormInputHidden();

    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
      'choices' => array(
        1 => 'öffentlich',
        0 => 'nur intern im Archiv'
      ),
      'expanded' => true,
    ));

    $this->widgetSchema['vorlagentyp_id'] = new sfWidgetFormDoctrineChoice(array(
      'label' => 'Liegt vor als',
      'model' => 'Vorlagentyp',
      'expanded' => false,
      'add_empty' => 'Bitte auswählen'
    ));

    $days = array_merge(array('' => 'Unbekannt') + range(0, 31));
    unset($days[0]);
    $this->widgetSchema['date_day'] = new sfWidgetFormChoice(array(
      'label' => 'Tag',
      'choices' => $days
    ));
    $months = array_merge(array('' => 'Unbekannt') + range(0, 12));
    unset($months[0]);

    $this->widgetSchema['date_month'] = new sfWidgetFormChoice(array(
      'label' => 'Monat',
      'choices' => $months
    ));

    $this->widgetSchema['date_year']->setAttributes(array(
      'size' => '4',
      'maxlength' => '4'
    ));

    $this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
      'label' => 'Bilddatei',
      'file_src' => $this->getObject()->getThumb(),
      'edit_mode' => !$this->isNew() && $this->getObject()->getFilename() == true,
      'template' => '<img src="%file%"> <a target="_blank" href="' . $this->getObject()->getOrgImage() . '">Originalbild anzeigen</a><br>%input%'
    ));

    $this->validatorSchema['filename'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('app_dokument_user_filesystem'),
      'validated_file_class' => 'ValidatedOriginalnameFile',
      'mime_types' => 'web_images'
        ), array('invalid' => 'Ungültige Datei.',
      'required' => 'Datei erforderlich.',
      'mime_types' => 'Der Dateityp wird nicht unterstützt.')
    );

    $this->validatorSchema['titel'] = new sfValidatorString(array(), array(
      'required' => 'Bitte einen Titel eingeben.'
    ));

    $this->validatorSchema['vorlagentyp_id']->setOption('required', true);
    $this->validatorSchema['vorlagentyp_id']->setMessage('required', 'Bitte füllen Sie dieses Feld aus.');
    $this->validatorSchema['filename']->setMessage('mime_types', 'Das Bildformat wird nicht unterstützt.');
    $this->validatorSchema['archiv_id']->setMessage('required', 'Bitte ordnen Sie eine Archivsystematik zu');

    $this->widgetSchema->setHelp('einsteller', 'Tragen Sie hier den Namen ein in dessen Auftrag Sie diesen Eintrag ausführen. Statt Ihrem wird dann dieser angezeigt.');
    $this->widgetSchema->setHelp('filename', 'Unterstütze Bildformate: jpg, jpeg, gif, png');

    $this->setDefault('usergenerated', 1);
  }

}
