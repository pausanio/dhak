<?php

/**
 * newsletter actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage newsletter
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class newsletterActions extends myActions
{

    public function preExecute()
    {
        $this->setMetaTitle('Newsletter');
        $this->setMetaDescription();
    }

    /**
     * Newsletter form
     *
     * @param sfWebRequest $request
     */
    public function executeIndex(sfWebRequest $request)
    {
        // Get 'newsletter' text snippet
        $this->cms_text = cmsText::getTextByLanguage('newsletter');

        $this->sendform = false;
        $this->form = new NewsletterForm();

        if ($request->hasParameter('newsletter')) {
            $this->form->bind($request->getParameter('newsletter'));

            if ($this->form->isValid()) {
                $this->sendform = true;

                require_once (sfConfig::get('sf_lib_dir') . '/vendor/mailchimp-api-class/MCAPI.class.php');

                $email = $this->form->getValue('email');
                $apiKey = sfConfig::get('app_mailchimp_apikey');
                $listId = sfConfig::get('app_mailchimp_listid');
                $campaignId = sfConfig::get('app_mailchimp_campaignid');
                $apiUrl = sfConfig::get('app_mailchimp_apiurl');

                $api = new MCAPI($apiKey);
                $retval = $api->listSubscribe($listId, $email);

                $this->error = false;
                if ($api->errorCode) {
                    $this->error = "Es ist ein Fehler bei der Anmeldung aufgetreten: " . $api->errorMessage;
                }
            }
        }
    }

}

