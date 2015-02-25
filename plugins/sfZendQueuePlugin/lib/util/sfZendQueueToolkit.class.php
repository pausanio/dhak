<?php
/**
 * Some standard tools for the sfZendQueue package.
 *
 * @package    sfZendQueuePlugin
 * @subpackage Utilities
 * @author     Ivo Bathke
 * @author     Carl Vondrick <carl@carlsoft.net>
 */

class sfZendQueueToolkit
{
  /**
    * Loads the Zend libraries. This method *must* be called before
    * you use a Zend library, otherwise the autoloader will not be able to find it!
  */
  static public function loadZend()
  {
    static $setup;

    if ($setup !== true)
    {
      $vendor = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor';
      $vendor = sfConfig::get('app_lucene_zend_location', $vendor);

      $lucene = $vendor . DIRECTORY_SEPARATOR . 'Zend' . DIRECTORY_SEPARATOR . 'Queue.php';

      if (!is_readable($lucene))
      {
        throw new sfLuceneException('Could not open Zend_Queue for inclusion at: ' . $vendor);
      }

      // let PHP find the Zend libraries.
      set_include_path(get_include_path() . PATH_SEPARATOR . $vendor);

      require_once $lucene;

      $setup = true;
    }
  }
}