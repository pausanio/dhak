<?php

/**
 * faq actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage faq
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class faqActions extends myActions
{

    /**
     * FAQ as CMS Content
     *
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->setMetaTitle('FAQ');
        $this->setMetaDescription('Fragen und Antworten zum Archiv');

        $this->ha_faqs = Doctrine_Core::getTable('haFaq')->createQuery()->execute();
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('module'));

        // Define the contact form
        $this->sendform = false;
        $this->form = new sfForm();
        $this->form->setWidgets(array(
            'name' => new sfWidgetFormInputText(array('label' => 'Ihr Name')),
            'email' => new sfWidgetFormInput(array('label' => 'Ihre Email-Adresse')),
            'comment' => new sfWidgetFormTextarea(array('label' => 'Ihre Frage'))
        ));
        $this->form->setValidators(array(
            'name' => new sfValidatorString(array('min_length' => 3), array(
                'required' => 'Bitte geben Sie Ihren Namen ein!',
                'min_length' => 'Ihr Name muss min. 3 Zeichen lang sein.'
                    )),
            'email' => new sfValidatorEmail(array(), array(
                'required' => 'Bitte geben Sie eine Email-Adresse an!',
                'invalid' => 'Bitte überprüfen Sie Ihre Email-Adresse!'
                    )),
            'comment' => new sfValidatorString(array('min_length' => 4), array(
                'required' => 'Bitte geben Sie Ihre Frage ein!',
                'min_length' => 'Ihre Frage muss min. 4 Zeichen lang sein.'
                    ))
        ));
        $this->form->getWidgetSchema()->setNameFormat('contact[%s]');

        // Handle the form
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('contact'));
            if ($this->form->isValid()) {
                $contact = $this->form->getValues();
                $message = Swift_Message::newInstance()
                        ->setFrom(array($this->form->getValue('email') => $this->form->getValue('name')))
                        ->setTo(sfConfig::get('app_mail_contact'))
                        ->setSubject('FAQ-Anfrage - Das digitale historische Archiv')
                        ->setBody($this->form->getValue('comment'));
                $this->getMailer()->send($message);
                $this->sendform = true;
            }
        }
    }

    /**
     * FAQ as footer content
     */
    public function executeFooter(sfWebRequest $request)
    {
        $this->setMetaTitle('FAQ');
        $this->setMetaDescription('Fragen und Antworten zum Archiv');
        $this->getRequest()->setAttribute('layout', 'footer');

        $this->ha_faqs = Doctrine_Core::getTable('haFaq')->createQuery()->execute();
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('module'));

        // Define the contact form
        $this->sendform = false;
        $this->form = new sfForm();
        $this->form->setWidgets(array(
            'name' => new sfWidgetFormInputText(array('label' => 'Ihr Name')),
            'email' => new sfWidgetFormInput(array('label' => 'Ihre Email-Adresse')),
            'comment' => new sfWidgetFormTextarea(array('label' => 'Ihre Frage'))
        ));
        $this->form->setValidators(array(
            'name' => new sfValidatorString(array('min_length' => 3), array(
                'required' => 'Bitte geben Sie Ihren Namen ein!',
                'min_length' => 'Ihr Name muss min. 3 Zeichen lang sein.'
                    )),
            'email' => new sfValidatorEmail(array(), array(
                'required' => 'Bitte geben Sie eine Email-Adresse an!',
                'invalid' => 'Bitte überprüfen Sie Ihre Email-Adresse!'
                    )),
            'comment' => new sfValidatorString(array('min_length' => 4), array(
                'required' => 'Bitte geben Sie Ihre Frage ein!',
                'min_length' => 'Ihre Frage muss min. 4 Zeichen lang sein.'
                    ))
        ));
        $this->form->getWidgetSchema()->setNameFormat('contact[%s]');

        // Handle the form
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('contact'));
            if ($this->form->isValid()) {
                $contact = $this->form->getValues();
                $message = Swift_Message::newInstance()
                        ->setFrom(array($this->form->getValue('email') => $this->form->getValue('name')))
                        ->setTo(sfConfig::get('app_mail_contact'))
                        ->setSubject('FAQ-Anfrage - Das digitale historische Archiv')
                        ->setBody($this->form->getValue('comment'));
                $this->getMailer()->send($message);
                $this->sendform = true;
            }
        }
    }

}
