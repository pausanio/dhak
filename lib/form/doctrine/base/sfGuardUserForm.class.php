<?php

/**
 * SfGuardUser form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Norman Fiedler
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SfGuardUserForm extends BaseSfGuardUserForm
{
  public function configure()
  {
    //unset($this->validSchema['created_at'], $this->widgetSchema['updated_at']);
  }
}
