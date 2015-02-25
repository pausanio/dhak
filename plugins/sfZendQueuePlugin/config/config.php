<?php

/**
 * @package sfzendQueuePlugin
 */

/**
 * Add configuration handlers for queue.yml
 */
$configCache = sfProjectConfiguration::getActive()->getConfigCache();
$configCache->registerConfigHandler('config/queue.yml', 'sfZendQueueProjectConfigHandler');
