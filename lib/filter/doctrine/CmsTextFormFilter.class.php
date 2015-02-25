<?php

/**
 * CmsText filter form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Norman Fiedler
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CmsTextFormFilter extends BaseCmsTextFormFilter
{
  public function configure()
  {
    $this->widgetSchema['created_by'] = new sfWidgetFormDoctrineChoice(
    array('model' => $this->getModelName(), 'add_empty' => true, 'method'=>'getEditors'));
  }
}
