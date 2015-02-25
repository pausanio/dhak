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
 * prestaAbstractForumConnector
 * @author ylybliamay
 *
 */
abstract class prestaAbstractForumConnector
{
	protected $params	= array();
	
	/**
	 * Constructor
	 * Call the setup function
	 */
	public function __construct( Array $params = array() )
	{
		$this->params	= $params;
		$this->setup();
	}
	
	/**
	 * Set the general configuration
	 * @abstract
	 */
	abstract public function setup();
	
	/**
	 * Search and replace code into a file
	 * @param 	$search
	 * @param 	$replace
	 * @param 	$file
	 * @return	int or false
	 */
	public function searchAndReplace( $search, $replace, $file, sfBaseTask $sfTask = null )
	{
		$replace			= str_replace( "\r", "", $replace );
		$succeed			= false;
		$filePrevContent	= str_replace( "\r", "", file_get_contents( $file ) );
		
		if( $search !== null )
		{
			$search				= str_replace( "\r", "", $search );
			$count				= 0;
			$fileNewContent		= str_replace( $search, $replace, $filePrevContent, $count);
			
			$alreadyPatched		= strpos( $filePrevContent, $replace ) !== false;
			if( $count == 1 )
			{
				file_put_contents( $file, $fileNewContent );
				$succeed		= true;
			}
		}
		else
		{
			$alreadyPatched	= ( $filePrevContent == $replace );
			if( !$alreadyPatched )
			{
				file_put_contents( $file, $replace );
				$succeed		= true;
			}
		}
		
		if( $sfTask !== null )
		{
			$result	= $alreadyPatched ? 'ALREADY PATCHED' : ( $succeed ? 'SUCCEED' : 'FAILURE' );
			$sfTask->logSection( 'Patch file', $file.':'.$result, null, $alreadyPatched || $succeed ? 'INFO' : 'ERROR' );
		}
		
		return $succeed;
	}
	
	/**
	 * Sign in the user
	 * @abstract
	 */
	abstract public function signIn($projectUserId, $sessionId = false);
	
	/**
	 * Sign out the user
	 * @abstract
	 */
	abstract public function signOut($projectUserId);
	
	/**
	 * Synchronize the data between project user and forum user
	 * @param $projectUserId
	 * @abstract
	 */
	abstract public function synchUser($projectUserId);
	
	/**
	 * Get the project user id from the forum user id
	 * @param 	$forumUserId
	 * @return	int
	 */
	abstract public function getProjectUserIdFromForumUserId($forumUserId);
	
	/**
	 * Get the forum user id from the project user id
	 * @param 	$projectUserId
	 * @return 	int
	 */
	abstract public function getForumUserIdFromProjectUserId($projectUserId);
	
	/**
	 * Get the forum user nickname
	 * @param 	$projectUserId
	 * @return	string
	 */
	abstract public function getUserNickName($projectUserId);
	
	/**
	 * Delete a forum User
	 * Delete a forum user
	 * @param 	$projectUserId
	 * @return 	boolean
	 */
	abstract public function deleteForumUser($projectUserId);
	
	/**
	 * Disable a forum user
	 * @param	$projectUserId
	 * @return	boolean
	 */
	abstract public function disableForumUser($projectUserId);
	
	/**
	 * Enable a forum user
	 * @param 	$projectUserId
	 * @return 	boolean
	 */
	abstract public function enableForumUser($projectUserId);
	
	
	
	/**
	 * Convert a nickname to a valid nickname
	 * @param	$projectNickName
	 * @param	$forumUserId
	 * @return	string
	 */
	abstract public function convertToForumNickName($projectNickName,$projectUserId ,$forumUserId = 0);
	
	/**
	 * Convert a mail address to a nickname if the parameter $address is a mail address
	 * 
	 * @param 	$address
	 * @return 	string
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	protected function convertMailAddressToNickName($address)
	{
		$Syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
		if(preg_match($Syntaxe,$address))
		{
			return substr($address, 0, strpos($address,'@'));
		}
		return $address;
	}
	
	/**
	 * Clear forum cache
	 * 
	 * @abstract
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.0 - 6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @since	6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @return Boolean
	 */
	abstract public function clearCache();
	
	/**
	 * Promote a user as an adminsitrator
	 * 
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.0 - 6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @since	6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @param	sfBaseTask $sfTask
	 * @param	Integer $projectUserId
	 * @return Boolean
	 */
	abstract public function promoteUserAsAdmin( sfBaseTask $sfTask, $projectUserId );
	
	/**
	 * Check if the forum user is enable
	 * @param 	$forumUserId
	 * @return 	boolean
	 */
	abstract public function forumUserIsEnabled($forumUserId);
	
	/**
	 * Check if the user is sign in
	 * @param 	$forumUserId
	 * @return 	boolean
	 */
	abstract public function isSignedIn($forumUserId);
	
//	abstract public function getCurrentUserId();

//	abstract public function isAuthenticated();
}