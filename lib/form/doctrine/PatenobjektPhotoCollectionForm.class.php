<?php

/**
 * PatenobjektPhoto collection form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektPhotoCollectionForm extends sfForm
{

  public function configure()
  {
    if (!$patenobjekt = $this->getOption('patenobjekt')) {
      throw new InvalidArgumentException('You must provide a patenobjekt object.');
    }

    for ($i = 0; $i < $this->getOption('size', 1); $i++) {
      $patenobjektPhoto = new PatenobjektPhoto();
      $patenobjektPhoto->Patenobjekt = $patenobjekt;
      $form = new PatenobjektPhotoForm($patenobjektPhoto);
      $this->embedForm($i, $form);
    }

    $this->mergePostValidator(new PatenobjektPhotoValidatorSchema());
  }

}