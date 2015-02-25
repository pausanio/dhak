<?php

/**
 * HaNews filter form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Norman Fiedler
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HaNewsFormFilter extends BaseHaNewsFormFilter
{
  public function configure()
  {
  $this->widgetSchema['publish_date'] = new sfWidgetFormFilterDate(
    array('from_date' => new sfWidgetFormI18nDate(array('culture'=>'de')), 
          'to_date' =>new sfWidgetFormI18nDate(array('culture'=>'de'))));
     
  }
}
