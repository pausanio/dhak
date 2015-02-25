<?php
/*
 * This file is part of the prestaForumConnectorPlugin package.
 * (c) Christophe DOLIVET <cdolivet@prestaconcept.net>
 * (c) Mikael RANDY <mrandy@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * prestaPhpBB3Connector is the forum connector for PhpBB3
 * @author ylybliamay
 *
 */
class prestaPhpBB3ConnectorPatcher extends prestaPhpBB3Connector implements prestaForumConnectorPatcherInterface
{
	

	/**
	 * This method allow us to patch the forum in order to install the prerequist
	 * for the plugin uses
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-27 - ylybliamay
	 * @since	1.0 - 2009-10-27 - ylybliamay
	 */
	public function patchForum( sfBaseTask $sfTask )
	{
		// Set general config
		$this->patchGeneralConfig( $sfTask );
		
		// Add the custom data in order to create a link between project and forum database
		$this->patchAddCustomField( $sfTask );
		// Disable user profile edition (can't change email or password)
		$this->patchDisableUserProfileEdition( $sfTask );
		
		$this->patchDisableRegistration( $sfTask );
		
		// Delete links and form for log in from the forum
		$this->patchDisableLogin( $sfTask );
		
		$sfTask->logSection( "Clear file cache", null, null, $this->clearCache() ? 'INFO' : 'ERROR' );
	}
	
	/**
	 * Add a custom field
	 * @author	ylybliamay
	 * @version	1.1 - 12 avr. 2011 - Sylvain Blatrix <sblatrix@prestaconcept.net>
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 * @param sfBaseTask $sfTask
	 * @return 	boolean
	 */
	public function patchAddCustomField( sfBaseTask $sfTask )
	{
		$field	= $this->params['forumFieldProjectUserId'];
		
		// Check if this field already exist
		$sql	= "SELECT field_id FROM ".$this->dbprefix."profile_fields WHERE field_name = '". $field ."'";
		$result	= $this->sqlExec($sql);
		$exist	= is_array( $this->db->sql_fetchrow($result) );
		if(!$exist)
		{
			$sql = "INSERT INTO ".$this->dbprefix."profile_fields (field_name, field_type, field_ident, field_length, field_minlen, field_maxlen, field_novalue, field_default_value, field_validation, field_required, field_show_on_reg, field_show_profile, field_hide, field_no_view, field_active, field_order )
			 VALUES ( '". $field ."', 1, '". $field ."', '10', '0', '0', '0', '0', '', 0, 0, 0, 1, 1, 1, 1)";
			$succeed	= $this->sqlExec($sql);
		}
		$sfTask->logSection( 'Database', 'Add custom field - part 1', null, $exist || $succeed ? 'INFO' : 'ERROR' );
		
		// Check if the field already create in the profile_fields_data table
		$sql = "SELECT column_name FROM information_schema.COLUMNS WHERE table_name = '".$this->dbprefix."profile_fields_data' AND column_name = 'pf_". $field ."'";
		
		$result = $this->sqlExec($sql);
		$exist	= is_array( $this->db->sql_fetchrow($result) );
		if(!$exist)
		{
			$sql = "ALTER TABLE ".$this->dbprefix."profile_fields_data ADD pf_". $field ." bigint";
			$succeed	= $this->sqlExec($sql);
		}
		$sfTask->logSection( 'Database', 'Add custom field - part 2', null, $exist || $succeed ? 'INFO' : 'ERROR' );
	}
	
	/**
	 * Deactivate the forum registration
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 */
	protected function patchDisableRegistration( sfBaseTask $sfTask )
	{
		$sql = "UPDATE ". $this->dbprefix ."config SET config_value = 3 WHERE config_name = 'require_activation'";
		$sfTask->logSection( 'Database', 'Disable registration', null, $this->sqlExec($sql) ? 'SUCCEED' : 'FAILURE' );
	}
	
	/**
	 * Disable user profile edition
	 * 
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.1 - 12 avr. 2011 - Sylvain Blatrix <sblatrix@prestaconcept.net>
	 * @since	6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @param	sfBaseTask $sfTask
	 */
	protected function patchDisableUserProfileEdition( sfBaseTask $sfTask )
	{
		$sql = "UPDATE ". $this->dbprefix ."modules SET module_enabled = '0' WHERE module_langname = 'UCP_PROFILE_REG_DETAILS';";
		$sfTask->logSection( 'Database', 'Disable user profile edition', null, $this->sqlExec($sql) ? 'SUCCEED' : 'FAILURE' );
	}
	
