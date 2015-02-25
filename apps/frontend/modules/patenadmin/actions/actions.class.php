<?php

/**
 * patenadmin actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage patenadmin
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class patenadminActions extends myActions
{

  public function preExecute()
  {
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->setMetaTitle('Patenschaften');
    $this->setMetaDescription();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $editor = $this->getUser()->getGuardUser()->hasPermission('patenschaften');
    $this->pager = new sfDoctrinePager('HaPatenobjekt', 30);
    $this->pager->setQuery(HaPatenobjekt::getDocumentsForEdit($this->getUser()->getGuardUser()->getId(), $editor));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    $this->ha_patenobjekts = $this->pager->getResults();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ha_patenobjekt = Doctrine_Core::getTable('HaPatenobjekt')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ha_patenobjekt);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->getResponse()->addJavascript('tiny_mce/tiny_mce.js');
    $this->form = new HaPatenobjektForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->getResponse()->addJavascript('tiny_mce/tiny_mce.js');
    $this->form = new HaPatenobjektForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ha_patenobjekt = Doctrine_Core::getTable('HaPatenobjekt')->find(array($request->getParameter('id'))), sprintf('Object ha_patenobjekt does not exist (%s).', $request->getParameter('id')));
    $this->getResponse()->addJavascript('tiny_mce/tiny_mce.js');
    $this->form = new HaPatenobjektForm($ha_patenobjekt);
    $this->images = Doctrine_Core::getTable('HaPatenobjektpic')->findByObjektId(array($request->getParameter('id')));
    $this->pic_form = new HaPatenobjektpicForm();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ha_patenobjekt = Doctrine_Core::getTable('HaPatenobjekt')->find(array($request->getParameter('id'))), sprintf('Object ha_patenobjekt does not exist (%s).', $request->getParameter('id')));
    $this->form = new HaPatenobjektForm($ha_patenobjekt);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($ha_patenobjekt = Doctrine_Core::getTable('HaPatenobjekt')->find(array($request->getParameter('id'))), sprintf('Object ha_patenobjekt does not exist (%s).', $request->getParameter('id')));
    $ha_patenobjekt->delete();
    $this->redirect('patenadmin/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $ha_patenobjekt = $form->save();
      $this->redirect('patenadmin/edit?id=' . $ha_patenobjekt->getId());
    }
  }

}
