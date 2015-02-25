<?php

/**
 * verzeichnungseinheit actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archiv
 * @author     Ivo Bathke
 */
class verzeichnungseinheitActions extends myActions {

    public function preExecute() {
        $this->setMetaTitle('Lesesaal - Verzeichnungseinheit');
        $this->setMetaDescription();
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request) {
         $this->current = $request->getParameter('id', false);
        
        if($this->current === false){
            $this->redirect404();
        }
        
        //get VE
        $this->verzeichnungseinheit = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery()
                ->where('id = ?', (int)$this->current)
                ->execute();
        if($this->verzeichnungseinheit === false){
            $this->redirect404();
        }
        var_dump($this->verzeichnungseinheit);exit;
    }

}