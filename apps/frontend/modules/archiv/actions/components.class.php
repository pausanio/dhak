<?php

class archivComponents extends sfComponents
{

    /**
     * get Dokumente
     *
     * @param type $veId
     */
    public function executeDokumente(sfWebRequest $request)
    {
        $this->currentArchiv = $request->getParameter('id', false);

        $ve = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery()
                ->where('id = ?', (int) $this->currentArchiv)
                ->execute();
        $this->verzeichnungseinheit = $ve->getFirst();

        if ($this->limit = $request->getParameter('limit', false)) {
            $this->getUser()->setAttribute('gallery_pages', $this->limit);
        }
        $this->page = $request->getParameter('page', 1);
        $this->loadPager($this->page, $this->getUser()->getAttribute('gallery_pages', 12));
        $this->content_data = $this->doc_pager->getResults();
        $this->doc_total = $this->doc_pager->getNbResults();

        $this->dokument = false;

        $this->myDoks = array();
        $uId = false;
        if($this->getUser()->getGuardUser()){
            $uId = $this->getUser()->getGuardUser()->getId();
        }

        if(count($this->content_data) > 0 && $uId){
            $myDoks = $this->getUser()->getGuardUser()->getMyDokumente();
            foreach($myDoks as $dok){
                $this->myDoks[] = $dok->dokument_id;
            }
        }
    }

    public function executeDokumentPager(sfWebRequest $request)
    {
        $this->currentArchiv = $this->veid;
        $count = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('verzeichnungseinheit_id = ?', (int) $this->currentArchiv)
                ->count();

        $this->pager = false;
        $this->next = false;
        $this->prev = false;
        if ($count > 0) {
            $get = array();
            if ($this->page > 1) {
                $get[] = $this->page - 1;
            }
            if ($this->page < $count) {
                $get[] = $this->page + 1;
            }
            if (!empty($get)) {
                $nextprev = Doctrine_Core::getTable('Dokument')
                        ->createQuery()
                        ->where('verzeichnungseinheit_id = ?', (int) $this->currentArchiv)
                        ->andWhereIn('position', $get)
                        ->orderBy('position ASC')
                        ->execute();
                if ($nextprev) {
                    $this->pager = true;
                    foreach ($nextprev as $value) {
                        if ($value->position == $this->page - 1) {
                            $this->prev = $value;
                        }
                        if ($value->position == $this->page + 1) {
                            $this->next = $value;
                        }
                    }
                }
            }
        }
        /*
         * count
         * if page > 1 prev
         * if
         */
    }

    /**
     * get Vorgänge für VE
     * @param sfWebRequest $request
     */
    public function executeVorgaenge(sfWebRequest $request)
    {
        $this->vorgaenge = Doctrine_Core::getTable('Vorgang')
                ->createQuery()
                ->where('ve_signatur = ?', $this->veSignatur)
                ->andWhere('bestand_sig = ?', $this->bestandSig)
                ->execute();
    }

    public function executeDokumentBreadcrumb(sfWebRequest $request)
    {
        $this->ve = $request->getAttribute('current_ve');
        $this->dokument = $request->getAttribute('current_dok');
        if ($this->currentArchiv = Doctrine_Core::getTable('Archiv')->findOneById((int) $this->ve->getArchivId())) {
            $this->ancestors = $this->currentArchiv->getNode()->getAncestors();
        }
    }

    protected function loadPager($page, $limit = 10)
    {
        $this->dokumente = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('verzeichnungseinheit_id = ?', (int) $this->currentArchiv);

        $this->doc_pager = new sfDoctrinePager(
                'Dokumente', $limit
        );
        $this->doc_pager->setQuery($this->dokumente);
        $this->doc_pager->setPage($page);
        $this->doc_pager->init();
    }

    /**
     * get&render all usergenerated dokumente for this Archiv
     * that dont have a VE
     *
     * @param sfWebRequest $request
     */
    public function executeUserDokumente(sfWebRequest $request)
    {
        $this->userDokumente = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('verzeichnungseinheit_id IS NULL')
                ->andWhere('usergenerated = ?', 1)
                ->andWhere('archiv_id = ?', (int) $this->currentArchiv->id);
        $this->page = $request->getParameter('page', 1);
        if ($this->limit = $request->getParameter('limit', 12)) {
            $this->getUser()->setAttribute('gallery_pages', $this->limit);
        } elseif ($this->getUser()->hasAttribute('gallery_pages')) {
            $this->limit = $this->getUser()->getAttribute('gallery_pages');
        }
        $this->doc_pager = new sfDoctrinePager(
                'Dokumente', $this->limit
        );
        $this->doc_pager->setQuery($this->userDokumente);
        $this->doc_pager->setPage($this->page);
        $this->doc_pager->init();
        $this->content_data = $this->doc_pager->getResults();
        $this->doc_total = $this->doc_pager->getNbResults();

        $this->dokument = false;

        $this->myDoks = array();
        $uId = false;
        if($this->getUser()->getGuardUser()){
            $uId = $this->getUser()->getGuardUser()->getId();
        }

        if(count($this->userDokumente) > 0 && $uId){
            $myDoks = $this->getUser()->getGuardUser()->getMyDokumente();
            foreach($myDoks as $dok){
                $this->myDoks[] = $dok->dokument_id;
            }
        }

    }

}

