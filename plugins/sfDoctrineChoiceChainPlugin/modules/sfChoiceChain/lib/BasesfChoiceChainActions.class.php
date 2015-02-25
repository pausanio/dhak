<?php

/**
 * sfDoctrineChoiceChainPlugin base actions class
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage modules
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */

class BasesfChoiceChainActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
	if($request->isXmlHttpRequest()){
		$params = $request->getParameter('sf_choice_chain');
		$num = intval($params['num']);
		$chain = unserialize($params['chain']);
                $ChainItem = $chain[$num];
                
		$next = $num + 1;
		if(!isset($chain[$next])){
			//$this->setTemplate('stop');
		}else{
                        $NextChainItem = $chain[$next];
			$field_name = $ChainItem['field_name'];

			$options = sfChoiceChainUtil::getChainItemOptions($chain, $next, array($field_name => $params['value']));

                        $add_empty = $NextChainItem['add_empty'];
                        
                        $viewOptions = array(
                            'options' => $options
                        );
                        if($add_empty !== false){
                            $viewOptions['add_empty'] = $add_empty;
                        }
                        $this->renderPartial('options', $viewOptions);
			
		}
		return sfView::NONE;
		
	}
  }
}


?>