<?php

/**
 * archiv actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archiv
 * @author     Maik Mettenheimer
 */
class archivActions extends myActions
{

    /**
     * Set Meta data and Layout vars
     */
    public function preExecute()
    {
        $this->getRequest()->setAttribute('layout', 'blank');
    }

    /**
     * List Tree
     *
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request)
    {
        // current archiv id
        $this->current = $request->getParameter('id', false);
        //TODO for highlighting
        $this->query = $request->getParameter('query', false);

        if (false === $this->current) {
            $this->current = Archiv::getRoot();
        }

        $this->loadNavi($this->current);
        $this->loadContactperson();

        $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')
            ->createQuery()
            ->where('archiv_id = ?', $this->currentArchiv->getId())
            ->execute();

        $uId = false;
        if($this->getUser()->getGuardUser()){
            $uId = $this->getUser()->getGuardUser()->getId();
        }

        if(count($this->verzeichnungseinheiten) > 0 && $uId){
            $myVE = $this->getUser()->getGuardUser()->getMyVerzeichnungseinheit();
            $this->myVEs = array();
            foreach($myVE as $ve){
                $this->myVEs[] = $ve->verzeichnungseinheit_id;
            }
        }

        $this->verweise = Doctrine_Core::getTable('Verweis')
            ->createQuery()
            ->where('archiv_id = ?', $this->currentArchiv->getId())
            ->execute();

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('navigation', $this->sidebar_params);
        }

        $this->setMetaTitle('Lesesaal - ' . $this->currentArchiv->getModel($this->currentArchiv->getType()) . ' - ' . $this->currentArchiv->getSignatur() . ' - ' . $this->currentArchiv->getName());
        $this->setMetaDescription($this->currentArchiv->getName() . ' - ' . $this->currentArchiv->getBeschreibung());
    }

    /**
     * render dfgviewer xml
     *
     * @param sfWebRequest $request
     */
    public function executeMets(sfWebRequest $request)
    {
        $ve_id = $request->getParameter('ve_id', false);
        $request->setRequestFormat('xml');
        if ($ve_id) {
            $this->type = 'Docs';
            //get VE
            $this->ve = Doctrine_Core::getTable('Verzeichnungseinheit')->findOneBy('id', (int)$ve_id);
            //get Dokumente
            $this->dokument = Doctrine_Core::getTable('Dokument')->createQuery('a')
                ->where('a.verzeichnungseinheit_id = ?', (int)$ve_id)
                ->execute();
        }
    }

    public function executeMetsUserDocs(sfWebRequest $request)
    {
        $id = $request->getParameter('id', false);
        $request->setRequestFormat('xml');
        $this->setTemplate('mets', 'archiv');
        if ($id) {
            $this->type = 'userDocs';
            //todo das geht davon aus, dass userDokumente nicht unter VE liegen können, das ist falsch!
            //todo da muss ein switch rein
            $this->ve = Doctrine_Core::getTable('Archiv')->findOneBy('id', (int)$id);
            $this->dokument = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('verzeichnungseinheit_id IS NULL')
                ->andWhere('usergenerated = ?', 1)
                ->andWhere('archiv_id = ?', (int)$id)
                ->execute();
        } else {
            $this->forward404();
        }
    }

    public function executePdf(sfWebRequest $request)
    {
        $doc_id = $request->getParameter('doc_id');
        $Doc = Doctrine_Core::getTable('Dokument')->createQuery('a')
            ->where('a.id = ?', (int)$doc_id)
            ->fetchOne();
        $pdfpath = $Doc->getPdf();
        $this->forward404Unless($pdfpath);

        // Adding the file to the Response object
        $this->getResponse()->clearHttpHeaders();
        $this->getResponse()->setHttpHeader('Pragma: public', true);
        $this->getResponse()->setContentType('application/pdf');
        $this->getResponse()->sendHttpHeaders();
        $this->getResponse()->setContent(readfile($pdfpath));

        return sfView::NONE;
    }

