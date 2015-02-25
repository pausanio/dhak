<?php

/**
 * netzwerk actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage netzwerk
 * @author     Norman Fiedler
 */
class netzwerkActions extends myActions
{

  public function executeIndex(sfWebRequest $request)
  {
    #$this->ha_projektes = HaProjekte::getProjektData();

    $this->ha_projektes = Doctrine_Core::getTable('HaProjekte')
        ->createQuery()
        ->where('status = ?',1)
        ->orderBy('projekt_title ASC')
        ->execute();

    $this->cms_text = cmsText::getTextByLanguage($request->getParameter('module'));
    $this->getRequest()->setAttribute('content_wrapper_class', 'simpleBg');
    $this->getRequest()->setAttribute('inner_class', 'network');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new haProjekteForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new haProjekteForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('haProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
    $this->form = new haProjekteForm($ha_projekte);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('haProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
    $this->form = new haProjekteForm($ha_projekte);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($ha_projekte = Doctrine_Core::getTable('haProjekte')->find(array($request->getParameter('id'))), sprintf('Object ha_projekte does not exist (%s).', $request->getParameter('id')));
    $ha_projekte->delete();
    $this->redirect('netzwerk/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $ha_projekte = $form->save();
      $this->redirect('netzwerk/edit?id=' . $ha_projekte->getId());
    }
  }

}
