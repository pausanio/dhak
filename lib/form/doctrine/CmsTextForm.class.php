<?php

/**
 * CmsText form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Norman Fiedler
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class CmsTextForm extends BaseCmsTextForm
{

    public function configure()
    {
        unset($this->widgetSchema['created_at'], $this->widgetSchema['updated_at']);
        unset($this->validatorSchema['created_at'], $this->validatorSchema['updated_at']);

        $this->widgetSchema['text'] = new sfWidgetFormTextareaTinyMCE(array(
            'width' => sfConfig::get('app_tinymce_options_width'),
            'height' => sfConfig::get('app_tinymce_options_height'),
            'config' => sfConfig::get('app_tinymce_options')));
    }

}
