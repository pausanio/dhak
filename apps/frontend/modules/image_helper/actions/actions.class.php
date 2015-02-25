<?php

/**
 * news actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage news
 * @author     Norman Fiedler
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class image_helperActions extends myActions
{
  public function executeResize(sfWebRequest $request)
  {
    $size = $request->getParameter('size');
    $img_src = $request->getParameter('img_src');
    $img_type = substr($img_src, strrpos($img_src, '.')+1);
    $img_name = substr($img_src, 0, strrpos($img_src, '.'));
    //TODO: image path besser auslesen
    $img_path = $_SERVER['DOCUMENT_ROOT'].'/images/thumbs/';
    $img_file = $img_name . $size . 'x' .$size . '.' . $img_type;
    $src_image = $_SERVER['DOCUMENT_ROOT'].'/images/' . $img_src;
    //echo $img_file;

    if(!is_readable($img_path . $img_file)){
      $img = new sfImage($src_image, 'image/'.$img_type);
      $img->thumbnail($size, $size);
      $img->saveAs($img_path . $img_file);

    }
    $this->redirect('/images/thumbs/'.$img_file);
    exit;
  }

}
