<?php

/**
 * Bestand form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class BackendBestandForm extends BaseBestandForm
{

  public function configure()
  {
    $this->removeFields();

    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
      'choices' => array(0 => 'inaktiv', 1 => 'aktiv'),
      'expanded' => true,
      'label' => 'Status'
    ));
  }

  protected function removeFields()
  {
    unset(
        $this['archiv_id'], $this['bestand_sig'], $this['bestandsname'], $this['laufzeit'], $this['bestand_inhalt'], $this['umfang'], $this['bem'], $this['bestandsgeschichte'], $this['sperrvermerk'], $this['abg_stelle'], $this['rechtsstatus'], $this['created_at'], $this['created_by'], $this['updated_at'], $this['updated_by']
    );
  }

}
