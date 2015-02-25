<?php

/**
 * Verzeichnungseinheit form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class BackendVerzeichnungseinheitForm extends BaseVerzeichnungseinheitForm
{

  public function configure()
  {
    $this->removeFields();

    $this->widgetSchema->setLabels(array(
      'user_description' => 'Ergänzende Informationen'
    ));

    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
      'choices' => array(0 => 'inaktiv', 1 => 'aktiv'),
      'expanded' => true,
      'label' => 'Status'
    ));

    #$this->widgetSchema->setHelp('contactperson_filename', 'Unterstütze Bildformate: jpg, jpeg, gif, png; Achtung Bilder werden nicht automatisch skaliert!');
  }

  protected function removeFields()
  {
    unset(
        $this['archiv_id'], $this['bestand_sig'], $this['signatur'], $this['laufzeit'], $this['beschreibung'], $this['titel'], $this['sperrvermerk'], $this['bestellsig'], $this['archivgutart'], $this['altsig'], $this['umfang'], $this['bem'], $this['usergenerated'], $this['created_at'], $this['created_by'], $this['updated_at'], $this['updated_by']
    );
  }

}
