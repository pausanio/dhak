<?php

require_once dirname(__FILE__) . '/../lib/verzeichnungseinheitGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/verzeichnungseinheitGeneratorHelper.class.php';

/**
 * verzeichnungseinheit actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage verzeichnungseinheit
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class verzeichnungseinheitActions extends autoVerzeichnungseinheitActions
{

  public function executeDelete(sfWebRequest $request)
  {
    die('Die Loeschfunktion ist derzeit noch nicht implementiert...');
  }

}
