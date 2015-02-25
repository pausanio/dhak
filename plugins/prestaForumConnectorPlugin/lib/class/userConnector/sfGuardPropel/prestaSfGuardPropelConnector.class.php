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
 * @author ylybliamay
 *
 */
class prestaSfGuardPropelConnector extends prestaAbstractUserConnector
{
	
	/**
	 * setup function calle don construction
	 * 
	 * @author	Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @version	1.0 - 6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @since	6 nov. 2009 - Christophe Dolivet <cdolivet@prestaconcept.net>
	 * @see prestaForumConnectorPlugin/lib/class/userConnector/prestaAbstractUserConnector#setup()
	 */
	public function setup()
	{
		$this->params	= array_merge( array(
			'getUsernameMethod'		=> 'getUsername',
			'getEmailMethod'		=> 'getEmail',
			'getCultureMethod'		=> 'getCulture',
			'getIsActiveMethod'		=> 'getIsActive',
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
		$user 		= sfGuardUserPeer::retrieveByPK($projectUserId);
		return call_user_func( array( $user, $this->params['getUsernameMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserEmail($projectUserId)
	{
		$user 		= sfGuardUserPeer::retrieveByPK($projectUserId);
		return call_user_func( array( $user, $this->params['getEmailMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function getUserCulture($projectUserId)
	{
		$user		= sfGuardUserPeer::retrieveByPK( $projectUserId );
		return call_user_func( array( $user, $this->params['getCultureMethod'] ) );
	}
	
	/*
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 */
	public function isUserEnabled($projectUserId)
	{
		$user = sfGuardUserPeer::retrieveByPK($projectUserId);
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
	 * Get all user id
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-28 - ylybliamay
	 * @since	1.0 - 2009-10-28 - ylybliamay
	 * @return	array
	 */
	public function getAllUserId()
	{
		$c = new Criteria();
		$c->clearSelectColumns();
		$c->addSelectColumn(sfGuardUserPeer::ID);

		$stmt = sfGuardUserPeer::doSelectStmt($c);
		
		$result = array();
		while($row = $stmt->fetch(PDO::FETCH_NUM))
		{
			$result[] = $row[0];
		}
		return $result;
	}
}