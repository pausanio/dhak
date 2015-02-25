<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm
{

  /**
   * @see sfForm
   */
  public function configure()
  {
    parent::configure();
    //$this->embedRelation('Profile');return;
    //die(get_class($this->object));
    $profileForm = new sfGuardUserProfileForm($this->object->getProfile());
    unset($profileForm['id'], $profileForm['user_id'], $profileForm['role'], $profileForm['status'], $profileForm['created_by'], $profileForm['updated_by']);
    //$profileForm->setLayout(new sfListLayout());
    $this->embedForm('Profile', $profileForm);
    //$this->mergeForm($profileForm);
  }

}
