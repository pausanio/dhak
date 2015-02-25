<?php

/**
 * sfChoiceChainExample actions.
 *
 * @package    test
 * @subpackage sfChoiceChainExample
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfChoiceChainExampleActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->test_items = Doctrine_Core::getTable('TestItem')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PluginTestItemForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PluginTestItemForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($test_item = Doctrine_Core::getTable('TestItem')->find(array($request->getParameter('id'))), sprintf('Object test_item does not exist (%s).', $request->getParameter('id')));
    $this->form = new PluginTestItemForm($test_item);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($test_item = Doctrine_Core::getTable('TestItem')->find(array($request->getParameter('id'))), sprintf('Object test_item does not exist (%s).', $request->getParameter('id')));
    $this->form = new PluginTestItemForm($test_item);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($test_item = Doctrine_Core::getTable('TestItem')->find(array($request->getParameter('id'))), sprintf('Object test_item does not exist (%s).', $request->getParameter('id')));
    $test_item->delete();

    $this->redirect('sfChoiceChainExample/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $test_item = $form->save();

      $this->redirect('sfChoiceChainExample/edit?id='.$test_item->getId());
    }
  }
}
