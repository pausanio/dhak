<?php

require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineGuardPlugin/modules/sfGuardUser/lib/BasesfGuardUserActions.class.php');

class sfGuardUserActions extends BasesfGuardUserActions {

    public function executeProfile(sfWebRequest $request) {
        $this->User = $this->getUser();
        $this->forward404Unless($this->User);
        $this->form = new sfGuardUserProfileEditForm($this->User->getGuardUser());
    }

    public function executeUpdateProfile(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->User = $this->getUser();
        $this->form = new sfGuardUserProfileEditForm($this->User->getGuardUser());

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
            $user = $this->form->save();
            $this->getUser()->setFlash('success', 'Profiländerungen gespeichert!');
        } else {
            $this->getUser()->setFlash('error', 'Fehler beim Änderungen speichern!');
            foreach ($this->form->getErrorSchema()->getErrors() as $e) {
                $this->getUser()->setFlash('error', $e->__toString());
            }
        }
        $this->redirect('@sf_guard_profile');
    }

}
