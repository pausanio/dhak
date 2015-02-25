<?php

/**
 * Patenobjekt filter form.
 *
 * @see http://symfony-world.blogspot.com/2010/01/custom-admin-generator-filter-example.html
 *
 * @package    historischesarchivkoeln.de
 * @subpackage filter
 * @author     Maik Mettenheimer
 * @since      2012-03-11
 */
class PatenobjektFormFilter extends BasePatenobjektFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema ['typefilter'] =
        new sfWidgetFormChoice(array(
          #'choices' => Doctrine::getTable('Patenobjekt')->getTypes()
          'choices' => array(
            '' => '',
            1 => 'Sammelpatenschaften',
            3 => 'Mit Pinsel und Skalpell',
            4 => 'Dicke Bretter bohren'
            )));
    $this->validatorSchema ['typefilter'] =
        new sfValidatorPass();

    $this->widgetSchema ['statusfilter'] =
        new sfWidgetFormChoice(array(
          'choices' => array(
            '' => '',
            1 => 'public',
            0 => 'intern'
            )));

    $this->validatorSchema ['statusfilter'] =
        new sfValidatorPass();
  }

  public function addTypefilterColumnQuery($query, $field, $value)
  {
    Doctrine::getTable('Patenobjekt')
        ->addFilterQuery($query, $value, 'type');
  }

  public function addStatusfilterColumnQuery($query, $field, $value)
  {
    Doctrine::getTable('Patenobjekt')
        ->addFilterQuery($query, $value, 'status');
  }

}
