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
 * Synchronize user task allow to synchronize project user and forum user
 * @author ylybliamay
 *
 */
class prestaForumConnectorPromoteUserTask extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace	= 'prestaForumConnector';
		$this->name			= 'promoteUser';
		$this->briefDescription		= 'Promote a user as administrator';
		$this->detailedDescription	= 'Promote a user as administrator';
		
		$this->addArgument('application', sfCommandArgument::REQUIRED, 'Application');
		$this->addArgument('userId',sfCommandArgument::REQUIRED, "The id of the user to synchronize (project's userId)");
   		$this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'Environnement' );
	}
	
	/**
	 * It uses the forum connector method for synchronize user
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-30 - ylybliamay
	 * @since	1.0 - 2009-10-30 - ylybliamay
	 */
	protected function execute($arguments = array(), $options = array())
	{
		$start_time	= microtime( true );
		$this->logSection( 'promoteUser', 'Start '.date('Y-m-d H:i:s') );
		
		$databaseManager = new sfDatabaseManager($this->configuration);
		
		$result	= prestaForumFactory::getForumConnectorInstance()->promoteUserAsAdmin( $this, $arguments['userId'] );
		$this->logSection( 'promoteUser', $arguments['userId'], null, $result ? 'INFO' : 'ERROR' );
		
		$this->logSection( 'promoteUser', 'End '.date('Y-m-d H:i:s') .' ( Total: '. round( ( microtime(true) - $start_time ), 2) .' s )' );
	}
}