    /**
     * render VE page
     *
     * @param sfWebRequest $request
     */
    public function executeVerzeichnungseinheit(sfWebRequest $request)
    {

        $this->currentVE = $request->getParameter('id', false);

        if ($this->currentVE === false) {
            $this->redirect404();
        }

        //get VE
        $this->verzeichnungseinheit = Doctrine_Core::getTable('Verzeichnungseinheit')
            ->createQuery()
            ->where('id = ?', (int)$this->currentVE)
            ->execute()
            ->getFirst();
        if ($this->verzeichnungseinheit === false) {
            $this->redirect404();
        }

        //navi
        $this->current = $this->verzeichnungseinheit->archiv_id;
        if (false === $this->current) {
            $this->current = Archiv::getRoot();
        }
        $this->setMetaTitle('Lesesaal - Verzeichnungseinheit - ' . $this->verzeichnungseinheit->getBestandSig() . ' - ' . $this->verzeichnungseinheit->getSignatur() . ' - ' . $this->verzeichnungseinheit->getTitel());
        $this->setMetaDescription('Der digitale Lesesaal für die Geschichte der Stadt Köln. Verzeichnungseinheit: ' . $this->verzeichnungseinheit->getTitel());
        $this->loadNavi($this->current);
    }

    /**
     * Render single Dokument page / open image viewer
     *
     * @param sfWebRequest $request
     */
    public function executeDokument(sfWebRequest $request)
    {
        $this->setLayout('viewer');

        $this->currentDok = $request->getParameter('id', false);
        if ($this->currentDok === false) {
            $this->redirect404();
        }

        $this->dokument = Doctrine_Core::getTable('Dokument')->findOneById((int)$this->currentDok);
        if ($this->dokument === false) {
            $this->redirect404();
        }

        $veId = $this->dokument->getVerzeichnungseinheitId();
        $this->verzeichnungseinheit = false;
        if ($this->dokument && !empty($veId)) {
            $this->verzeichnungseinheit = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->findOneById((int)$veId);
        }

        //load settings
        $this->uiSettings = false;
        $this->myDokument = null;
        $this->myVE = null;
        if ($this->getUser()->getGuardUser()) {
            $this->myDokument = $this->dokument->getMyDokumenteForUser(($this->getUser()->getGuardUser()->getId()));
            if ($this->myDokument) {
                $this->uiSettings = json_decode($this->myDokument->getViewerSettings());
            }
            if (!empty($this->verzeichnungseinheit)) {
                $this->myVE = $this->verzeichnungseinheit->getMyVerzeichnungseinheitenForUser(($this->getUser()->getGuardUser()->getId()));
                if (!$this->uiSettings && $this->verzeichnungseinheit) {
                    //if no myDokument check for myVE
                    if ($this->myVE) {
                        $this->uiSettings = json_decode($this->myVE->getViewerSettings());
                    }
                }
            }
        }

        $this->current = $this->dokument->archiv_id;
        if (false === $this->current) {
            $this->current = Archiv::getRoot();
        }

        if ($this->verzeichnungseinheit) {
            $title = $this->verzeichnungseinheit->getTitel();
            $currentSignatur = $this->verzeichnungseinheit->getBestandSig() . ' ' . $this->verzeichnungseinheit->getSignatur();
        } else {
            $title = $title = $this->dokument->getTitel();
            $currentSignatur = $this->dokument->getBestandSig() . ' ' . $this->dokument->getSignatur();
        }
        $this->setMetaTitle('Lesesaal - Dokument - ' . $this->dokument->getBestandSig() . ' - ' . $this->dokument->getSignatur() . ' - ' . $title . ' - Datei ' . $this->dokument->getPosition());
        $this->setMetaDescription('Der digitale Lesesaal für die Geschichte der Stadt Köln. Dokument: ' . $title . ' - Seite ' . $this->dokument->getPosition());
        $this->loadNavi($this->current);

        $this->pdf = $this->dokument->getPdf();

        $this->getRequest()->setAttribute('current_dok', $this->dokument);
        if ($this->verzeichnungseinheit) {
            $this->getRequest()->setAttribute('current_ve', $this->verzeichnungseinheit);
        } else {
            $this->getRequest()->setAttribute('current_ve', $this->dokument);
        }
        $this->getRequest()->setAttribute('current_signatur', $currentSignatur);

        // get cms info page 'lese- und arbeitshilfen'
        $this->infopage = Doctrine_Core::getTable('CmsInfo')->findOneBy('route_name', 'lese-und-arbeitshilfen');
    }

