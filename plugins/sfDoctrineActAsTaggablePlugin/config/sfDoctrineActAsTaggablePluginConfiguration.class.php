<?php

/**
 * sfDoctrineActAsTaggablePluginConfiguration configuration.
 * 
 * @package     sfDoctrineActAsTaggablePlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: sfDoctrineActAsTaggablePluginConfiguration.class.php 31458 2010-11-19 08:38:57Z gimler $
 */
class sfDoctrineActAsTaggablePluginConfiguration extends sfPluginConfiguration
{
  static $registered = false;

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Yes, this can get called twice. This is Fabien's workaround:
    // http://trac.symfony-project.org/ticket/8026
    if (!self::$registered)
    {
      if (sfConfig::get('app_taggable_routes_register', true))
      {
        $this->dispatcher->connect('routing.load_configuration', array('taggableRouting', 'listenToRoutingLoadConfigurationEvent'));
      }

      self::$registered = true;
    }
  }
  
}
