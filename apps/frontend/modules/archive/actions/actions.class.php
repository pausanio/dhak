<?php

/**
 * archive actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archive
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class archiveActions extends myActions
{

  public function preExecute()
  {
    $this->setMetaTitle('Lesesaal');
    $this->setMetaDescription();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $request->setParameter('ve_sig', str_replace('-', '/', $request->getParameter('ve_sig')));
    foreach ($request->extractParameters($request->getParameterHolder()->getNames()) as $key => $value) {
      $this->$key = $value;
    }
    $this->_setAttributesGalleryPages();
    if ($request->getParameter('limit')) {
      $this->redirect($request->getReferer());
    }
    $this->_setAttributesArchivView();
    $this->_setAttributesVeSearch();

    //Anzeigeoptionen wurden geändert
    if ($this->viewtype OR $this->viewoptions OR $this->ve_search_count) {
      $this->redirect($request->getReferer());
    }

    $this->_getSidebar($request);

    if ($request->isXmlHttpRequest()) {
      return $this->renderPartial('navigation', $this->sidebar_params);
    }

    $this->archive_level = 'tektonik';
    if ($this->tekt_nr != '0') {
      $this->archive_level = 'tektonik';
      $this->text_data = Doctrine_Core::getTable('HaTektonik')->findOneByTektNr($this->tekt_nr);
    }
    if ($this->bestand_sig != '0') {
      $this->archive_level = 'bestand';
      $this->text_data = Doctrine_Core::getTable('HaBestand2')->findOneByBestandSig($this->bestand_sig);
    }
    if ($this->ve_sig != '0') {
      $this->archive_level = 'verzeinheiten';
      $this->text_data = Doctrine_Core::getTable('HaVerzeinheiten')
          ->createQuery('a')
          ->addWhere('signatur= ?', $request->getParameter('ve_sig'))
          ->addWhere('bestand_sig= ?', $request->getParameter('bestand_sig'))
          ->execute();
    }

    $this->doc_pager = null;
    if ($this->ve_sig != '0') {
      $this->doc_pager = new sfDoctrinePager(
          'HaVerzeinheiten', $this->getUser()->getAttribute('gallery_pages', 20)
      );
      $this->doc_pager->setQuery(HaVerzeinheiten::getDocumentsBySignatur($this->bestand_sig, $this->ve_sig));
      $this->doc_pager->setPage($request->getParameter('page', 1));
      $this->doc_pager->init();

      $this->content_data = $this->doc_pager->getResults();
      $this->doc_total = $this->doc_pager->getNbResults();
    } else {
      $this->content_data = HaDocuments::getDocumentsByNewest(9, $this->tekt_nr, $this->bestand_sig, $this->ve_sig);
    }
    $this->hastk_contact = $this->_getHastkContact($this->tekt_nr);

    $this->content_params = array('archive_level' => $this->archive_level, 'text_data' => $this->text_data, 'content_data' => $this->content_data, 'doc_total' => $this->doc_total, 'doc_page' => $request->getParameter('page', 1), 'doc_pager' => $this->doc_pager,
      'tekt_nr' => $this->tekt_nr, 'bestand_sig' => $this->bestand_sig, 've_sig' => $this->ve_sig, 've_page' => $request->getParameter('ve_page', 1),
      'hastk_contact' => $this->hastk_contact);

    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->getRequest()->setAttribute('search_wrapper_class', 'archive');
  }

  public function executeShow(sfWebRequest $request)
  {
    foreach ($request->extractParameters($request->getParameterHolder()->getNames()) as $key => $value) {
      $this->$key = $value;
    }
    $this->_setAttributesArchivView();
    $this->_setAttributesVeSearch();
    $this->_getSidebar($request);
    $this->content_data = HaDocuments::getDocumentById($request->getParameter('doc_id'))->get(0);
    $this->doc_page = $request->getParameter('doc_page', 1);
    $this->hastk_contact = $this->_getHastkContact($this->tekt_nr);

    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->getRequest()->setAttribute('search_wrapper_class', 'archive');
  }

  public function executeSearch(sfWebRequest $request)
  {
    $request->setParameter('ve_sig', str_replace('-', '/', $request->getParameter('ve_sig')));

    if ($request->getParameter('archive_search_count')) {
      $this->getUser()->setAttribute('archive_search_count', $request->getParameter('archive_search_count'));
    }

    if ($request->getParameter('sortBy')) {
      $this->getUser()->setAttribute('archive_search_sort', $request->getParameter('sortBy'));
    }

    if ($request->getParameter('archive_search_count') OR $request->getParameter('archive_search_sort')) {
      $this->redirect($request->getReferer());
    }

    $query = trim($request->getParameter('query'));
    if ($query) {
      $this->doc_pager = new sfDoctrinePager(
          'HaVerzeinheiten', $this->getUser()->getAttribute('archive_search_count', 16)
      );
      $this->doc_pager->setQuery(HaDocuments::getDocumentsByQuery($query, $this->getUser()->getAttribute('archive_search_sort', null)));
      $this->doc_pager->setPage($request->getParameter('page', 1));
      $this->doc_pager->init();
      $this->content_data = $this->doc_pager->getResults();
      $this->doc_total = $this->doc_pager->getNbResults();
    } else {
      $this->content_data = array();
      $this->doc_pager = null;
      $this->doc_total = 0;
    }

    $this->query = $request->getParameter('query');

    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->getRequest()->setAttribute('search_wrapper_class', 'archive');
  }

  public function executeSearchExtended(sfWebRequest $request)
  {
    if ($request->getParameter('archive_search_count')) {
      $this->getUser()->setAttribute('archive_search_count', $request->getParameter('archive_search_count'));
      $this->redirect($request->getReferer());
    }
    $query = trim($request->getParameter('query'));
    $qo = $request->getParameter('qo');

    if (!$query AND $qo)
      $query = '%';

    if (!isset($qo['comment']))
      $qo['comment'] = null;
    if (!$query AND $qo['title']) {
      $query = $qo['title'];
    }
    if ($query OR $qo) {
      $this->doc_pager = new sfDoctrinePager(
          'HaVerzeinheiten', $this->getUser()->getAttribute('archive_search_count', 16)
      );
      $this->doc_pager->setQuery(HaDocuments::getDocumentsByQuery($request->getParameter('query'), $this->getUser()->getAttribute('archive_search_sort', null), $qo));
      $this->doc_pager->setPage($request->getParameter('page', 1));
      $this->doc_pager->init();

      $this->content_data = $this->doc_pager->getResults();
      $this->doc_total = $this->doc_pager->getNbResults();
    } else {
      $this->content_data = array();
      $this->doc_pager = null;
      $this->doc_total = 0;
    }
    $this->query = $query;
    $this->getRequest()->setAttribute('header_class', 'searchExtended');
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->getRequest()->setAttribute('search_wrapper_class', 'archive extended');
    $this->setTemplate('search');
  }

  public function executeMets(sfWebRequest $request)
  {
    $request->setParameter('ve_sig', str_replace('-', '/', $request->getParameter('ve_sig')));
    $this->data = HaVerzeinheiten::getDocumentsBySignatur($request->getParameter('bestand_sig'), $request->getParameter('ve_sig'))->execute();
  }

  public function executeTags(sfWebRequest $request)
  {
    if ($request->getParameter('archive_search_count')) {
      $this->getUser()->setAttribute('archive_search_count', $request->getParameter('archive_search_count'));
    }

    if ($request->getParameter('sortBy')) {
      $this->getUser()->setAttribute('archive_search_sort', $request->getParameter('sortBy'));
    }

    if ($request->getParameter('archive_search_count') OR $request->getParameter('archive_search_sort')) {
      $this->redirect($request->getReferer());
    }

    $query = trim($request->getParameter('tags'));
    if ($query) {
      $this->doc_pager = new sfDoctrinePager(
          'HaVerzeinheiten', $this->getUser()->getAttribute('archive_search_count', 16)
      );
      $this->doc_pager->setQuery(HaDocuments::getDocumentsByTags($query, $this->getUser()->getAttribute('archive_search_sort', null)));
      $this->doc_pager->setPage($request->getParameter('page', 1));
      $this->doc_pager->init();
      $this->content_data = $this->doc_pager->getResults();
      $this->doc_total = $this->doc_pager->getNbResults();
    } else {
      $this->content_data = array();
      $this->doc_pager = null;
      $this->doc_total = 0;
    }

    $this->query = $query;
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
    $this->getRequest()->setAttribute('search_wrapper_class', 'archive');
  }

  private function _getSidebar($request)
  {
    //die(__FILE__);
    $ve_pager = null;
    if ($this->bestand_sig != '0') {
      $ve_pager = $this->_getVerzeinheitenByBestandSig($this->bestand_sig, $request->getParameter('ve_page'), $this->ve_search['count']);
      $this->verzeinheiten = $ve_pager->getResults();
    }

    if ($request->isXmlHttpRequest()) {
      $this->navigation = HaTektonik::getNavigationDataAjax($request->getParameter('tekt_nr'));
    } else {
      $this->navigation = HaTektonik::getNavigationData($request->getParameter('tekt_nr'));
    }


    $this->sidebar_params = array('navigation' => $this->navigation, 'verzeinheiten' => $this->verzeinheiten,
      'tekt_nr' => $this->tekt_nr, 'bestand_sig' => $this->bestand_sig,
      've_sig' => $this->ve_sig, 've_pager' => $ve_pager, 've_page' => $request->getParameter('ve_page', 1));
  }

  private function _setAttributesArchivView()
  {
    $params = $this->request->getGetParameters();
    $user_params = $this->getUser()->getAttribute('archive_view');

    //defaults
    $archive_view = array('type' => (isset($user_params['type'])) ? $user_params['type'] : 'list',
      'options' => array(
        'text' => (isset($user_params['options']['text'])) ? $user_params['options']['text'] : true,
        'detail' => (isset($user_params['options']['detail'])) ? $user_params['options']['detail'] : false,
        'gallery' => (isset($user_params['options']['gallery'])) ? $user_params['options']['gallery'] : true));

    $archive_view['type'] = (isset($params['viewtype'])) ? $params['viewtype'] : $archive_view['type'];

    if (isset($params['viewoptions'])) {
      $archive_view['options'][key($params['viewoptions'])] = current($params['viewoptions']);
    }
    $this->archive_view = $archive_view;
    $this->getUser()->setAttribute('archive_view', $archive_view);
  }

  private function _setAttributesVeSearch()
  {
    $params = $this->request->getGetParameters();
    $user_params = $this->getUser()->getAttribute('ve_search');
    $ve_search = array('count' => (isset($user_params['count'])) ? $user_params['count'] : 10,
      'page' => (isset($user_params['page'])) ? $user_params['page'] : 1,
      'term' => (isset($user_params['term'])) ? $user_params['term'] : '');
    $ve_search['count'] = (isset($params['ve_search_count'])) ? $params['ve_search_count'] : $ve_search['count'];
    $ve_search['page'] = (isset($params['ve_search_page'])) ? $params['ve_search_page'] : $ve_search['page'];
    $ve_search['term'] = (isset($params['ve_search_term'])) ? $params['ve_search_term'] : $ve_search['term'];
    $this->ve_search = $ve_search;
    $this->getUser()->setAttribute('ve_search', $ve_search);
  }

  private function _setAttributesGalleryPages()
  {
    $params = $this->request->getGetParameters();
    $user_params = $this->getUser()->getAttribute('gallery_pages');
    $limit = (isset($user_params)) ? $user_params : 20;
    $limit = (isset($params['limit'])) ? $params['limit'] : $limit;
    $this->gallery_pages = $limit;
    $this->getUser()->setAttribute('gallery_pages', $limit);
  }

  private function _getVerzeinheitenByBestandSig($bestand_sig, $page, $count)
  {
    $pager = new sfDoctrinePager('HaVerzeinheiten', $count);
    $pager->setQuery(HaVerzeinheiten::getByBestandSig($bestand_sig));
    $pager->setPage($page, 1);
    $pager->init();
    return $pager;
  }

  private function _getHastkContact($tekt_nr)
  {
    $hastk_contacts = array('1' => 'Dr. Max Plassmann',
      '2' => 'Dr. Franz-Josef Verscharen',
      '3' => 'Dr. Gisela Fleckenstein',
      '4.1' => 'Dr. Max Plassmann',
      '4.2' => 'Dr. Franz-Josef Verscharen',
      '4.3' => 'Dr. Gisela Fleckenstein',
      '4.4' => 'Dr. Max Plassmann');
    $female = array('3', '4.3');
    foreach ($hastk_contacts as $t_nr => $contact) {
      if (substr($tekt_nr, 0, strlen($t_nr)) == $t_nr) {
        return array('name' => $contact, 'gender' => (in_array($t_nr, $female) ? 'female' : 'male'));
      }
    }
    return false;
  }

}