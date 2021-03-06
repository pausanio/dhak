<?php

/**
 * MyVerzeichnungseinheitTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MyVerzeichnungseinheitTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object MyVerzeichnungseinheitTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('MyVerzeichnungseinheit');
    }

    public static function findByUserAndId($userId, $veId){
        return self::getInstance()
            ->createQuery()
            ->where('created_by = ?', (int)$userId)
            ->andWhere('verzeichnungseinheit_id = ?', (int)$veId)
            ->fetchOne();
    }
}
