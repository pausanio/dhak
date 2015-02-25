<?php

/**
 * ArchivTable
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @since      2012-03-24
 */
class ArchivTable extends Doctrine_Table
{

    /**
     * Status types
     *
     * @var type
     */
    static public $status = array(
        '0' => 'Inaktiv',
        '1' => 'Aktiv'
    );

    public function getStatus()
    {
        return self::$status;
    }

    /**
     * Returns an instance of this class.
     *
     * @return object ArchivTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Archiv');
    }

    static public function getLuceneIndex()
    {
        ProjectConfiguration::registerZend();

        if (file_exists($index = self::getLuceneIndexFile())) {
            return Zend_Search_Lucene::open($index);
        }

        return Zend_Search_Lucene::create($index);
    }

    static public function getLuceneIndexFile()
    {
        return sfConfig::get('sf_data_dir') . '/archiv.index';
    }

    public function getForLuceneQuery($query)
    {
        $hits = self::getLuceneIndex()->find($query);
        $pks = array();
        foreach ($hits as $hit) {
            $pks[] = $hit->pk;
        }

        if (empty($pks)) {
            return false;
        }

        return $this->createQuery('a')
                        ->whereIn('a.id', $pks)
        //TODO join Bestand for highlighting
        ;
    }

}

