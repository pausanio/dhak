<?php
// initializes testing framework
$sf_root_dir = realpath(dirname(__FILE__).'/../../../../');

// initialize database manager
require_once($sf_root_dir.'/test/bootstrap/unit.php');

// start tests
$t = new lime_test(31);

// these tests check for the tags attachement consistency
$t->diag('::cleanTagName');

$tag = TaggableToolkit::cleanTagName('');
$t->is($tag, '', 'empty string');

$tag = TaggableToolkit::cleanTagName('test');
$t->is($tag, 'test', 'single tag');

$tag = TaggableToolkit::cleanTagName('test1,test2');
$t->is($tag, 'test1 test2', 'double tag whitespace');

$tag = TaggableToolkit::cleanTagName('test1, test2');
$t->is($tag, 'test1  test2', 'double tag with whitespace');

$t->todo('test optional option parameter');


$t->diag('::explodeTagString');

$tag = TaggableToolkit::explodeTagString('');
$t->is($tag, '', 'empty string');

$tag = TaggableToolkit::explodeTagString('test1');
$t->is($tag, 'test1', 'single tag');

$tag = TaggableToolkit::explodeTagString('test1 test2');
$t->is($tag, 'test1 test2', 'single tag with whitespace');

$tag = TaggableToolkit::explodeTagString('test1,test2');
$t->is($tag, array('test1','test2'), 'double tag');

$tag = TaggableToolkit::explodeTagString(' test1  , test2');
$t->is($tag, array('test1','test2'), 'double dirty tag');

$tag = TaggableToolkit::explodeTagString(' test1  ,
    test2');
$t->is($tag, array('test1','test2'), 'double extra dirty tag');

$tag = TaggableToolkit::explodeTagString(', test1  ,
    ');
$t->is($tag, array('test1'), 'single extra dirty tag');


$t->diag('::extractTriple');

$tag = TaggableToolkit::extractTriple('test1');
$t->is($tag, array('test1', null, null, null), 'no triple');

$tag = TaggableToolkit::extractTriple('namespace:key=value');
$t->is($tag, array('namespace:key=value', 'namespace', 'key', 'value'), 'correct triple');

$tag = TaggableToolkit::extractTriple('namespace:=value');
$t->is($tag, array('namespace:=value', null, null, null), 'empty key');

$tag = TaggableToolkit::extractTriple(':=value');
$t->is($tag, array(':=value', null, null, null), 'empty namespace, key');

$tag = TaggableToolkit::extractTriple(':=');
$t->is($tag, array(':=', null, null, null), 'empty namespace, key, value');

$tag = TaggableToolkit::extractTriple('1_incorrect_namespace:key=value');
$t->is($tag, array('1_incorrect_namespace:key=value', null, null, null), 'incorrect namespace');

$tag = TaggableToolkit::extractTriple('namespace:1_incorrect_key=value');
$t->is($tag, array('namespace:1_incorrect_key=value', null, null, null), 'incorrect key');


$t->diag('::formatTagString');

$tag = TaggableToolkit::formatTagString('dog');
$t->is($tag, '"dog"', 'single tag string');

$tag = TaggableToolkit::formatTagString('dog,cat,bird');
$t->is($tag, '"bird", "cat" and "dog"', 'multi tag string');

$tag = TaggableToolkit::formatTagString('dog, cat, bird,');
$t->is($tag, '"bird", "cat" and "dog"', 'not cleaned multi tag string');

$tag = TaggableToolkit::formatTagString(array('dog', 'cat', 'bird'));
$t->is($tag, '"bird", "cat" and "dog"', 'simple tag array');


class Doctrine
{
  public static function isValidModelClass($model)
  {
    if (is_object($model))
    {
      $model = get_class($model);
    }

    return in_array($model, array('ValidModel', 'InValidModel'));
  }

  public static function getTable($model)
  {
    return call_user_func(array($model, 'getTable'));
  }
}

class ValidModel
{
  public static function getTable()
  {
    return new self();
  }

  public function hasTemplate($template)
  {
    return true;
  }
}

class InValidModel
{
  public static function getTable()
  {
    return new self();
  }

  public function hasTemplate($template)
  {
    return false;
  }
}

$t->diag('::isTaggable');

$t->ok(TaggableToolkit::isTaggable('ValidModel'), 'valid model name');
$t->ok(TaggableToolkit::isTaggable(new ValidModel('ValidModel')), 'valid model object');

$t->ok(!TaggableToolkit::isTaggable('InValidModel'), 'invalid model name');
$t->ok(!TaggableToolkit::isTaggable(new InValidModel('InValidModel')), 'invalid model object');

try
{
  TaggableToolkit::isTaggable('MyClass');
  $t->fail('no exception for no doctrine model name');
}
catch(Exception $e)
{
  $t->pass('no doctrine model name');
}

class MyClass{}

try
{
  TaggableToolkit::isTaggable(new MyClass());
  $t->fail('no exception for no doctrine model class');
}
catch(Exception $e)
{
  $t->pass('no doctrine model class');
}


$t->diag('::normalize');
$t->todo('test normalize');


$t->diag('::triplify');

$tags = array('peter', 'wolf');
array_walk($tags, 'TaggableToolkit::triplify', 'namespace:key');
$t->is($tags, array('namespace:key=peter', 'namespace:key=wolf'), 'simple tags');