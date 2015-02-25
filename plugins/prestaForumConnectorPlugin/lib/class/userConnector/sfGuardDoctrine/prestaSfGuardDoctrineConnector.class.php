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
 * prestaSfGuardPropelConnector is the user connector for sfGuardPropel
 * @author cdolivet
 *
 */
class prestaSfGuardDoctrineConnector extends prestaAbstractUserConnector
{
	
	/**
	 * setup function calle don construction
	 * 
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.0 - 6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @since	6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @see		prestaForumConnectorPlugin/lib/class/userConnector/prestaAbstractUserConnector#setup()
	 */
	public function setup()
	{
		$this->params	= array_merge( array(
			'getUsernameMethod'		=> 'getUsername',
			'getEmailMethod'		=> 'getEmail',
			'getIsActiveMethod'		=> 'getIsActive',
			'getCultureMethod'		=> 'getCulture',
 			'setUsernameMethod'		=> 'setUsername',
			'setEmailMethod'		=> 'setUsername',
			'setPasswordlMethod'	=> 'setPassword',
			'setIsActiveMethod'		=> 'setIsActive',
      
		), $this->params );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserNickName($projectUserId)
	{
		$user		= Doctrine::getTable('sfGuardUser')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getUsernameMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserEmail($projectUserId)
	{
		$user		= Doctrine::getTable('sfGuardUser')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getEmailMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserCulture($projectUserId)
	{
		$user		= Doctrine::getTable('sfGuardUser')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getCultureMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function isUserEnabled($projectUserId)
	{
		$user		= Doctrine::getTable('sfGuardUser')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getIsActiveMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getCurrentUserId()
	{
		/**
		 * TODO
		 */
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function isAuthenticated()
	{
		/**
		 * TODO
		 */
	}
	
	/**
	 * Get all user's id
	 * 
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.0 - 9 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @since	9 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @return	Array of user ids
	 */
	public function getAllUserId()
	{
		$a_userIds	= array();
		$q			= Doctrine_Query::create()->from('sfGuardUser u')->select('u.id');
  		foreach( $q->fetchArray() as $line )
		{
			$a_userIds[]	= $line['id'];
		}
		return $a_userIds;
	}
}