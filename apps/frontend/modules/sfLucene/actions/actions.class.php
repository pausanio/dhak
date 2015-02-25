<?php
/*
 * This file is part of the sfLucenePlugin package
 * (c) 2007 - 2008 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once sfConfig::get('sf_plugins_dir') . '/sfLucenePlugin/modules/sfLucene/lib/BasesfLuceneActions.class.php';

/**
 * @package    sfLucenePlugin
 * @subpackage Module
 * @author     Carl Vondrick <carl@carlsoft.net>
 * @version SVN: $Id: actions.class.php 6247 2007-12-01 03:25:13Z Carl.Vondrick $
 */
class sfLuceneActions extends BasesfLuceneActions
{

    public function executeSearch($request)
    {
        try {
            return parent::executeSearch($request);
        } catch (Zend_Search_Lucene_Exception $e) {
            $this->getUser()->setFlash('error', 'Bei der Suche ist ein Fehler aufgetreten: '.$e->getMessage());
            $referer = $request->getReferer();
            if(empty($referer)){
                $referer = '@homepage';
            }
            return $this->redirect($referer);
        }
    }

    public function preExecute()
    {
        $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
        $this->getRequest()->setAttribute('search_wrapper_class', 'archive');
        $this->getRequest()->setAttribute('inner_class', 'arch');
    }

    /**
     * Returns an instance of sfLucene configured for this environment.
     */
    protected function getLuceneInstance()
    {
        return sfLuceneToolkit::getApplicationInstance();
    }
}
