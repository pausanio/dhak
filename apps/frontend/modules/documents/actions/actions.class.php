<?php

/**
 * documents actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage documents
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class documentsActions extends myActions
{

  /**
   * The code inserted here is executed at the beginning of each action call.
   */
  public function preExecute()
  {
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    #$this->getRequest()->setAttribute('inner_class', 'netw');
    $this->setMetaTitle('Archivalen');
    $this->setMetaDescription('Archivalen verwalten');
  }

  public function executeIndex(sfWebRequest $request)
  {
    $editor = $this->getUser()->getGuardUser()->hasGroup('redaktion');
    $this->pager = new sfDoctrinePager('HaDocuments', 30);
    $this->pager->setQuery(HaDocuments::getDocumentsForEdit($this->getUser()->getGuardUser()->getId(), $editor));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    $this->ha_documentss = $this->pager->getResults();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ha_documents = Doctrine_Core::getTable('HaDocuments')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ha_documents);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new HaDocumentsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new HaDocumentsForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $editor = $this->getUser()->getGuardUser()->hasGroup('redaktion');
    $this->forward404Unless($ha_documents = Doctrine_Core::getTable('HaDocuments')->find(array($request->getParameter('id'))), sprintf('Object ha_documents does not exist (%s).', $request->getParameter('id')));
    if (!$editor) {
      $this->forward404Unless($ha_documents->getCreatedBy() == $this->getUser()->getGuardUser()->getId());
    }
    $this->form = new HaDocumentsForm($ha_documents);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ha_documents = Doctrine_Core::getTable('HaDocuments')->find(array($request->getParameter('id'))), sprintf('Object ha_documents does not exist (%s).', $request->getParameter('id')));
    $this->form = new HaDocumentsForm($ha_documents);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($ha_documents = Doctrine_Core::getTable('HaDocuments')->find(array($request->getParameter('id'))), sprintf('Object ha_documents does not exist (%s).', $request->getParameter('id')));
    $ha_documents->delete();
    $this->redirect('documents/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $ha_documents = $form->save();
      $this->redirect('documents/index');
    }
  }

}
