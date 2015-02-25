<?php

/**
 * sfDoctrineChoiceChainPlugin widget class
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage lib
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */

class sfWidgetFormDoctrineChoiceChain extends sfWidgetForm{

        protected $count = 0;
        protected $entity_num = null;

        protected static $entity_number = 0;
        protected $add_data_rendered = false;

        protected $chain = null;

        public function configure($options = array(), $attributes = array()){

            if(!isset($options['chain'])){
                throw new sfException('Please, specify chain');
            }

            $this->addOption('chain', null);
            $this->addOption('label_from_model', true);
            $this->addOption('item_template', '%label%%widget%');
            $this->addOption('global_template', '%items%');
            $this->addOption('template', '');

            self::$entity_number++;
            $this->entity_num = self::$entity_number;

            $this->chain = sfChoiceChainUtil::prepareChain($options['chain']);


            parent::configure($options, $attributes);

        }


        public function render($name, $value = null, $attributes = array(), $errors = array())
        {
                $str = "";
                if($value == null){
                        $value = array();
                }
                $chain = $this->chain;
                $template = $this->getOption('template');

                if($template){
                    $replacements = array();
                    foreach($chain as $ChainItem){
                        $replacements['%' . $ChainItem['model'] . '%'] = $this->renderItem($name, $value, $ChainItem, $attributes);
                    }
                    return strtr($template, $replacements);
                }else{
                    foreach($chain as $ChainItem){
                            $str .= $this->renderItem($name, $value, $ChainItem, $attributes);
                    }
                    return strtr($this->getOption('global_template'), array(
                            '%items%' => $str
                    ));
                }
        }

        protected function renderItem($name, $value, $ChainItem, $attributes = array())
        {
                $label = $ChainItem['label'];
                $field_name = $ChainItem['field_name'];
                $model = $ChainItem['model'];
                $num = $this->_getChainItemNum($ChainItem);

                $options = $this->_getChainItemOptions($num - 1, $value);
                $add_empty = $ChainItem['add_empty'];
                $choices = $this->_prepareChoices($options, $add_empty);

                $w = new sfWidgetFormChoice(array('choices' => $choices));
                $v = isset($value[$field_name]) ? $value[$field_name] : null;
                $w_rendered = $w->render($name . '[' . $field_name . ']', $v, array(
                    'class' => "sf_choice_chain_item sf_choice_chain_item_model-{$model} sf_choice_chain_item_num-{$num} sf_choice_chain_item-{$this->entity_num}"
                ));
                $str = strtr($this->getOption('item_template'), array(
                        '%label%' => $label,
                        '%widget%' => $w_rendered
                ));
                if(!$this->add_data_rendered){
                    $str .= $this->renderChain();
                    $str .= $this->renderUrl();
                    $str .= $this->renderJs();
                    sfContext::getInstance()->getResponse()->addJavascript('/sfDoctrineChoiceChainPlugin/js/sfWidgetFormDoctrineChoiceChain.js');
                    $this->add_data_rendered = true;
                }
                return $str;
        }


        protected function renderModel($model, $name, $value, $attributes = array())
        {

            if($ChainItem = $this->getChainItemByModel($model)){

                return $this->renderItem($name, $value, $ChainItem, $attributes);
            }else{
                throw new sfException(sprintf('Unknown model %s in %s.', $model, get_class($this)));
            }
        }

        protected function renderChain(){
                $chain = serialize($this->chain);
                return "<input type='hidden' class='sf_choice_chain_item sf_choice_chain_chain sf_choice_chain_item-{$this->entity_num}' value='{$chain}' />";
        }

        protected function renderUrl(){
                $configuration = sfContext::getInstance()->getConfiguration();
                $configuration->loadHelpers(array('Url'));
                $url = url_for('@sf_choice_chain');
                return "<input type='hidden' class='sf_choice_chain_item sf_choice_chain_url sf_choice_chain_item-{$this->entity_num}' value='{$url}'/>";
        }
        protected function renderJs()
        {
            $add_empty = $this->getOption('add_empty');
            $str = "<script type='text/javascript'>
                        if(!window.sfWidgetFormChoiceChainOptions){
                            sfWidgetFormChoiceChainOptions = {};
                        }
                        sfWidgetFormChoiceChainOptions[{$this->entity_num}] = {
                            add_empty: {}
                        };
                        ";
             for($i = 0; $i < count($this->chain); $i++){
                 $num = $i + 1;
                 $ChainItem = $this->chain[$i];
                 $add_empty = $ChainItem['add_empty'];
                 $str .= "sfWidgetFormChoiceChainOptions[{$this->entity_num}].add_empty[{$num}] = '{$add_empty}';";
             }
             $str .= "
                    </script>";
             return $str;
        }



        protected function _getChainItemNum($ChainItem)
        {
            $chain = $this->chain;
            for($i = 0; $i < count($chain); $i++){
                $Item = $chain[$i];
                if($Item['model'] == $ChainItem['model']){
                    return $i+1;
                }
            }
            throw new sfException('Undefined number Chain Item number - ' . 0);
        }

        protected function getChainItemByModel($model)
        {
            for($i = 0; $i < count($this->chain); $i++){
                if($this->chain[$i]['model'] == $model){
                    return $this->chain[$i];
                }
            }
            return false;
        }


        protected function _getChainItemOptions($num, $value){
                return sfChoiceChainUtil::getChainItemOptions(
                        $this->chain,
                        $num,
                        $value
                );
        }

        protected function _prepareChoices($options, $add_empty = ''){
                if(is_array($options)){
                        return $options;
                }
                $choices = array();
                if($add_empty !== false){
                    $choices = array('' => $add_empty);
                }
                if($options instanceof Doctrine_Collection){
                        foreach($options as $option){
                                $choices[$option->getPrimaryKey()] = $option->__toString();
                        }
                        return $choices;
                }
                return $choices;
        }
        public function __call($method, $arguments)
        {
            if(preg_match('/^render/', $method)){
                $model = preg_replace('/^render/', '', $method);
                list($name, $value, $attributes) = $arguments;
                return $this->renderModel($model, $name, $value, $attributes);
            }
            throw new sfException(sprintf('Call to undefined method %s::%s.', get_class($this), $method));
        }

}