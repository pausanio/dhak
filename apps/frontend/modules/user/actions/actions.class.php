<?php

/**
 * user actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage user
 * @author     Norman Fiedler
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class userActions extends myActions
{

  public function preExecute()
  {
    $this->getRequest()->setAttribute('content_in_footer', true);
  }

  public function executeIndex(sfWebRequest $request)
  {
    return;
  }

  public function executeSupporter(sfWebRequest $request)
  {

    $this->setMetaTitle('Unterstützer');
    $this->setMetaDescription('Unterstützer des DHASK');
    $this->getRequest()->setAttribute('layout', 'footer');

    $this->ha_supporters = Doctrine_Core::getTable('sfGuardUserProfile')
        ->createQuery('p')
        ->select('p.*, u.*')
        ->from('sfGuardUserProfile p')
        ->leftJoin('p.User u')
        ->where('person_support = ?', 1)
        ->andWhere('p.status=?', 1)
        ->orderBy('last_name')
        ->execute();

    $this->cms_text = cmsText::getTextByLanguage('unterstuetzer');
    $this->cms_partner = cmsText::getTextByLanguage('partner');
    $content = $this->getPartial('supporter', array('ha_supporters' => $this->ha_supporters, 'cms_text' => $this->cms_text, 'cms_partner' => $this->cms_partner));
    $this->content = $this->getPartial('home/footer', array('active' => 'supporter', 'big' => true, 'footer_content' => $content));
  }

}
