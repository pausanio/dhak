<?php

/**
 * doc actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage doc
 * @author     Maik Mettenheimer
 */
/**
 * Install PSR-0-compatible class autoloader
 */
spl_autoload_register(function($class) {
            require_once (sfConfig::get('sf_lib_dir') . '/vendor/Markdown.php');
        });

use \Michelf\Markdown;

class docActions extends sfActions
{

    public function executeUser(sfWebRequest $request)
    {
        $this->docs = $this->getDocs(sfConfig::get('app_docs_user'));
    }

    public function executeDev(sfWebRequest $request)
    {
        $this->docs = $this->getDocs(sfConfig::get('app_docs_dev'));
    }

    protected function getDocs($dir)
    {
        $docs = array();

        if ($dir_handle = opendir($dir)) {
            while (false !== ($file = readdir($dir_handle))) {
                $info = new SplFileInfo($file);
                if ($info->getExtension() == 'md' || $info->getExtension() == 'markdown') {
                    $file_handle = fopen($dir . $file, 'r');
                    $docs[] = array(
                        'file' => $info->getBasename(),
                        'anker' => Doctrine_Inflector::unaccent($info->getBasename('.' . $info->getExtension())),
                        'title' => trim(fgets($file_handle)),
                        'last_modified' => date("d.m.Y", filemtime($dir . $file)),
                        'content' => Markdown::defaultTransform(file_get_contents($dir . $file)),
                    );
                    fclose($file_handle);
                }
            }
            closedir($dir_handle);
        }

        return $docs;
    }

}

