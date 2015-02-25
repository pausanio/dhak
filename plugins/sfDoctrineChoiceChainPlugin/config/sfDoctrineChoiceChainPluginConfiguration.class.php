<?php


/**
 * sfDoctrineChoiceChainPlugin configuration.
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage config
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */
class sfDoctrineChoiceChainPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {


    if (sfConfig::get('app_sf_choice_chain_plugin_routes_register', true) && in_array('sfChoiceChain', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('sfChoiceChainRouting', 'addRoutes'));
    }

  }
}