	/**
	 * Deactivate login in the forum
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 * @return	boolean
	 */
	protected function patchDisableLogin( sfBaseTask $sfTask )
	{
		
		// *************
		// *** Disable logout link
		// *************
		
		$replace	= '<!-- IF S_USER_LOGGED_IN  --><li class="icon-logout">{L_LOGIN_LOGOUT}</li><!-- ENDIF -->';
		// >= 3.0.6
		if( version_compare($this->getConfigVal('version'), '3.0.6', '>=' ) )
		{
			$search	= '<li class="icon-logout"><a href="{U_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}" accesskey="x">{L_LOGIN_LOGOUT}</a></li>';
		}
		// < 3.0.6
		else
		{
			$search	= '<li class="icon-logout"><a href="{U_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}" accesskey="l">{L_LOGIN_LOGOUT}</a></li>';
		}
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'styles/prosilver/template/overall_header.html', $sfTask );
		
		// *************
		
			
		// *************
		// *** Disable login form
		// *************
		
		$search	= <<<EOF
<!-- IF not S_USER_LOGGED_IN and not S_IS_BOT -->

		<form action="{S_LOGIN_ACTION}" method="post">

		<div class="panel">
			<div class="inner"><span class="corners-top"><span></span></span>

			<div class="content">
				<h3><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a><!-- IF S_REGISTER_ENABLED -->&nbsp; &bull; &nbsp;<a href="{U_REGISTER}">{L_REGISTER}</a><!-- ENDIF --></h3>

				<fieldset class="fields1">
				<dl>
					<dt><label for="username">{L_USERNAME}:</label></dt>
					<dd><input type="text" tabindex="1" name="username" id="username" size="25" value="{USERNAME}" class="inputbox autowidth" /></dd>
				</dl>
				<dl>
					<dt><label for="password">{L_PASSWORD}:</label></dt>
					<dd><input type="password" tabindex="2" id="password" name="password" size="25" class="inputbox autowidth" /></dd>
					<!-- IF S_AUTOLOGIN_ENABLED --><dd><label for="autologin"><input type="checkbox" name="autologin" id="autologin" tabindex="3" /> {L_LOG_ME_IN}</label></dd><!-- ENDIF -->
					<dd><label for="viewonline"><input type="checkbox" name="viewonline" id="viewonline" tabindex="4" /> {L_HIDE_ME}</label></dd>
				</dl>
				<dl>
					<dt>&nbsp;</dt>
					<dd><input type="submit" name="login" tabindex="5" value="{L_LOGIN}" class="button1" /></dd>
				</dl>
				{S_LOGIN_REDIRECT}
				</fieldset>
			</div>

			<span class="corners-bottom"><span></span></span></div>
		</div>

		</form>

	<!-- ENDIF -->
EOF;
		$this->searchAndReplace( $search, '<!-- /* form removed */ -->', $this->phpbb_root_path.'styles/prosilver/template/viewforum_body.html', $sfTask );
		
		// *************
		
		
		// *************
		// *** disable login form
		// *************
		
