<?php

/**
 * HaFaq form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class HaFaqForm extends BaseHaFaqForm
{

  public function configure()
  {

    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['created_by']);
    unset($this['updated_by']);

    $this->widgetSchema['question'] = new sfWidgetFormTextareaTinyMCE(array(
          'width' => sfConfig::get('app_tinymce_options_width'),
          'height' => sfConfig::get('app_tinymce_options_height'),
          'config' => sfConfig::get('app_tinymce_options')));

    $this->widgetSchema['answer'] = new sfWidgetFormTextareaTinyMCE(array(
          'width' => sfConfig::get('app_tinymce_options_width'),
          'height' => sfConfig::get('app_tinymce_options_height'),
          'config' => sfConfig::get('app_tinymce_options')));
  }

}
