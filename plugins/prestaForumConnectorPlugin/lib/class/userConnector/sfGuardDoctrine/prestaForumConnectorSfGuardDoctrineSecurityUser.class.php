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
 * Security User class uses between the myUser class and the sfGuardSecurityUser
 * in order to customize the security user according to the user connector
 * @author ylybliamay
 *
 */
class prestaForumConnectorSfGuardDoctrineSecurityUser extends sfGuardSecurityUser
{
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 */
	public function signIn($user, $remember = false, $con = null)
	{
		parent::signIn( $user, $remember, $con );
		prestaForumFactory::getForumConnectorInstance()->signIn($user->getId());
	}
	
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 */
	public function signOut()
	{
		prestaForumFactory::getForumConnectorInstance()->signOut($this->getUserId());
		parent::signOut();
	}
	
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 */
	public function getUserId()
	{
		return $this->getAttribute('user_id', 0, 'sfGuardSecurityUser');
	}
}
