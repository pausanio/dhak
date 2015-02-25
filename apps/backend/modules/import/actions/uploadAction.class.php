<?php

/**
 * import upload action.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Maik Mettenheimer
 * @since      2013-06-10
 */
class uploadAction extends sfActions
{

    /**
     * Index action
     *
     * @param type $request
     */
    public function execute($request)
    {
        if ($request->getParameter('type') == 'xml') {
            $type = 'bestand';
            $path = sfConfig::get('app_import_bestand');
        } else {
            $type = 'verzeichnungseinheit';
            $path = sfConfig::get('app_import_verzeichnungseinheit');
        }
        
        if (!empty($_FILES)) {
            $u = move_uploaded_file($_FILES['file']['tmp_name'], $path . $_FILES['file']['name']);
            
            if($u){
                //queue for validation
                $v = sfZendQueue::getInstance('validation.'.$type);
                $v->send($path . $_FILES['file']['name']);
            }
            
        }
        $this->setLayout(false);
        return sfView::NONE;
    }

}

