<?php
/**
 * Set PHPBB3 config value according to the environment
 * @author	ylybliamay
 * @return	array
 */
function getConfigEnvironment()
{
	$currentDir				= preg_replace('@^(.*?)/([^/]*)$@', '$2', dirname(__FILE__) );
	$result['script_path']		= array_key_exists('REQUEST_URI',$_SERVER) 	? substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'], $currentDir) + strlen( $currentDir )) : '';
	$result['server_name']		= array_key_exists('HTTP_HOST',$_SERVER)	? $_SERVER['HTTP_HOST'] : '';
	$result['cookie_domain']	= array_key_exists('HTTP_HOST',$_SERVER) 	? $_SERVER['HTTP_HOST'] : '';
	return $result;
}

/**
 * In order to get Symfony sf_user, whe should get the sf_user from the instance.
 * But according to the referer application (symfony or forum), you should create or only get the instance.
 */

@define('SYMFONY_FORUM', true);

require dirname(__FILE__).'/../index.php';

$instanceCreated	= false;

if( !sfContext::hasInstance() )
{
	$instanceCreated	= true;
	$instance			= sfContext::createInstance($configuration);
	// notify a special event for possible customization
	$instance->getEventDispatcher()->notify( new sfEvent( $instance, 'prestaForumConnector.initContextInstanceFromForum' ) );
}

$sf_user	= sfContext::getInstance()->getUser();
$sf_user_id =  method_exists( $sf_user, 'getUserId' ) ? $sf_user->getUserId() : 0;
if($sf_user_id > 0)
{
	sfConfig::set('projectUserId', $sf_user_id );
}
if( $instanceCreated )
{
	$instance->shutdown();
}

$databaseManager	= new sfDatabaseManager( $configuration );
$sfDatabase 		= $databaseManager->getDatabase( sfConfig::get('app_prestaForumConnector_forumDatabaseId' ) );

$dsn = $sfDatabase->getParameter('dsn');
$dsn = explode(':',$dsn);
// phpBB 3.0.x auto-generated configuration file
// Do not change anything in this file!
$dbms	= $dsn[0];

// pgSQL compatibility
if($dbms == 'pgsql')
{
	$dbms = 'postgres';
}

$dsn	= explode(';',$dsn[1]);
$dsn_dbname	= explode('=',$dsn[0]);
$dsn_dbhost	= explode('=',$dsn[1]);

$dbhost 			= $dsn_dbhost[1];
$dbport 			= '';
$dbname 			= $dsn_dbname[1];
$dbuser 			= $sfDatabase->getParameter('username');
$dbpasswd 			= $sfDatabase->getParameter('password');
$table_prefix 		= 'phpbb_';
$acm_type 			= 'file';
$load_extensions	= '';

@define('PHPBB_INSTALLED', true);
		