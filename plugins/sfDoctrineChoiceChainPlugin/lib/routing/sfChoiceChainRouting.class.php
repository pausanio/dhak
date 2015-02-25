<?php

/**
 * sfDoctrineChoiceChainPlugin base actions class
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage lib
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */

	class sfChoiceChainRouting{
		public static function addRoutes(sfEvent $event){
		    $event->getSubject()->prependRoute('sf_choice_chain', new sfRoute('/sfchoicechain/util', array('module' => 'sfChoiceChain', 'action' => 'index'))); 			
		}
	}

?>