    protected function loadNavi($id)
    {
        if ($this->currentArchiv = Doctrine_Core::getTable('Archiv')->findOneById((int)$id)) {
            $this->path = array();
            if ($this->ancestors = $this->currentArchiv->getNode()->getAncestors()) {
                foreach ($this->ancestors->toArray() as $ancestor) {
                    $this->path[] = $ancestor['id'];
                }
            }
            // add current node, for rendering
            array_push($this->path, $this->current);

            $q = Doctrine_Core::getTable('Archiv')->createQuery('c')
                ->andWhere('c.lft BETWEEN ' . $this->currentArchiv->getNode()->getLeftValue() . ' and ' . $this->currentArchiv->getNode()->getRightValue())
                ->orWhere('c.level <= ?', $this->currentArchiv->getNode()->getLevel())
                ->orderBy('c.lft');
            //könnte man wahrscheinlich auch mit $tree->setBaseQuery($query); machen
            $this->tree = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
        } else {
            $this->tree = false;
        }
    }

    /**
     * loads ContactPerson for given node
     *
     * return void
     */
    protected function loadContactperson()
    {
        $this->contactperson = false;
        $this->contactperson_filename = false;
        if ($this->currentArchiv) {
            if ($this->ancestors = $this->currentArchiv->getNode()->getAncestors()) {
                foreach ($this->ancestors as $ancestor) {
                    if ($ancestor->getContactperson()) {
                        $this->contactperson = $ancestor->getContactperson();
                        $this->contactperson_filename = $ancestor->getContactpersonFilename();
                    }
                }
            }
            if ($this->currentArchiv->getContactperson()) {
                $this->contactperson = $this->currentArchiv->getContactperson();
                $this->contactperson_filename = $this->currentArchiv->getContactpersonFilename();
            }
        }
    }

    public function executeTags(sfWebRequest $request)
    {
        $items = false;
        $query = trim($request->getParameter('tags'));
        switch ($query) {
            case 'beliebt':
                $this->headline = 'Die beliebtesten Bestände';
                $list = array('Best. 1', 'Best. 95', 'Best. 110', 'Best. 902', 'Best. 7030');
                break;
            case 'schoen':
                $this->headline = 'Die schönsten Bestände';
                $list = array('Best. 234', 'Best. 721', 'Best. 1108', 'Best. 1217', 'Best. 1311', 'Best. 1558');
                break;
            case 'bedeutend':
                $this->headline = 'Die bedeutensden Bestände';
                $list = array('Best. 1', 'Best. 10', 'Best. 30', 'Best. 101', 'Best. 400', 'Best. 7010', 'Best. 7030');
                break;
            case 'zufaellig':
            default:
                $this->headline = 'Zufällige Bestände';
                $list = self::getRandomBestand();
                break;
        }

        $dokument = Doctrine_Core::getTable('Dokument')
            ->createQuery()
            ->whereIn('bestand_sig', $list)
            ->andWhere('filename IS NOT NULL')
            ->orderBy('bestand_sig')
            ->execute()
            ->getFirst();
        $archiv = Doctrine_Core::getTable('Archiv')
            ->createQuery()
            ->whereIn('signatur', $list)
            ->orderBy('signatur')
            ->fetchOne();

        if ($dokument && $archiv) {
            $items[] = array(
                'name' => $archiv->getName(),
                'signatur' => $archiv->getSignatur(),
                'archiv_id' => $archiv->getId(),
                'filename' => $dokument->getThumb(),
            );
        }

        $this->setMetaTitle('Das digitale Historische Archiv Köln - ' . $this->headline);
        $this->setMetaDescription($this->headline);

        $this->items = $items;
    }

    protected static function getRandomBestand($limit = 12)
    {
        $results = Doctrine_Core::getTable('Dokument')
            ->createQuery()
            ->select('bestand_sig')
            ->where('status = ?', 1)
            ->andWhere('bestand_sig IS NOT NULL')
            ->andWhere('filename LIKE ? ', '%jpg')
            ->distinct(true)
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute()
            ->toArray();

        $bestand_sig = array();
        foreach ($results as $bestand) {
            $bestand_sig[] = $bestand['bestand_sig'];
        }
        return $bestand_sig;
    }

}

