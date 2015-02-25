<?php

class viewerComponents extends sfComponents
{

    public function executeRoutes(sfWebRequest $request)
    {
        $docRoutes = array();
        if ($this->parentType == 've') {
            $nextprev = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('verzeichnungseinheit_id = ?', (int)$this->parentId)
                ->orderBy('position ASC')
                ->execute();
        } else {
            $nextprev = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('archiv_id = ?', (int)$this->parentId)
                ->orderBy('id ASC')
                ->execute();
        }
        if ($nextprev) {
            $i = 1;
            foreach ($nextprev as $value) {
                if($value->id == $this->currentId){
                    $this->currentIndex = $i;
                }
                $docRoutes[] = $this->generateUrl('lesesaal_dokument', array('sf_culture' => sfContext::getInstance()->getUser()->getCulture(),
                    'id' => $value->id,
                    'slug' => $value->getSignaturSlug()));
                $i++;
            }
        }
        $this->docRoutes = $docRoutes;
    }

    public function executePager(sfWebRequest $request)
    {
        $this->currentArchiv = $this->veid;
        $count = Doctrine_Core::getTable('Dokument')
            ->createQuery()
            ->where('verzeichnungseinheit_id = ?', (int)$this->currentArchiv)
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
                    ->where('verzeichnungseinheit_id = ?', (int)$this->currentArchiv)
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
    }

//    public function executeViewer(sfWebRequest $request){
//        $dokument;
//        $parent;//VE oder archiv
//        $this->uiSettings = false;
//        $this->myDokument = null;
//        $this->myVE = null;
//
//        if ($this->getUser()->getGuardUser()) {
//            $this->myDokument = $this->dokument->getMyDokumenteForUser(($this->getUser()->getGuardUser()->getId()));
//            if ($this->myDokument) {
//                $this->uiSettings = json_decode($this->myDokument->getViewerSettings());
//            }
//            if (!empty($this->verzeichnungseinheit)) {
//                $this->myVE = $this->verzeichnungseinheit->getMyVerzeichnungseinheitenForUser(($this->getUser()->getGuardUser()->getId()));
//                if (!$this->uiSettings && $this->verzeichnungseinheit) {
//                    //if no myDokument check for myVE
//                    if ($this->myVE) {
//                        $this->uiSettings = json_decode($this->myVE->getViewerSettings());
//                    }
//                }
//            }
//        }
//    }

    protected function loadPager($page, $limit = 10)
    {
        $this->dokumente = Doctrine_Core::getTable('Dokument')
            ->createQuery()
            ->where('verzeichnungseinheit_id = ?', (int)$this->currentArchiv);

        $this->doc_pager = new sfDoctrinePager(
            'Dokumente', $limit
        );
        $this->doc_pager->setQuery($this->dokumente);
        $this->doc_pager->setPage($page);
        $this->doc_pager->init();
    }

}