		// >= 3.0.6
		if( version_compare($this->getConfigVal('version'), '3.0.6', '>=' ) )
		{
			$search	= <<<EOF
<!-- IF not S_USER_LOGGED_IN and not S_IS_BOT -->
	<form method="post" action="{S_LOGIN_ACTION}" class="headerspace">
	<h3><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a><!-- IF S_REGISTER_ENABLED -->&nbsp; &bull; &nbsp;<a href="{U_REGISTER}">{L_REGISTER}</a><!-- ENDIF --></h3>
		<fieldset class="quick-login">
			<label for="username">{L_USERNAME}:</label>&nbsp;<input type="text" name="username" id="username" size="10" class="inputbox" title="{L_USERNAME}" />
			<label for="password">{L_PASSWORD}:</label>&nbsp;<input type="password" name="password" id="password" size="10" class="inputbox" title="{L_PASSWORD}" />
			<!-- IF S_AUTOLOGIN_ENABLED -->
				| <label for="autologin">{L_LOG_ME_IN} <input type="checkbox" name="autologin" id="autologin" /></label>
			<!-- ENDIF -->
			<input type="submit" name="login" value="{L_LOGIN}" class="button2" />
			{S_LOGIN_REDIRECT}
		</fieldset>
	</form>
<!-- ENDIF -->
EOF;
		}
		// <= 3.0.5
		else
		{
			$search	= <<<EOF
<!-- IF not S_USER_LOGGED_IN and not S_IS_BOT -->
	<form method="post" action="{S_LOGIN_ACTION}" class="headerspace">
	<h3><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a><!-- IF S_REGISTER_ENABLED -->&nbsp; &bull; &nbsp;<a href="{U_REGISTER}">{L_REGISTER}</a><!-- ENDIF --></h3>
		<fieldset class="quick-login">
			<label for="username">{L_USERNAME}:</label>&nbsp;<input type="text" name="username" id="username" size="10" class="inputbox" title="{L_USERNAME}" />  
			<label for="password">{L_PASSWORD}:</label>&nbsp;<input type="password" name="password" id="password" size="10" class="inputbox" title="{L_PASSWORD}" />
			<!-- IF S_AUTOLOGIN_ENABLED -->
				| <label for="autologin">{L_LOG_ME_IN} <input type="checkbox" name="autologin" id="autologin" /></label>
			<!-- ENDIF -->
			<input type="submit" name="login" value="{L_LOGIN}" class="button2" />
		</fieldset>
	</form>
<!-- ENDIF -->
EOF;
		}
		$this->searchAndReplace( $search, '<!-- /* form removed */ -->', $this->phpbb_root_path.'styles/prosilver/template/index_body.html', $sfTask );
		
		// *************
		
		
		// *************
		// *** disable login body page
		// *************
	
		// >= 3.0.6
		if( version_compare($this->getConfigVal('version'), '3.0.6', '>=' ) )
		{
			$search	= <<<EOF
		<h2><!-- IF LOGIN_EXPLAIN -->{LOGIN_EXPLAIN}<!-- ELSE -->{L_LOGIN}<!-- ENDIF --></h2>

		<fieldset <!-- IF not S_CONFIRM_CODE -->class="fields1"<!-- ELSE -->class="fields2"<!-- ENDIF -->>
EOF;
		}
		// <= 3.0.5
		else
		{
			$search	= <<<EOF
		<h2><!-- IF LOGIN_EXPLAIN -->{LOGIN_EXPLAIN}<!-- ELSE -->{L_LOGIN}<!-- ENDIF --></h2>
		
		<fieldset <!-- IF not S_CONFIRM_CODE -->class="fields1"<!-- ELSE -->class="fields2"<!-- ENDIF -->>
EOF;
		}
$replace	= <<<EOF
		<h2><!-- IF LOGIN_EXPLAIN -->{LOGIN_EXPLAIN}<!-- ELSE -->{L_LOGIN}<!-- ENDIF --></h2>	
	<!-- IF S_ADMIN_AUTH  -->
		<fieldset <!-- IF not S_CONFIRM_CODE -->class="fields1"<!-- ELSE -->class="fields2"<!-- ENDIF -->>
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'styles/prosilver/template/login_body.html', $sfTask );
		
$search	= <<<EOF
		</fieldset>
	</div>
EOF;
$replace	= <<<EOF
		</fieldset>
	<!-- ENDIF -->	
	</div>
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'styles/prosilver/template/login_body.html', $sfTask );		
		
		// *************		
		
		
		// *************
		// *** disable login and logout actions
		// *************
		
		$search	= <<<EOF
