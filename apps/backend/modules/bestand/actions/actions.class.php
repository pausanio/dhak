<?php

require_once dirname(__FILE__) . '/../lib/bestandGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/bestandGeneratorHelper.class.php';

/**
 * bestand actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage bestand
 * @author     Maik Mettenheimer
 * @since      2012-03-19
 */
class bestandActions extends autoBestandActions
{

  public function executeShow(sfWebRequest $request)
  {
    $this->bestand = Doctrine_Core::getTable('Bestand')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->bestand);

    $this->verzeichnungseinheiten = Doctrine_Core::getTable('Verzeichnungseinheit')
        ->createQuery()
        ->where('archiv_id = ?', $this->bestand->getArchivId())
        ->execute();

    $this->dokumente = Doctrine_Core::getTable('Dokument')
        ->createQuery()
        ->where('archiv_id = ?', $this->bestand->getArchivId())
        ->execute();
  }

  public function executeDelete(sfWebRequest $request)
  {
    die('Die Loeschfunktion ist derzeit noch nicht implementiert...');
  }

}