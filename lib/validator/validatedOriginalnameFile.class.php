<?php

/**
 * Custom filename validator
 *
 * @author Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class ValidatedOriginalnameFile extends sfValidatedFile
{

    /**
     * Generates a filename for the current file.
     *
     * @return string [urlized_filename]_[uniqid].[extension]
     */
    public function generateFilename()
    {
        //info: $this->getExtension($this->getOriginalExtension();

        $file = basename($this->getOriginalName());
        $filename = Doctrine_Inflector::urlize(substr($file, 0, strrpos($file, '.')));
        $extension = substr($file, strrpos($file, '.') + 1);
        #$filename = $filename . '_' . uniqid() . '.' . $extension;
        $filename = $filename . '_' . time() . '.' . $extension;

        return $filename;
    }

}

