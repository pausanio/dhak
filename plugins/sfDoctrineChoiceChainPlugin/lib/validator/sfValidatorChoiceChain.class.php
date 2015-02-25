<?php

/**
 * sfDoctrineChoiceChainPlugin validator class
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage lib
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */

	class sfValidatorChoiceChain extends sfValidatorBase{
                protected $chain = null;
		public function configure($options = array(), $messages = array()){

			$this->addOption('chain', array());
			$this->addMessage('field_required', "Field %field% is required");

                        $this->chain = sfChoiceChainUtil::prepareChain($options['chain']);

			parent::configure($options, $messages);
		}

		public function doClean($value){
			$res_val = array();
			$chain = $this->chain;
			foreach($chain as $ChainItem){
				$field_name = $ChainItem['field_name'];
				$required = $ChainItem['required'];
				if(!isset($value[$field_name]) || !$value[$field_name]){
					if($required){
                                                $params = array('field' => $ChainItem['label']);
						throw new sfValidatorError($this, 'field_required', $params);
					}
					$res_val[$field_name] = null;
				}else{
					$res_val[$field_name] = $value[$field_name];
				}
			}
			return $res_val;
		}

	}

?>