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
class prestaSfGuardDoctrineConnectorMock extends prestaSfGuardDoctrineConnector implements prestaUserConnectorMockInterface
{
	/**
	 * Add a user
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-29 - ylybliamay
	 * @since	1.0 - 2009-10-29 - ylybliamay
	 * @param 	$nickname
	 * @param 	$email
	 * @param 	$password
	 * @param 	$active
	 * @return	userId
	 */
	public function addUserTest($nickname, $email, $password, $active = 1)
	{
		$user = new sfGuardUser();
		call_user_func( array($user, $this->params['setUsernameMethod']), $nickname );
		call_user_func( array($user, $this->params['setEmailMethod']), $email );
		call_user_func( array($user, $this->params['setPasswordlMethod']), $password );
		call_user_func( array($user, $this->params['setIsActiveMethod']), $active );
		$user->save();
		return $user->getId();
	}
}