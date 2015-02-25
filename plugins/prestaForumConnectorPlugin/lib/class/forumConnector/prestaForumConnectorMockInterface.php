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
 * Mock interface for forum connector
 * @author 	ylybliamay
 *
 */
interface prestaForumConnectorMockInterface
{
	/**
	 * Override the protected method ::convertMailAddressToNickName()
	 * @param	$address
	 * @return 	string
	 */
	public function convertMailAddressToNickName($address);
	
	/**
	 * Override the protected method ::nickNameAlreadyUse()
	 * @param	$nickname
	 * @param 	$forumUserId
	 * @return 	boolean
	 */
	public function nickNameAlreadyUse($nickname, $forumUserId = 0);
	
	/**
	 * Override the protected method ::projectUserExist()
	 * @param 	$projectUserId
	 * @return	boolean
	 */
	public function projectUserExist($projectUserId);
}