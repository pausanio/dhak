<?php

/**
 * Newsletter form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class newsletterForm extends BaseForm
{

    public function configure()
    {

        $this->disableLocalCSRFProtection();

        $this->widgetSchema->setNameFormat('newsletter[%s]');
        $this->widgetSchema->setIdFormat('newsletter_%s');

        $this->setWidget('email', new sfWidgetFormInputText(array(), array()));
        $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => true));
        $this->validatorSchema['email']->setMessage('invalid', 'Bitte geben sie eine gültige E-Mail-Adresse ein.');
        $this->validatorSchema['email']->setMessage('required', 'Bitte geben sie eine E-Mail-Adresse ein.');
        $this->widgetSchema['email']->setLabel('E-Mail-Adresse');
    }

}
