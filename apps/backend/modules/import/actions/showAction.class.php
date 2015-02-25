<?php

/**
 * import show action.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage import
 * @author     Ivo Bathke
 * @since      2013-02-16
 */
class showAction extends sfActions {

    /**
     * Show action
     *
     * @param type $request
     */
    public function execute($request) {
        $file = $request->getParameter('file');
        $folder = $request->getParameter('folder');
        $dir = sfConfig::get('app_import_' . $folder);
        if (!file_exists($dir . $file)) {
            $this->redirect404();
        }

        $this->setLayout(false);
        $info = pathinfo($dir . $file);
        $format = $info['extension'] ? : 'txt';
        $content = file_get_contents($dir . $file);
        $response = $this->getResponse();
        $response->setContentType('application/'.$format);
        $response->setHttpHeader('Content-Length', filesize($dir . $file));
        $response->setContent($content);
        return sfView::NONE;
    }

}