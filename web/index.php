<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
if(!defined('SYMFONY_FORUM'))
{
    sfContext::createInstance($configuration)->dispatch();
}
