<?php

/**
 * dokument actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage dokument
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class dokumentActions extends myActions
{

    public function preExecute()
    {
        $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
        $this->getRequest()->setAttribute('inner_class', 'netw');
        $this->setMetaTitle('Archivalen');
        $this->setMetaDescription('Archivalen verwalten');
    }

    public function executeIndex(sfWebRequest $request)
    {
        $this->dokuments = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('created_by = ?', $this->getUser()->getGuardUser()->getId())
                ->orderBy('id')
                ->execute();
    }

    /*
      public function executeNew(sfWebRequest $request)
      {
      $this->form = new DokumentForm();
      $this->setArchiv($request);
      $this->selected_ve = false;
      $this->verzeichnungseinheiten = false;
      }

      public function executeCreate(sfWebRequest $request)
      {
      $this->forward404Unless($request->isMethod(sfRequest::POST));
      $this->form = new DokumentForm();
      $this->processForm($request, $this->form);
      $this->setTemplate('new');
      $this->setArchiv($request);
      }

      public function executeEdit(sfWebRequest $request)
      {
      $this->forward404Unless($dokument = Doctrine_Core::getTable('Dokument')
      ->find(array($request->getParameter('id'))), sprintf('Object dokument does not exist (%s).', $request->getParameter('id')));

      if ($dokument->getCreatedBy() != $this->getUser()->getGuardUser()->getId()) {
      $this->forward404();
      }

      $this->form = new DokumentForm($dokument);
      $this->archiv_id = $dokument->getArchivId();
      $this->forward404Unless($node = Doctrine_Core::getTable('Archiv')->find($dokument->getArchivId()));
      $this->archiv_title = $node->getName();
      $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')->findByArchivId($dokument->getArchivId());
      $this->selected_ve = $dokument->getVerzeichnungseinheitId();
      }

      public function executeUpdate(sfWebRequest $request)
      {
      $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
      $this->forward404Unless($dokument = Doctrine_Core::getTable('Dokument')
      ->find(array($request->getParameter('id'))), sprintf('Object dokument does not exist (%s).', $request->getParameter('id')));
      $this->form = new DokumentForm($dokument);
      $this->processForm($request, $this->form);
      $this->setTemplate('edit');
      $this->setArchiv($request);
      $this->archiv_id = $dokument->getArchivId();
      }

      public function executeDelete(sfWebRequest $request)
      {
      $request->checkCSRFProtection();
      $this->forward404Unless($dokument = Doctrine_Core::getTable('Dokument')
      ->find(array($request->getParameter('id'))), sprintf('Object dokument does not exist (%s).', $request->getParameter('id')));
      $dokument->delete();
      $this->redirect('dokument/index');
      }

      protected function processForm(sfWebRequest $request, sfForm $form)
      {
      $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
      if ($form->isValid()) {
      $this->dokument = $form->save();
      $this->redirect('dokument/edit?id=' . $this->dokument->getId());
      $this->sendNotificationEmail();
      }
      }

      protected function sendNotificationEmail()
      {
      $body = '<p>Hallo!<p><p>Es wurde eine Archivalie hochgeladen/ aktualisiert:</p><p><a href="http://historischesarchivkoeln.de/backend.php/dokument/' . $this->dokument->getId() . '/edit">Dokument im Backend bearbeiten</a></p>';

      $message = Swift_Message::newInstance()
      ->setFrom(sfConfig::get('app_dokument_notification_from'))
      ->setTo(sfConfig::get('app_dokument_notification_to'))
      ->setSubject('Meine Archivalien: ' . $this->dokument->getTitel())
      ->setBody($body);
      $this->getMailer()->send($message);
      }
     */

    /**
     * current Archiv
     */
    protected function setArchiv(sfWebRequest $request)
    {
        $allFormValues = $request->getParameter($this->form->getName());
        $node = Doctrine_Core::getTable('Archiv')->find($allFormValues['archiv_id']);
        if (is_object($node)) {
            $this->archiv_title = '<b>' . $node->getName() . ' </b>' . $node->getModel($node->getType()) . ' ' . $node->getSignatur();
            $this->archiv_id = $node->getId();
            $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')->findByArchivId($allFormValues['archiv_id']);
            $this->selected_ve = $allFormValues['verzeichnungseinheit_id'];
        } else {
            $this->archiv_id = '';
            $this->archiv_title = '';
            $this->verzeichnungseinheiten = '';
        }
    }

    /**
     * Render Archiv tree (Ajax)
     */
    public function executeTree(sfWebRequest $request)
    {
        $this->current = $request->getParameter('id');
        if ($this->current == '') {
            $this->current = false;
        }
        if (false === $this->current) {
            $current = Doctrine_Core::getTable('Archiv')
                    ->createQuery()
                    ->orderBy('id asc')
                    ->execute()
                    ->getFirst();
            $this->current = $current->getId();
        }
        if ($this->currentArchiv = Doctrine_Core::getTable('Archiv')->findOneById($this->current)) {
            $q = Doctrine_Core::getTable('Archiv')->createQuery('c')->orderBy('c.lft');
            $this->path = array();
            if ($this->ancestors = $this->currentArchiv->getNode()->getAncestors()) {
                foreach ($this->ancestors->toArray() as $ancestor) {
                    $this->path[] = $ancestor['id'];
                }
            }
            // add current node, for rendering
            array_push($this->path, $this->current);
            $this->tree = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
        } else {
            $this->tree = false;
        }
        $this->setLayout(false);
    }

    public function executeVerzeichnungseinheiten(sfWebRequest $request)
    {
        $this->current = $request->getParameter('id');
        $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery()
                ->where('archiv_id = ?', $this->current)
                ->orderBy('titel')
                ->execute();
        $this->setLayout(false);
    }

}
