<?php

class myUser extends sfGuardSecurityUser
{

  // @see https://halestock.wordpress.com/2010/02/08/symfony-understanding-permissionscredentials-with-sfdoctrineguardplugin/

  public function hasCredential($credential, $useAnd = true)
  {
    return (parent::hasCredential($credential, $useAnd) || parent::hasPermission($credential));
  }

  public function hasPermission($permission)
  {
    return $this->hasCredential($permission);
  }

}
