<?php

/**
 * My actions helper
 *
 * @author  Maik Mettenheimer <metenheimer@pausanio.de>
 * @since   2011-12-06
 *
 * @see http://stackoverflow.com/questions/2722273/dynamic-page-titles-in-symfony-1-4
 */
class myActions extends sfActions
{

  /**
   * Set meta title
   *
   * @param type $string
   */
  protected function setMetaTitle($title = false)
  {
    if ($title === false) {
      return false;
    }
    $this->getResponse()->setTitle($title . sfConfig::get('app_title_separator') . sfConfig::get('app_title_default'));
  }

  /**
   * Set meta description
   *
   * @param type $string
   */
  protected function setMetaDescription($description = false)
  {
    if ($description === false) {
      return false;
    }
    $this->getResponse()->addMeta('description', $description);
  }

}