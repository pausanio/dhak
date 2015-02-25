<?php

/**
 * projekte actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage projekte
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class projekteActions extends myActions
{

    /**
     * The code inserted here is executed at the beginning of each action call.
     */
    public function preExecute()
    {
        $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
        $this->getRequest()->setAttribute('inner_class', 'netw');
        $this->setMetaTitle('Projekte');
        $this->setMetaDescription('Projekte verwalten');
    }

    public function executeIndex(sfWebRequest $request)
    {
        $this->ha_projektes = Doctrine_Core::getTable('HaProjekte')
                ->createQuery()
                ->where('created_by = ?', $this->getUser()->getGuardUser()->getId())
                ->orderBy('created_at desc')
                ->execute();
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->ha_projekte = Doctrine_Core::getTable('HaProjekte')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->ha_projekte);
    }

    /*
      public function executeNew(sfWebRequest $request)
      {
      $this->form = new HaProjekteForm();
      }
     */

    /*
      public function executeCreate(sfWebRequest $request)
      {
      $this->forward404Unless($request->isMethod(sfRequest::POST));
      $this->form = new HaProjekteForm();
      $this->processForm($request, $this->form);
      $this->setTemplate('new');
      }
     */

    public function executeEdit(sfWebRequest $request)
    {
        $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('HaProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
        $this->form = new HaProjekteForm($ha_projekte);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('HaProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
        $this->form = new HaProjekteForm($ha_projekte);
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request)
    {
        $request->checkCSRFProtection();
        $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('HaProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
        $ha_projekte->delete();
        $this->redirect('projekte/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ha_projekte = $form->save();
            $this->redirect('projekte/index');
        }
    }

}
