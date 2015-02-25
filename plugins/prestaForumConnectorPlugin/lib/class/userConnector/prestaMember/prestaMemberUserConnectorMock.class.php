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
 * Mock Class used for unit tests
 * @author cdolivet
 *
 */
class prestaMemberUserConnectorMock extends prestaMemberUserConnector implements prestaUserConnectorMockInterface
{	
	/**
	 * Add a user
	 * 
	 * @author	ylybliamay
	 * @version	1.1 - 19 avr. 2011 - Sylvain Blatrix <sblatrix@prestaconcept.net>
	 * @since	29 oct. 2009 - ylybliamay
	 * @see plugins/prestaForumConnectorPlugin/lib/class/userConnector/prestaUserConnectorMockInterface#addUserTest($nickname, $email, $password, $active)
	 */
	public function addUserTest($nickname, $email, $password, $active = 1)
	{
		$user = new prestaMember();
		call_user_func( array($user, $this->params['setUsernameMethod']), $nickname );
		call_user_func( array($user, $this->params['setEmailMethod']), $email );
		call_user_func( array($user, $this->params['setPasswordlMethod']), $password );
		call_user_func( array($user, $this->params['setIsActiveMethod']), $active );
		$user->save();
		return $user->getIdMember();
	}
}