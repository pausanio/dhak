<?php

/**
 * Listener for the Signable behavior which automatically sets the created_by
 * and updated_by columns when a record is inserted and updated.
 *
 * @package     sfDoctrineActAsSignablePlugin
 * @subpackage  listener
 * @since       1.0
 * @author      Vitaliy Tverdokhlib <new2@ua.fm>
 * @author      Tomasz Ducin <tomasz.ducin@gmail.com>
 */
class Doctrine_Template_Listener_Signable extends Doctrine_Record_Listener
{

  /**
   * Array of Signable options
   *
   * @var array
   */
  protected $_options = array();

  /**
   * __construct
   *
   * @param array $options
   * @return void
   */
  public function __construct(array $options)
  {
    $this->_options = $options;
  }

  /**
   * preInsert
   *
   * @param object $Doctrine_Event.
   * @return void
   */
  public function preInsert(Doctrine_Event $event)
  {
    if (!$this->_options['created']['disabled'])
    {
      $createdName = $this->_options['created']['name'];
      $event->getInvoker()->$createdName = $this->getUserId('created');
    }

    if (!$this->_options['updated']['disabled'] && $this->_options['updated']['onInsert'])
    {
      $updatedName = $this->_options['updated']['name'];
      $event->getInvoker()->$updatedName = $this->getUserId('updated');
    }
  }

  /**
   * preUpdate
   *
   * @param object $Doctrine_Event.
   * @return void
   */
  public function preUpdate(Doctrine_Event $event)
  {
    if (!$this->_options['updated']['disabled'])
    {
      $updatedName = $this->_options['updated']['name'];
      $event->getInvoker()->$updatedName = $this->getUserId('updated');
    }
  }

  /**
   * getUserId
   *
   * Gets the userid or username depending on field format
   *
   * @param string $type.
   * @return void
   */
  public function getUserId($type)
  {
    // null in CLI mode
    if (0 != strncasecmp(PHP_SAPI, 'cli', 3))
    {
      $options = $this->_options[$type];
      if ($options['expression'] !== false && is_string($options['expression']))
      {
        return new Doctrine_Expression($options['expression']);
      }
      elseif (!class_exists("pakeApp"))
      {
        switch ($options['type'])
        {
          case 'integer':
            if (class_exists('sfGuardUser'))
            {
              return sfContext::getInstance()->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser');
            }
            else
            {
              return sfContext::getInstance()->getUser()->getId();
            }
            break;
          case 'string':
            return sfContext::getInstance()->getUser()->getUsername();
            break;
          default:
            return 'n/a';
            break;
        }
      }
    }
    else
    {
      return null;
    }
  }
}
