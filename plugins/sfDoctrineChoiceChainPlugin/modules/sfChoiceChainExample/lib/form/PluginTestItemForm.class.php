<?php

/**
 * TestItem form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PluginTestItemForm extends BaseTestItemForm
{
  public function configure()
  {
      $this->useFields(array('name'));

      $chain_categories = array(
            'Category', 'SubCategory', array(
                'model' => 'SubSubCategory',
                'table_method' => 'tmGetItems',
                'required' => false
            )
      );
      $this->widgetSchema['categories'] = new sfWidgetFormDoctrineChoiceChain(array(
        'chain' => $chain_categories,
          'item_template' => '%widget%'
      ));
      $this->validatorSchema['categories'] = new sfValidatorChoiceChain(array(
        'chain' => $chain_categories
      ));



      $chain_location = array(
          array(
              'model' => 'DbCountry',
              'field_name' => 'id_country',
              'add_empty' => false
          ),
          array(
              'model' => 'DbRegion',
              'field_name' => 'id_region'
          ),
          array(
              'model' => 'DbCity',
              'field_name' => 'id_city'
          ));
      $this->widgetSchema['location'] = new sfWidgetFormDoctrineChoiceChain(array(
          'chain' => $chain_location,
          'item_template' => '<td><strong>%label%<span class="star">*</span></strong>: %widget%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>',
          'global_template' => '<table cellpadding="0" cellspacing="0"><tr>%items%</tr></table>'
      ));
      $this->validatorSchema['location'] = new sfValidatorChoiceChain(array(
          'chain' => $chain_location
      ));

      $chain_location2 = array(array(
          'model' => 'DbCountry',
          'field_name' => 'id_country',
          'label' => 'Country 2',
          'required' => false,
          'add_empty' => '--optional--',
          'orderBy' => 'country_name_en ASC'
      ), array(
          'model' => 'DbCity',
          'field_name' => 'id_city',
          'label' => 'City 2',
          'required' => false,
          'add_empty' => '--optional--',
          'orderBy' => 'city_name_en ASC'
      ));
      $this->widgetSchema['location2'] = new sfWidgetFormDoctrineChoiceChain(array(
          'chain' => $chain_location2,
          'template' => '%DbCountry% :: %DbCity%',
          'item_template' => '%label%: %widget%'
      ));
      $this->validatorSchema['location2'] = new sfValidatorChoiceChain(array(
          'chain' => $chain_location2
      ));



  }


  public function updateObject($values = null)
  {
      $object = parent::updateObject($values);
      $values = $this->getValues();
      if(isset($values['location'])){
          foreach($values['location'] as $key => $value){
              $object->set($key, $value);
          }
      }
      if(isset($values['location2'])){
          foreach($values['location2'] as $key => $value){
              $object->set($key . '2', $value);
          }
      }
      if(isset($values['categories'])){
          foreach($values['categories'] as $key => $value){
              $object->set($key, $value);
          }
      }
      return $object;
  }

  public function updateDefaultsFromObject()
  {
      parent::updateDefaultsFromObject();
      $object = $this->getObject();
      if($this->isNew()){
        $this->setDefault('categories', array(
            'category_id' => 1, 'sub_category_id' => 1
        ));
        $this->setDefault('location', array(
           'id_country' => 22
        ));
      }else{
         $this->setDefault('location', array(
            'id_country' => $object->getIdCountry(),
            'id_region' => $object->getIdRegion(),
            'id_city' => $object->getIdCity()
         ));

         $this->setDefault('location2', array(
            'id_country' => $object->getIdCountry2(),
            'id_city' => $object->getIdCity2()
         ));

         $this->setDefault('categories', array(
            'category_id' => $object->getCategoryId(),
            'sub_category_id' => $object->getSubCategoryId(),
            'sub_sub_category_id' => $object->getSubSubCategoryId()
         ));

      }

  }

    //Enables separate rendering, see lines 56, 57, 58 in sfChoiceChainExample/templates/_form.php
    public function offsetGet($name)
    {
        if (!isset($this->formFields[$name]))
        {
          if (!$widget = $this->widgetSchema[$name])
          {
            throw new InvalidArgumentException(sprintf('Widget "%s" does not exist.', $name));
          }

          if ($this->isBound)
          {
            $value = isset($this->taintedValues[$name]) ? $this->taintedValues[$name] : null;
          }
          else if (isset($this->defaults[$name]))
          {
            $value = $this->defaults[$name];
          }
          else
          {
            $value = $widget instanceof sfWidgetFormSchema ? $widget->getDefaults() : $widget->getDefault();
          }

          //line 165 is the only difference!
          $class = $widget instanceof sfWidgetFormSchema ? 'sfFormFieldSchema' : 'sfFormFieldExtended';

          $this->formFields[$name] = new $class($widget, $this->getFormFieldSchema(), $name, $value, $this->errorSchema[$name]);
        }

        return $this->formFields[$name];
    }

}
