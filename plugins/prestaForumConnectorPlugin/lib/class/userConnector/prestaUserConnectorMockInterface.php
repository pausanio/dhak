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
 * Mock interface for user connector
 * @author ylybliamay
 *
 */
interface prestaUserConnectorMockInterface
{
	/**
	 * Add a user
	 * @param 	$nickname
	 * @param 	$email
	 * @param 	$password
	 * @param 	$active
	 * @return	user id
	 */
	public function addUserTest($nickname, $email, $password, $active = true );
}