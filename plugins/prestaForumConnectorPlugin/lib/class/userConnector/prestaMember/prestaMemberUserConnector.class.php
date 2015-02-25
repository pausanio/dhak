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
class prestaMemberUserConnector extends prestaAbstractUserConnector
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
			'getUsernameMethod'		=> 'getLogin',
			'getEmailMethod'		=> 'getEmail',
			'getIsActiveMethod'		=> 'isActive',
			'getCultureMethod'		=> 'getCulture',
 			'setUsernameMethod'		=> 'setLogin',
			'setEmailMethod'		=> 'setEmail',
			'setPasswordlMethod'	=> 'setPassword',
			'setIsActiveMethod'		=> 'validate',
      
		), $this->params );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserNickName($projectUserId)
	{
		$user		= Doctrine::getTable('prestaMember')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getUsernameMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserEmail($projectUserId)
	{
		$user		= Doctrine::getTable('prestaMember')->find($projectUserId);
		return call_user_func( array( $user, $this->params['getEmailMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserCulture($projectUserId)
	{
		$user		= Doctrine::getTable('prestaMember')->find($projectUserId);
		return false;
		// TODO récupération de la culture
		//return call_user_func( array( $user, $this->params['getCultureMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function isUserEnabled($projectUserId)
	{
		$user		= Doctrine::getTable('prestaMember')->find($projectUserId);
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
		$q			= Doctrine_Query::create()->from('prestaMember m')->select('m.id_member');
  		foreach( $q->fetchArray() as $line )
		{
			$a_userIds[]	= $line['id_member'];
		}
		return $a_userIds;
	}
}