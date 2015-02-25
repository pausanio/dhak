<?php

require_once dirname(__FILE__) . '/../lib/dokumentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/dokumentGeneratorHelper.class.php';

/**
 * dokument actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage dokument
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class dokumentActions extends autoDokumentActions
{

    public function executeNew(sfWebRequest $request)
    {
        $this->selected_ve = false;
        $this->verzeichnungseinheiten = false;
        $dok = $this->getUser()->getAttribute('prefilledDokument', false);
        if($dok){
            $newdok = $dok->copy();
        }
        else{
            $newdok = new Dokument();
        }
        $this->form = new BackendDokumentForm($newdok);
        $this->dokument = $this->form->getObject();
        $this->setArchiv($request);
        if($this->getUser()->hasAttribute('prefilledDokument')){
            $this->setArchivValues($dok->archiv_id, $dok->verzeichnungseinheit_id);
        }
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->form = new BackendDokumentForm();
        $this->dokument = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setArchiv($request);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
        $this->forward404Unless($dokument = Doctrine_Core::getTable('Dokument')->find(array($request->getParameter('id'))),
                                sprintf('Object dokument does not exist (%s).', $request->getParameter('id')));
        $this->form = new BackendDokumentForm($dokument);
        if (!$dokument->getArchivId()) {
            $this->archiv_id = '';
            $this->archiv_title = '';
            $this->selected_ve = false;
            $this->verzeichnungseinheiten = false;
        } else {
            $node = Doctrine_Core::getTable('Archiv')->find($dokument->getArchivId());
            $this->archiv_id = $dokument->getArchivId();
            $this->archiv_title = $node->getName();
            $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')->findByArchivId($dokument->getArchivId());
            $this->selected_ve = $dokument->getVerzeichnungseinheitId();
        }
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($dokument = Doctrine_Core::getTable('Dokument')
            ->find(array($request->getParameter('id'))), sprintf('Object dokument does not exist (%s).', $request->getParameter('id')));
        $this->form = new BackendDokumentForm($dokument);
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
        $this->setArchiv($request);
        $this->archiv_id = $dokument->getArchivId();

    }

    public function executeDelete(sfWebRequest $request)
    {
        die('Die Löschfunktion ist derzeit noch nicht implementiert...');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'Dokument wurde gespeichert.' : 'Dokument wurde aktualisiert.';
            $dokument = $form->save();

            if ($request->hasParameter('_save_and_add'))
            {
                $dok = $dokument->copy();
                $this->getUser()->setAttribute('prefilledDokument', $dok);
                $this->getUser()->setFlash('notice', $notice.' Sie können hier ein neues anlegen.');
                $this->redirect('@dokument_new');
            }
            else
            {
                $this->getUser()->getAttributeHolder()->remove('prefilledDokument');
                $this->getUser()->setFlash('notice', $notice);
                $this->redirect('dokument/edit?id=' . $dokument->getId());
            }
        }
    }

    /**
     * current Archiv
     */
    protected function setArchiv(sfWebRequest $request)
    {
        $allFormValues = $request->getParameter($this->form->getName());
        $this->setArchivValues($allFormValues['archiv_id'], $allFormValues['verzeichnungseinheit_id']);
    }

    protected function setArchivValues($archivId, $veId){
        $node = Doctrine_Core::getTable('Archiv')->find($archivId);
        if (is_object($node)) {
            $this->archiv_title = '<b>' . $node->getName() . ' </b>' . $node->getModel($node->getType()) . ' ' . $node->getSignatur();
            $this->archiv_id = $node->getId();
            $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')->findByArchivId($archivId);
            $this->selected_ve = $veId;
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
                ->limit(1)
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

    /**
     * Find archiv_id for old uploaded Dokuments
     */
    public function executeFindarchiv()
    {
        $i = 0;
        $k = 0;
        $t = 0;
        $dokuments = Doctrine_Core::getTable('Dokument')
            ->createQuery()
            ->where('archiv_id IS NULL')
            ->andWhere('usergenerated = ?', 1)
            ->andWhere('validated = ?', 0)
            ->limit(1000)
            ->execute();
        foreach ($dokuments as $dokument) {
            $verzeichnungseinheit = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery()
                ->where('bestand_sig = ?', $dokument->getBestandSig())
                ->andWhere('signatur = ?', $dokument->getSignatur())
                ->fetchOne();
            if (is_object($verzeichnungseinheit)) {
                $dokument->setVerzeichnungseinheitId($verzeichnungseinheit->getId());
                $dokument->setArchivId($verzeichnungseinheit->getArchivId());

                // found a dokument: copy file from old filesystem
                $file = sfConfig::get('app_dokument_user_filesystem_old') . $dokument->getFilename();
                $newfile = sfConfig::get('app_dokument_user_filesystem') . $dokument->getFilename();
                if (!@copy($file, $newfile)) {
                    echo "<br>VE-Zuordnung: File not found: $file...\n";
                }
                echo '<br>VE-Zuordnung: Update ID' . $dokument->getId();
                $i++;
            } else {
                $bestand = Doctrine_Core::getTable('Bestand')
                    ->createQuery()
                    ->where('bestand_sig = ?', $dokument->getBestandSig())
                    ->fetchOne();
                if (is_object($bestand)) {
                    $dokument->setArchivId($bestand->getArchivId());

                    // found a dokument: copy file from old filesystem
                    $file = sfConfig::get('app_dokument_user_filesystem_old') . $dokument->getFilename();
                    $newfile = sfConfig::get('app_dokument_user_filesystem') . $dokument->getFilename();
                    if (!@copy($file, $newfile)) {
                        echo "<br>Bestand-Zuordnung: File not found: $file...\n";
                    }

                    echo '<br>Bestand-Zuordnung: Ipdate ID' . $dokument->getId();
                    $k++;
                }
            }
            $dokument->setValidated(1);
            $dokument->save();
            $t++;
        }

        echo "<br><hr><br>Zugeordnete Verzeichnungseinheiten: " . $i;
        echo "<br>Zugeordnete Bestaende: " . $k;
        echo "<br>Dokumente gesamt: " . $t;
        $this->setLayout(false);
        exit();
    }

}

