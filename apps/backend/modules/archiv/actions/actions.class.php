<?php

require_once dirname(__FILE__) . '/../lib/archivGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/archivGeneratorHelper.class.php';

/**
 * archiv actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archiv
 * @author     Maik Mettenheimer
 */
class archivActions extends autoArchivActions
{

    /**
     * Render the whole Tree
     */
    public function executeSimpletree()
    {
        $this->treeObject = Doctrine_Core::getTable('Archiv')->getTree();
    }

    /**
     * List Tree
     *
     * @param sfWebRequest $request
     */
    public function executeTree(sfWebRequest $request)
    {

        // current archiv id
        $this->current = $request->getParameter('id', false);
        $this->query = $request->getParameter('query', false);

        if (false === $this->current) {
            $this->current = Archiv::getRoot();
        }
        if ($this->currentArchiv = Doctrine_Core::getTable('Archiv')->findOneById($this->current)) {
            $this->path = array();
            if ($this->ancestors = $this->currentArchiv->getNode()->getAncestors()) {
                foreach ($this->ancestors->toArray() as $ancestor) {
                    $this->path[] = $ancestor['id'];
                }
            }
            // add current node, for rendering
            array_push($this->path, $this->current);
            $q = Doctrine_Core::getTable('Archiv')->createQuery('c')
                    ->andWhere('c.lft BETWEEN ' . $this->currentArchiv->getNode()->getLeftValue() . ' and ' . $this->currentArchiv->getNode()->getRightValue())
                    ->orWhere('c.level <= ?', $this->currentArchiv->getNode()->getLevel())
                    ->orderBy('c.lft');
            //könnte man wahrscheinlich auch mit $tree->setBaseQuery($query); machen
            $this->tree = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
        } else {
            $this->tree = false;
        }

        $this->bestand = Doctrine_Core::getTable('Bestand')
                ->createQuery()
                ->where('bestand_sig = ?', $this->currentArchiv->getSignatur())
                ->fetchOne();

        $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')
                ->createQuery()
                ->where('archiv_id = ?', $this->currentArchiv->getId())
                ->execute();

        $this->dokuments = Doctrine_Core::getTable('Dokument')
                ->createQuery()
                ->where('archiv_id = ?', $this->currentArchiv->getId())
                ->execute();
    }

    public function executeDelete(sfWebRequest $request)
    {
        die('Die Loeschfunktion ist derzeit noch nicht implementiert...');
    }

}

