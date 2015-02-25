<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfFormFieldExtended
 *
 * @author Алекс
 */
class sfFormFieldExtended extends sfFormField{
    //put your code here

    public function __call($method, $arguments){
        if(preg_match('/^render/', $method)){
            $argument = isset($arguments[0]) ? $arguments[0] : array();
            $name = $this->parent ? $this->parent->getWidget()->generateName($this->name) : $this->name;
            return $this->widget->{$method}($name, $this->value, $argument);
        }
        throw new sfException(sprintf('Call to undefined method %s::%s.', get_class($this), $method));
    }
}
?>
