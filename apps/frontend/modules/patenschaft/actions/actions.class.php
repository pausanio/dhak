<?php

/**
 * patenschaft actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage patenschaft
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class patenschaftActions extends myActions
{

    public function preExecute()
    {
        $this->setMetaTitle('Patenschaften');
        $this->setMetaDescription();
    }

    public function executeIndex(sfWebRequest $request)
    {
        $this->type = (int) $request->getParameter('type', 1);
        $this->pager = new sfDoctrinePager('Patenobjekt', (int) $request->getParameter('limit', 9));
        $this->pager->setQuery(Patenobjekt::getObjectsByType((int) $this->type));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->objects = $this->pager->getResults();
        $this->subnavi = $this->getPartial('patenschaft/patenschaft_subnavi', array('active' => 'patenschaft'));
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->object = Doctrine_Core::getTable('Patenobjekt')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->object);
        $this->subnavi = $this->getPartial('patenschaft/patenschaft_subnavi', array('active' => 'patenschaft'));
    }

    public function executeIntro(sfWebRequest $request)
    {
        $this->setMetaTitle('Patenschaft');
        $this->setMetaDescription('Helfen auch Sie bei der Wiederherstellung der Bestände und werden Sie Restaurierungspate!');

        if ($request->getParameter('pagename') == 'patenschaft_liste') {
            $active = 'list';
        } else {
            $active = 'intro';
        }

        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->subnavi = $this->getPartial('patenschaft/patenschaft_subnavi', array('active' => $active));
    }

    public function executeContact(sfWebRequest $request)
    {
        $this->subnavi = $this->getPartial('patenschaft/patenschaft_subnavi', array('active' => 'contact'));
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->form = new PatenContactForm();
        $this->sendform = false;

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('contact'));
            if ($this->form->isValid()) {
                $contact = $this->form->getValues();
                $request_params = $request->getParameter('contact');
                $labels = array();
                foreach ($this->form->getWidgetSchema()->getFields() as $field => $widget) {
                    if ($widget->getLabel()) {
                        $labels[$widget->getLabel()] = $contact[$field];
                        if ($labels[$widget->getLabel()] == 'Array')
                            $labels[$widget->getLabel()] = $request_params[$field][0];
                    }
                }
                $msg = '
*************** Das digitale Historische Archiv Köln ***************

Folgende Anfrage wurde über das Kontaktformular zu Restaurierungspatenschaften gesendet.

';
                foreach ($labels as $key => $value) {
                    $msg .= sprintf("$key:\n $value\n\n");
                }

                $msg .= '
Mit freundlichen Grüßen,
das Team von historischesarchivkoeln.de


****************************************
http://www.historischesarchivkoeln.de
';
                $name = $this->form->getValue('salutation') . ' ' . $this->form->getValue('firstname') . ' ' . $this->form->getValue('lastname');

                $message = Swift_Message::newInstance()
                        ->setFrom(array($this->form->getValue('email') => $name))
                        ->setTo(sfConfig::get('app_mail_patenschaft'))
                        ->setTo('patenschaften-archiv@stadt-koeln.de')
                        ->setSubject('Restaurierungspatenschafts-Anfrage - Das digitale historische Archiv')
                        ->setBody($msg);
                $this->getMailer()->send($message);
                $this->sendform = true;
            }
        }
    }

}

