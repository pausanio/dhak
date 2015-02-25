<?php

/**
 * sfGuardUserProfile form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Norman Fiedler
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserProfileForm extends BasesfGuardUserProfileForm
{
  public function configure()
  {
    unset($this->widgetSchema['created_at'], $this->widgetSchema['updated_at']);
    unset($this->validatorSchema['created_at'], $this->validatorSchema['updated_at']);
  }
}
