<?php

/**
 * sfDoctrineChoiceChainPlugin helper class
 * 
 * @package    sfDoctrineChoiceChainPlugin
 * @subpackage lib
 * @author     Alex Radyuk <alexey.radyuk@gmail.com>
 */

class sfChoiceChainUtil{

        protected static $defaultChainItem = array(
            'model' => '%model%',
            'add_empty' => '',
            'field_name' => '%underscored model%_id',
            'table_method' => null,
            'label' => '%humanized model%',
            'orderBy' => false,

            'required' => true
        );

        public static function checkChainItemRequired($ChainItem){
            if(is_array($ChainItem)){
                    if(isset($ChainItem['required'])){
                            return $ChainItem['required'];
                    }
            }
            return false;
        }

        public static function getChainItemOptions($chain, $num, $value){
            $ChainItem = $chain[$num];
            $model = $ChainItem['model'];
            $field_name = $ChainItem['field_name'];
            $table_method = $ChainItem['table_method'];

            $table = Doctrine::getTable($model);
            if(!$num){
                    if(!$table_method){
                            $q = $table->createQuery('a');
                            if($ChainItem['orderBy']){
                                $q->orderBy('a.' . $ChainItem['orderBy']);
                            }
                            return $q->execute();
                    }else{
                            if(method_exists($table, $table_method)){
                                    return call_user_func(array($table, $table_method));
                            }else{
                                    throw new sfException('Specified table method doesnt exist!');
                            }
                    }
            }else{
                    $previous_field_name = $chain[$num - 1]['field_name'];
                    if(!isset($value[$previous_field_name]) || !$value[$previous_field_name]){
                            return false;
                    }
                    if(!$table_method){
                            $q = $table->createQuery('a')
                                ->where('a.' . $previous_field_name . ' = ?', $value[$previous_field_name]);
                            if($ChainItem['orderBy']){
                                $q->orderBy('a.' . $ChainItem['orderBy']);
                            }
                            return $q->execute();
                    }else{
                            return call_user_func(array($table, $table_method), $value);
                    }
            }
        }


        public static function prepareChain($chain){

            for($i = 0; $i < count($chain); $i++){
                $item = $chain[$i];
                $ChainItem = array();
                if(is_string($item)){
                    $item = array(
                        'model' => $item
                    );
                }
                $model = $item['model'];
                foreach(self::$defaultChainItem as $key => $value){
                    if(isset($item[$key])){
                        $ChainItem[$key] = $item[$key];
                    }else{
                        if($key == 'field_name'){
                            $ChainItem[$key] = strtr($value, array(
                               '%underscored model%' => sfInflector::underscore($model)
                            ));
                        }else if($key == 'label'){
                            $ChainItem[$key] = strtr($value, array(
                               '%humanized model%' => sfInflector::humanize($model)
                            ));
                        }else if($key == 'model'){
                            $ChainItem[$key] = $model;
                        }else{
                            $ChainItem[$key] = $value;
                        }
                    }
                }
                $chain[$i] = $ChainItem;
            }
            return $chain;

        }
}