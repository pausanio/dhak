<?php

sfZendQueueToolkit::loadZend();

class sfZendQueue {

    /**
     * Holder for the instances
     */
    static protected $instances = array();

    protected function __construct() {
        
    }

    protected static function create($name) {
        $config = self::getConfig();

        if(isset($config[$name]) === false){
          throw new Exception('No such queue');  
        }
        
        if (isset($config[$name]['adapter'])) {
            $adapter = $config[$name]['adapter'];
        } else {
            throw new Exception('Error loading configuration: no adapter set');
        }
        if(isset($config[$name]['options']['name']) === false){
            $config[$name]['options']['name'] = $name;
        }
        $queueName = $config[$name]['options']['name'];
        $z = new Zend_Queue($adapter, $config[$name]['options']);
        $z->createQueue($queueName);
        return $z;
    }

    /**
     * Returns all of the config.
     */
    static public function getConfig() {
        $context = sfContext::getInstance();

        require($context->getConfiguration()->getConfigCache()->checkConfig('config/queue.yml'));

        if (!isset($config)) {
            throw new \Exception('Error loading configuration');
        }

        return $config;
    }

    /**
     * Public constructor.  This returns an instance of sfLucene configured to the specifications
     * of the search.yml files.
     *
     * @param string $name The name of the index
     * 
     * @return sfZendQueue
     */
    static public function getInstance($name) {

        if (!isset(self::$instances[$name])) {
            if (!isset(self::$instances[$name])) {
                self::$instances[$name] = array();
            }

            self::$instances[$name] = self::create($name);
        }

        return self::$instances[$name];
    }

}
