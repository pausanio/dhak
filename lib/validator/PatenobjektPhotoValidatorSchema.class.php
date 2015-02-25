<?php

/**
 * PatenobjektPhoto validator schema.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage validator
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektPhotoValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('position', 'The position is required.');
    $this->addMessage('filename', 'The filename is required.');
  }

  protected function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);

    foreach ($values as $key => $value) {
      $errorSchemaLocal = new sfValidatorErrorSchema($this);

      // filename is filled but no position
      if ($value['filename'] && !$value['position']) {
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'position');
      }

      // caption is filled but no filename
      if ($value['position'] && !$value['filename']) {
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'filename');
      }

      // no position and no filename, remove the empty values
      if (!$value['filename'] && !$value['position']) {
        unset($values[$key]);
      }

      // some error for this embedded-form
      if (count($errorSchemaLocal)) {
        $errorSchema->addError($errorSchemaLocal, (string) $key);
      }
    }

    // throws the error for the main form
    if (count($errorSchema)) {
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }

    return $values;
  }

}