<?php

require_once dirname(__FILE__) . '/../lib/sliderGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/sliderGeneratorHelper.class.php';

/**
 * cms slider actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage cms/slider
 * @author     Ivo Bathke
 */
class sliderActions extends autoSliderActions {

    public function executePromote() {
        $object = Doctrine::getTable('CmsSlider')->findOneById($this->getRequestParameter('id'));
        $object->promote();
        $this->redirect("@cms_slider");
    }

    public function executeDemote() {
        $object = Doctrine::getTable('CmsSlider')->findOneById($this->getRequestParameter('id'));
        $object->demote();
        $this->redirect("@cms_slider");
    }

}