// Basic "global" modes
switch (\$mode)
{
EOF;
		$replace	= <<<EOF
// login and logout are disabled
if( \$mode == 'login' || \$mode == 'logout')
{
	die;
}

// Fixed Basic "global" modes
switch(\$mode)
{
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'ucp.php', $sfTask );
		
		// *************
	
		// *************
		// *** synch session with website one
		// *************
		
		$search		= <<<EOF
		// if session id is set
		if (!empty(\$this->session_id))
		{
			\$sql = 'SELECT u.*, s.*
				FROM ' . SESSIONS_TABLE . ' s, ' . USERS_TABLE . " u
				WHERE s.session_id = '" . \$db->sql_escape(\$this->session_id) . "'
					AND u.user_id = s.session_user_id";
			\$result = \$db->sql_query(\$sql);
			\$this->data = \$db->sql_fetchrow(\$result);
			\$db->sql_freeresult(\$result);

			// Did the session exist in the DB?
EOF;

		$replace	= <<<EOF
		if( class_exists( 'sfConfig' ) )
		{
			\$projectUserId	= sfConfig::get('projectUserId');
		}
		
		// if session id is set
		if (!empty(\$this->session_id))
		{
			\$sql = 'SELECT u.*, s.*
				FROM ' . SESSIONS_TABLE . ' s, ' . USERS_TABLE . " u
				WHERE s.session_id = '" . \$db->sql_escape(\$this->session_id) . "'
					AND u.user_id = s.session_user_id";
			\$result = \$db->sql_query(\$sql);
			\$this->data = \$db->sql_fetchrow(\$result);
			\$db->sql_freeresult(\$result);
			
			if( class_exists( 'sfConfig' ) )
			{
				\$projectUserId	= sfConfig::get('projectUserId');
				\$forumUserId	= is_array( \$this->data ) && array_key_exists('user_id',\$this->data) ? \$this->data['user_id'] : 1;
				if(!empty(\$projectUserId) && ( empty(\$forumUserId) || \$forumUserId == 1 ) )
				{
					prestaForumFactory::getForumConnectorInstance()->signIn( \$projectUserId );
					header('Location: '.\$_SERVER['REQUEST_URI']);die;
				}
				else if(empty(\$projectUserId) && ( !empty(\$forumUserId) && \$forumUserId != 1 ) )
				{
					prestaForumFactory::getForumConnectorInstance()->signOut( prestaForumFactory::getForumConnectorInstance()->getProjectUserIdFromForumUserId( \$forumUserId ) );
					header('Location: '.\$_SERVER['REQUEST_URI']);die;
				}
			}
			
			// Did the session exist in the DB?
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'includes/session.php', $sfTask );
		
		// ************* 
	}
	
	/**
	 * Set the general config for the forum (config.php)
	 * 
	 * @author	ylybliamay
	 * @version	1.1 - 13 avr. 2011 - Sylvain Blatrix <sblatrix@prestaconcept.net>
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 * @param 	sfBaseTask $sfTask
	 */
	protected function patchGeneralConfig( sfBaseTask $sfTask )
	{
		$search		= null;
		$replace	= <<<EOF
<?php
/**
 * Set PHPBB3 config value according to the environment
 * @author	ylybliamay
 * @return	array
 */
function getConfigEnvironment()
{
	\$currentDir				= preg_replace('@^(.*?)/([^/]*)$@', '$2', dirname(__FILE__) );
	\$result['script_path']		= array_key_exists('REQUEST_URI',\$_SERVER) 	? substr(\$_SERVER['REQUEST_URI'],0,strpos(\$_SERVER['REQUEST_URI'], \$currentDir) + strlen( \$currentDir )) : '';
	\$result['server_name']		= array_key_exists('HTTP_HOST',\$_SERVER)	? \$_SERVER['HTTP_HOST'] : '';
	\$result['cookie_domain']	= array_key_exists('HTTP_HOST',\$_SERVER) 	? \$_SERVER['HTTP_HOST'] : '';
	return \$result;
}

/**
 * In order to get Symfony sf_user, whe should get the sf_user from the instance.
 * But according to the referer application (symfony or forum), you should create or only get the instance.
 */

@define('SYMFONY_FORUM', true);

require dirname(__FILE__).'/../index.php';

\$instanceCreated	= false;

if( !sfContext::hasInstance() )
{
	\$instanceCreated	= true;
	\$instance			= sfContext::createInstance(\$configuration);
	// notify a special event for possible customization
	\$instance->getEventDispatcher()->notify( new sfEvent( \$instance, 'prestaForumConnector.initContextInstanceFromForum' ) );
}

\$sf_user	= sfContext::getInstance()->getUser();
\$sf_user_id =  method_exists( \$sf_user, 'getUserId' ) ? \$sf_user->getUserId() : 0;
if(\$sf_user_id > 0)
{
	sfConfig::set('projectUserId', \$sf_user_id );
}
if( \$instanceCreated )
{
	\$instance->shutdown();
}

\$databaseManager	= new sfDatabaseManager( \$configuration );
\$sfDatabase 		= \$databaseManager->getDatabase( sfConfig::get('app_prestaForumConnector_forumDatabaseId' ) );

\$dsn = \$sfDatabase->getParameter('dsn');
\$dsn = explode(':',\$dsn);
// phpBB 3.0.x auto-generated configuration file
// Do not change anything in this file!
\$dbms	= \$dsn[0];

// pgSQL compatibility
if(\$dbms == 'pgsql')
{
	\$dbms = 'postgres';
}

\$dsn	= explode(';',\$dsn[1]);
\$dsn_dbname	= explode('=',\$dsn[0]);
\$dsn_dbhost	= explode('=',\$dsn[1]);

\$dbhost 			= \$dsn_dbhost[1];
\$dbport 			= '';
\$dbname 			= \$dsn_dbname[1];
\$dbuser 			= \$sfDatabase->getParameter('username');
\$dbpasswd 			= \$sfDatabase->getParameter('password');
\$table_prefix 		= '$this->dbprefix';
\$acm_type 			= 'file';
\$load_extensions	= '';

@define('PHPBB_INSTALLED', true);
		
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'config.php', $sfTask );
		
		// *************
		// *** acm_file.php
		// *************
		
		// >= 3.0.6
		if( version_compare($this->getConfigVal('version'), '3.0.6', '>=' ) )
		{
			$this->searchAndReplace(
				'fwrite($handle, "\n" . $this->var_expires[$var] . "\n");',
				'fwrite($handle, "\n" . ( array_key_exists( $var, $this->var_expires ) ? $this->var_expires[$var] : time() + 31536000 ) . "\n");',
				$this->phpbb_root_path.'includes/acm/acm_file.php',
				$sfTask
			);
			
			$search	= <<<EOF
			if (\$filename == 'data_global')
			{
				// Global data is a different format
EOF;
			$replace	= <<<EOF
			if (\$filename == 'data_global')
			{
				\$this->vars = array_merge(\$this->vars,getConfigEnvironment());
				// Global data is a different format
EOF;
		}
		// <= 3.0.5
		else
		{
			$search	= <<<EOF
		if (\$fp = @fopen(\$this->cache_dir . 'data_global.' . \$phpEx, 'wb'))
		{
			@flock(\$fp, LOCK_EX);
EOF;
			$replace	= <<<EOF
		if (\$fp = @fopen(\$this->cache_dir . 'data_global.' . \$phpEx, 'wb'))
		{
			\$this->vars = array_merge(\$this->vars,getConfigEnvironment());
			@flock(\$fp, LOCK_EX);
EOF;
		}
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'includes/acm/acm_file.php', $sfTask );
		
		// *************


		// *************
		// *** cache.php
		// *************
		
		$search		= <<<EOF
		}

		return \$config;
EOF;
		$replace	= <<<EOF
		}
		
		\$config = array_merge(\$config,getConfigEnvironment());

		return \$config;
EOF;
		$this->searchAndReplace( $search, $replace, $this->phpbb_root_path.'includes/cache.php', $sfTask );
		
		// *************
		
		
		// *************
		// *** Upgrade admin rights
		// *************
		
		
		// Check if line already exist
		
		// boost admin rights
		$sql = "SELECT * FROM ".$this->dbprefix."acl_groups WHERE group_id = 5 AND forum_id = 0 AND auth_option_id=0 AND auth_role_id=4 AND auth_setting=0";
		$result = $this->sqlExec($sql);
		$exist	= is_array( $this->db->sql_fetchrow($result) );;
		if(!$exist)
		{
			$sql = "INSERT INTO  ". $this->dbprefix ."acl_groups (group_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('5', '0', '0', '4', '0')";
			$succeed	= $this->sqlExec($sql);
		}
		$sfTask->logSection( 'Database', 'Upgrade admin rights - 1', null, $exist || $succeed ? 'INFO' : 'ERROR' );
		
		// all admin can manage the admin group
		$sql = "UPDATE  ". $this->dbprefix ."groups SET group_founder_manage = 0 WHERE group_id = 5;";
		$succeed	= $this->sqlExec($sql);
		$sfTask->logSection( 'Database', 'Upgrade admin rights - 2', null, $succeed ? 'INFO' : 'ERROR' );
		
		// *************
	}
}
