<?php

/**
 * HaProjekte form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Norman Fiedler
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class HaProjekteForm extends BaseHaProjekteForm
{

    public function configure()
    {

        unset(
                $this['created_at'], $this['updated_at'], $this['created_by'], $this['updated_by']
        );

        $this->widgetSchema->setlabels(array(
            'projekt_title' => 'Titel Ihres Forschungsprojektes*',
            'projekt_einsteller' => 'Ihr Name*',
            'projekt_bestand' => 'Welche Bestände aus dem Archiv nutzen Sie für Ihre Forschung?',
            'projekt_notiz' => 'Notiz',
            'projekt_type' => 'Projekttyp'
        ));

        $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
            'choices' => Doctrine_Core::getTable('HaProjekte')->getStatus(),
            'expanded' => false,
            'label' => 'Status des Projektes'
        ));

        $this->validatorSchema['projekt_title'] = new sfValidatorString(
                array(
            'required' => true,
            'min_length' => 4), array(
            'required' => 'Bitte geben Sie einen Titel an.',
            'min_length' => 'Der Titel muss mindestens 4 Zeichen lang sein.'
        ));

        $this->validatorSchema['projekt_einsteller'] = new sfValidatorString(
                array(
            'required' => true,
            'min_length' => 4), array(
            'required' => 'Bitte geben Sie einen Namen an.',
            'min_length' => 'Der Name muss mindestens 4 Zeichen lang sein.'
        ));

        $this->widgetSchema['projekt_title']->setAttribute("class", "span8");
        $this->widgetSchema['status']->setAttribute("class", "span8");
        $this->widgetSchema['projekt_einsteller']->setAttribute("class", "span8");
        $this->widgetSchema['projekt_bestand']->setAttribute("class", "span8");
        $this->widgetSchema['projekt_notiz']->setAttribute("class", "span8");
        $this->widgetSchema['projekt_type']->setAttribute("class", "span8");
    }

}
