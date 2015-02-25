<?php

/**
 * PatenobjektPhoto
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektPhoto extends BasePatenobjektPhoto
{

  public function save(Doctrine_Connection $conn = null)
  {
    #if ($this->isModified()) {
    if ($this->isNew()) {
      $this->generateThumbnail();
    }

    return parent::save($conn);
  }

  /**
   * Generate a thumbnail in upload dir under png format
   */
  public function generateThumbnail()
  {
    $sourceFile = sfConfig::get('app_photos_patenobjekt_org') . $this->getFilename();

    // use imagemagic
    #$thumbnail = new sfThumbnail(150, 150, true, true, 75, 'sfImageMagickAdapter');

    // thumbnail
    $thumbnail = new sfThumbnail(150, 150);
    $thumbnail->loadFile($sourceFile);
    $thumbnail->save(sfConfig::get('app_photos_patenobjekt_thumb') . $this->getFilename(), 'image/png');

    // large
    $thumbnail = new sfThumbnail(400, 533);
    $thumbnail->loadFile($sourceFile);
    $thumbnail->save(sfConfig::get('app_photos_patenobjekt_large') . $this->getFilename(), 'image/png');

    return true;
  }

}
