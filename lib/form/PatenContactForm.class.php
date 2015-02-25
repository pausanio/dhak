<?php

/**
 * Patenschaft contact form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class PatenContactForm extends BaseForm
{

    public function configure()
    {

        $this->disableLocalCSRFProtection();

        $this->widgetSchema->setNameFormat('contact[%s]');

        $this->setWidget('salutation', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['salutation'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['salutation']->setDefault(__('Anrede'));
        $this->widgetSchema['salutation']->setLabel(__('Anrede'));

        $this->setWidget('firstname', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['firstname'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['firstname']->setDefault(__('Vorname'));
        $this->widgetSchema['firstname']->setLabel(__('Vorname'));

        $this->setWidget('lastname', new sfWidgetFormInputText(array(), array()));
        $this->widgetSchema['lastname']->setDefault(__('Nachname'));
        $this->validatorSchema['lastname'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['lastname']->setLabel(__('Nachname'));

        $this->setWidget('institution', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['institution'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['institution']->setLabel(__('Firma / Institution'));

        $this->setWidget('email', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => false));
        $this->validatorSchema['email']->setMessage('invalid', 'Bitte überprüfen Sie Ihre Eingabe');
        $this->widgetSchema['email']->setLabel(__('Emailadresse'));

        $this->setWidget('street', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['street'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['street']->setDefault(__('Straße'));
        $this->widgetSchema['street']->setLabel(__('Straße Nr.'));

        $this->setWidget('zip', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['zip'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['zip']->setDefault(__('PLZ'));
        $this->widgetSchema['zip']->setLabel(__('PLZ'));

        $this->setWidget('city', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['city'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['city']->setDefault(__('Ort'));
        $this->widgetSchema['city']->setLabel(__('Ort'));

        $this->setWidget('country', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['country'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['country']->setDefault(__('Land'));
        $this->widgetSchema['country']->setLabel(__('Land'));

        $this->setWidget('subject', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['subject'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['subject']->setLabel(__('Betreff'));

        $this->setWidget('objects', new sfWidgetFormTextArea(array(), array()));
        $this->validatorSchema['objects'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['objects']->setLabel(__('Besonders interessiert mich'));

        $this->widgetSchema['surplus'] = new sfWidgetFormChoice(array(
            'choices' => array('Ja' => __('Ich bin damit einverstanden, dass der so eventuell entstandene Überschuss anderen Restaurierungsprojekten zufließt.')),
            'expanded' => true,
            'multiple' => true));
        $this->validatorSchema['surplus'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['surplus']->setDefault('Ja');
        $this->widgetSchema['surplus']->setLabel(__('Wir sind bemüht, bei der konkreten Auftragsvergabe die Kosten zu senken.'));

        $this->widgetSchema['receipt'] = new sfWidgetFormChoice(array(
            'choices' => array('Spendenquittung erbeten' => 'Ja'),
            'expanded' => true,
            'multiple' => true));
        $this->validatorSchema['receipt'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['receipt']->setLabel('Spendenquittung erbeten');

        $this->setWidget('address', new sfWidgetFormTextArea(array(), array()));
        $this->validatorSchema['address'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['address']->setLabel(__('Anschrift'));

        $this->widgetSchema['label'] = new sfWidgetFormChoice(array(
            'choices' => array('Ja' => 'Ja', 'Nein' => 'Nein'),
            'expanded' => true,));
        $this->validatorSchema['label'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['label']->setDefault('Ja');
        $this->widgetSchema['label']->setLabel(__('Als Einzelpate für Objekte ab 1.500 Euro bin ich mit der Nennung meines Namens auf einem Etikett auf der Schutzverpackung meines „Patenkindes“ einverstanden.'));

        $this->setWidget('labelname', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['labelname'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['labelname']->setLabel(__('Textwunsch'));

        $this->widgetSchema['public'] = new sfWidgetFormChoice(array(
            'choices' => array('Ja, nur Name' => 'Ja, nur Name', 'Ja, Name und Signatur' => 'Ja, Name und Signatur', 'Nein' => 'Nein'),
            'expanded' => true,));
        $this->validatorSchema['public'] = new sfValidatorString(array('required' => true));
        $this->widgetSchema['public']->setDefault('Ja, Name und Signatur');
        $this->widgetSchema['public']->setLabel(__('Mit der Veröffentlichung auf der <a target="_blank" href="/de/patenschaft/patenliste">Patenliste</a> auf der Homepage des Digitalen historischen Archivs Köln bin ich einverstanden'));

        $this->setWidget('comment', new sfWidgetFormTextArea(array(), array()));
        $this->validatorSchema['comment'] = new sfValidatorString(array('required' => false));
        $this->widgetSchema['comment']->setLabel(__('Ergänzende Mitteilungen'));

        foreach ($this->validatorSchema->getFields() as $field => $validator) {
            if ($validator->getOption('required') === true) {
                $validator->setMessage('required', 'Bitte prüfen!');
            }
        }

        // set in ProjectConfiguration.class.php
        #$this->widgetSchema->addFormFormatter('bootstrap', new sfWidgetFormSchemaFormatterBootstrap($this->widgetSchema));
        #$this->widgetSchema->setFormFormatterName('bootstrap');
    }

}
