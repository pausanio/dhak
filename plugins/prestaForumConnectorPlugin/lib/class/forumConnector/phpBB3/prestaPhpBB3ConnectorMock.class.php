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
 * @author ylybliamay
 *
 */
class prestaPhpBB3ConnectorMock extends prestaPhpBB3Connector implements prestaForumConnectorMockInterface
{
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-29 - ylybliamay
	 * @since	1.0	- 2009-10-29 - ylybliamay
	 */
	public function convertMailAddressToNickName($address)
	{
		return parent::convertMailAddressToNickName($address);
	}
	
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-29 - ylybliamay
	 * @since	1.0	- 2009-10-29 - ylybliamay
	 */
	public function nickNameAlreadyUse($nickname, $forumUserId = 0)
	{
		return parent::nickNameAlreadyUse($nickname, $forumUserId);
	}
	
	/**
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-29 - ylybliamay
	 * @since	1.0	- 2009-10-29 - ylybliamay
	 */
	public function projectUserExist($projectUserId)
	{
		return parent::projectUserExist($projectUserId);
	}
}