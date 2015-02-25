<?php

class myUser extends prestaForumConnectorSfGuardDoctrineSecurityUser
{

  public function isFirstRequest($boolean = null)
  {
    if (is_null($boolean))
    {
      return $this->getAttribute('first_request', true);
    }
  
    $this->setAttribute('first_request', $boolean);
  }

}
