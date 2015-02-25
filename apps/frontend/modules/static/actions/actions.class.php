<?php

/**
 * static actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage static
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class staticActions extends myActions
{

    public function executeIndex(sfWebRequest $request)
    {
        $this->setMetaTitle('Informationen');
        $this->setMetaDescription();
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
    }

    public function executeInfocms(sfWebRequest $request)
    {
        $route = $request->getParameter('routename');
        $this->page = Doctrine_Core::getTable('CmsInfo')->findOneByRouteName($route);
        $this->forward404Unless($this->page);
    }

    public function executeInfo(sfWebRequest $request)
    {
        $this->pagename = $request->getParameter('pagename');
        $this->paragraph = $request->getParameter('paragraph');
        $pagename = $request->getParameter('pagename') . '/' . $request->getParameter('paragraph');
        $this->cms_text = cmsText::getTextByLanguage($pagename);
        $this->cms_text_top = cmsText::getTextByLanguage('home');
        $this->cms_text_navi = cmsText::getTextByLanguage('info_' . $this->pagename);
        $this->getRequest()->setAttribute('is_info_page', true);
    }

    public function executeTeam(sfWebRequest $request)
    {
        $this->setMetaTitle('Das Team');
        $this->setMetaDescription('Das Team des Historisches Archiv der Stadt Köln und des digitalen Historische Archiv Kölns');
        $this->getRequest()->setAttribute('layout', 'footer');

        $dir = sfConfig::get('sf_web_dir') . '/images';
        $hastk_images = array_map('basename', (glob($dir . "/content/team_??hastk*jpg")));
        $dhak_images = array_map('basename', (glob($dir . "/content/team_??dhak*jpg")));
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('team', array('cms_text' => $this->cms_text, 'hastk_images' => $hastk_images, 'dhak_images' => $dhak_images));
        $this->content = $this->getPartial('home/footer', array('active' => 'team', 'big' => true, 'footer_content' => $content));
    }

    public function executeLinks(sfWebRequest $request)
    {
        $this->setMetaTitle('Links');
        $this->setMetaDescription('Weiterführende Quellen und Literatur, sowie Links zu Institutionen. ');
        $this->getRequest()->setAttribute('layout', 'footer');

        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('links', array('cms_text' => $this->cms_text));
        $this->content = $this->getPartial('home/footer', array('active' => 'links', 'big' => true, 'footer_content' => $content));
    }

    public function executeImprint(sfWebRequest $request)
    {
        $this->setMetaTitle('Impressum');
        $this->setMetaDescription('');
        $this->getRequest()->setAttribute('layout', 'footer');

        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('imprint', array('cms_text' => $this->cms_text));
        $content .= '<h4>Datenschutz-Hinweise</h4><iframe frameborder="no" width="600px" height="200px" src="http://piwik.historischesarchivkoeln.de/index.php?module=CoreAdminHome&action=optOut&language=de"></iframe>';
        $this->content = $this->getPartial('home/footer', array('active' => 'legal', 'big' => true, 'footer_content' => $content));
    }

    public function executeContact(sfWebRequest $request)
    {
        $this->setMetaTitle('Kontakt');
        $this->setMetaDescription('');
        $this->getRequest()->setAttribute('layout', 'footer');
        $this->cms_text = cmsText::getTextByLanguage($request->getParameter('pagename'));
        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('contact', array('cms_text' => $this->cms_text));
        $this->content = $this->getPartial('home/footer', array('active' => 'contact', 'big' => true, 'footer_content' => $content));
    }

}
