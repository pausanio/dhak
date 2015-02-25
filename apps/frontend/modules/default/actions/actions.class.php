<?php

/**
 * default actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archiv
 * @author     Ivo Bathke
 */
class defaultActions extends myActions
{

    public function executeError404(sfWebRequest $request)
    {
        $this->getRequest()->setAttribute('layout', 'blank');
    }

    public function executeGetFooter(sfWebRequest $request)
    {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);
    }

}
