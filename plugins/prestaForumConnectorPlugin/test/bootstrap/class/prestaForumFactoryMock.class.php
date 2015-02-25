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
 * prestaForumFactory is the only way to communicate
 * between the forum connector and the user connector
 * @author ylybliamay
 *
 */
class prestaForumFactoryMock extends prestaForumFactory
{
	
	/**
	 * Get the user connector instance
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 * @return	sfContext
	 */
	public static function getUserConnectorInstance()
	{
		$options	= sfConfig::get( 'app_prestaForumConnector_userConnector' );
		$class		= $options['class'].'Mock';

		if(!(self::$userInstance instanceof $class) )
		{
			self::$userInstance	= new $class( array_key_exists( 'param', $options ) && is_array( $options['param'] ) ? $options['param'] : array() );
		}
		
		return self::$userInstance;
	}
	
	/**
	 * Get the forum connector instance
	 * @author	ylybliamay
	 * @version	1.0 - 2009-10-26 - ylybliamay
	 * @since	1.0 - 2009-10-26 - ylybliamay
	 * @return	sfContext
	 */
	public static function getForumConnectorInstance()
	{
		$options	= sfConfig::get( 'app_prestaForumConnector_forumConnector' );
		$class		= $options['class'].'Mock';

		if(!(self::$forumInstance instanceof $class))
		{
			self::$forumInstance = new $class( array_key_exists( 'param', $options ) && is_array( $options['param'] ) ? $options['param'] : array() );
		}
		
		return self::$forumInstance;
	}
}