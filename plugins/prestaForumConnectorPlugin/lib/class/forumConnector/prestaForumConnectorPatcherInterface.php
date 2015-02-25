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
 * Patcher interface for forum connector
 * 
 * @author 	cdolivet
 */
interface prestaForumConnectorPatcherInterface
{
	/**
	 * Patch the forum database, configuration and file
	 * @return	boolean
	 */
	public function patchForum( sfBaseTask $sfTask );
}