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
class prestaForumConnectorPrestaMemberSecurityUser extends prestaMemberUser
{
	/**
	 * Authentification
	 * 
	 * @author	ylybliamay
	 * @version	1.1 - 12 avr. 2011 - Sylvain Blatrix <sblatrix@prestaconcept.net>
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 * @see plugins/prestaMemberPlugin/lib/user/prestaMemberUser#signIn($member, $autoSignIn)
	 */
	public function signIn($member, $autoSignIn = false)
	{
		parent::signIn($member, $autoSignIn);
		prestaForumFactory::getForumConnectorInstance()->signIn($member->getIdMember());
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
		return $this->getAttribute('id_member', null, 'prestaMemberUser');
	}
}
