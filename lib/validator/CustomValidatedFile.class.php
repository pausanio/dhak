<?php
/**
 * Custom Filename for uploaded files
 *
 * @package    historischesarchivkoeln.de
 * @subpackage validator
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class customValidatedFile extends sfValidatedFile
{

  /**
   * Generates a non random filename
   *
   * @return string A non random name to represent the current file
   */
  public function generateFilename()
  {
    $ext = strtolower($this->getExtension($this->getOriginalExtension()));
    $name = self::urlize(substr($this->getOriginalName(), 0, - strlen($ext)));

    $filename = self::urlize($name . $ext);

    $i = 1;
    while (file_exists($this->getPath() . '/' . $filename)) {
      $filename = self::urlize($name . '-' . $i . $ext);
      $i++;
    }
    return $filename;
  }

  /**
   * based on Doctrine_Inflector::urlize (without strtolower)
   */
  public function urlize($text)
  {
    // Remove all non url friendly characters with the unaccent function
    $text = Doctrine_Inflector::unaccent($text);

    // Remove all none word characters
    $text = preg_replace('/[^\w\.\_\-]/', ' ', $text);
    $text = str_replace(' ', '-', $text);

    return trim($text, '-');
  }

}