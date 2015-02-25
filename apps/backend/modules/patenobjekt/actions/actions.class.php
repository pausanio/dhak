<?php

require_once dirname(__FILE__) . '/../lib/patenobjektGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/patenobjektGeneratorHelper.class.php';

/**
 * patenobjekt actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage patenobjekt
 * @author     Maik Mettenheimer
 * @since      2012-03-12
 */
class patenobjektActions extends autoPatenobjektActions
{

  /**
   * Delete Patenobjekt
   *
   * Delete related objects and image files
   *
   * @param sfWebRequest $request
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($patenobjekt = Doctrine_Core::getTable('Patenobjekt')->find(array($request->getParameter('id'))), sprintf('Object ha_patenobjekt does not exist (%s).', $request->getParameter('id')));

    foreach ($patenobjekt->getPatenobjektPhotos() as $photo) {
      unlink(sfConfig::get('app_photos_patenobjekt_org').$photo->getFilename());
      unlink(sfConfig::get('app_photos_patenobjekt_thumb').$photo->getFilename());
      unlink(sfConfig::get('app_photos_patenobjekt_large').$photo->getFilename());
      $photo->delete();
    }
    $patenobjekt->delete();
    $this->redirect('patenobjekt/index');
  }

}
