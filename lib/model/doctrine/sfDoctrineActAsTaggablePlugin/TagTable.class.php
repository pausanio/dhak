<?php

/**
 * TagTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TagTable extends PluginTagTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object TagTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Tag');
    }
}