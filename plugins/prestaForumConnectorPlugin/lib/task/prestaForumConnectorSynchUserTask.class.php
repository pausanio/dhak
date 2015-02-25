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
class prestaForumConnectorSynchUserTask extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace	= 'prestaForumConnector';
		$this->name			= 'synchUser';
		$this->briefDescription		= 'Synchronize users between the project and the forum';
		$this->detailedDescription	= 'Synchronize users between the project and the forum';
		
		$this->addArgument('application', sfCommandArgument::REQUIRED, 'Application');
		$this->addArgument('userId',sfCommandArgument::REQUIRED,'The id of the user to synchronize, or "all" to synchronize all users');
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
		$this->logSection( 'syncUser', 'Start '.date('Y-m-d H:i:s') );
		
		$databaseManager = new sfDatabaseManager($this->configuration);
		
		if($arguments['userId'] == 'all')
		{
			$results = prestaForumFactory::getUserConnectorInstance()->getAllUserId();
			foreach($results as $userId)
			{
				prestaForumFactory::getForumConnectorInstance()->synchUser($userId);
			}
			$this->logSection( 'syncUser', sprintf('%s users are synchronized', count($results) ) );
		}
		else
		{
			prestaForumFactory::getForumConnectorInstance()->synchUser($arguments['userId']);
			$this->logSection( 'syncUser', sprintf('user #%s is synchronized', $arguments['userId'] ) );
		}
		
		$this->logSection( 'syncUser', 'End '.date('Y-m-d H:i:s') .' ( Total: '. round( ( microtime(true) - $start_time ), 2) .' s )' );
	}
}