<?php

/**
 * Home actions.
 *
 * @author  Norman Fiedler
 * @author  Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class homeActions extends myActions
{

    public function executeIndex(sfWebRequest $request)
    {
        if (!$request->getParameter('sf_culture')) {
            $culture = 'de';
            $this->getUser()->setCulture($culture);
            $this->getUser()->isFirstRequest(false);
            $this->redirect('localized_homepage');
        }

        $this->setMetaTitle();
        $this->setMetaDescription();
        $this->getRequest()->setAttribute('layout', 'blank');
    }

}
