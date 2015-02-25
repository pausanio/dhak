<?php

require_once dirname(__FILE__) . '/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{

    public function setup()
    {
        $this->enablePlugins(array(
            'sfDoctrinePlugin',
            'sfDoctrineGuardPlugin',
            'sfDoctrineActAsSignablePlugin',
            'sfFormExtraPlugin',
            'sfImageTransformPlugin',
            'sfDoctrineActAsTaggablePlugin',
            'sfDoctrineChoiceChainPlugin',
            'sfThumbnailPlugin',
            'csDoctrineActAsSortablePlugin',
            'sfLucenePlugin',
            'sfZendQueuePlugin',
            'sfCaptchaGDPlugin',
            'prestaForumConnectorPlugin'
        ));

        // Form schema formatter for Twitter Bootstrap
        sfWidgetFormSchema::setDefaultFormFormatterName('bootstrap');
        // listen to symfony's clear:cache task's event
        $this->dispatcher->connect( 'task.cache.clear', array( 'prestaForumFactory', 'listenToClearCacheEvent' ) );
        $this->enablePlugins('sfCaptchaGDPlugin');
  }

    static protected $zendLoaded = false;

    static public function registerZend()
    {
        if (self::$zendLoaded) {
            return;
        }

        set_include_path(sfConfig::get('sf_lib_dir') . '/vendor' . PATH_SEPARATOR . get_include_path());
        require_once sfConfig::get('sf_lib_dir') . '/vendor/Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();
        self::$zendLoaded = true;
    }

}

