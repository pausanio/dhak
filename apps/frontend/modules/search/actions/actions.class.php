<?php

/**
 * archive actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage archive
 * @author     Norman Fiedler / Maik Mettenheimer
 */
class searchActions extends myActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $method_name = 'execute' . $request->getParameter('topic');
    if ($request->isXmlHttpRequest()) {
      return $this->$method_name($request);
    } else {

    }
  }

  public function executeVerzeinheitenByBestand($request)
  {
    $user_params = $this->getUser()->getAttribute('ve_search');

    if (!is_null($request->getParameter('count'))) {
      $user_params['count'] = $request->getParameter('count');
    }
    if (!is_null($request->getParameter('page'))) {
      $user_params['page'] = $request->getParameter('page');
    }
    if (!is_null($request->getParameter('term'))) {
      $user_params['term'] = $request->getParameter('term');
    }
    $this->getUser()->setAttribute('ve_search', $user_params);
    $pager = new sfDoctrinePager(
            'HaVerzeinheiten',
            $user_params['count']
    );

    $pager->setQuery(HaVerzeinheiten::searchByBestandSig(
            $request->getParameter('bestand_sig'), $request->getParameter('term'), $user_params['count']
        ));
    $pager->setPage($user_params['page'], 1);
    $pager->init();

    $data = $pager->getResults();


    return $this->renderPartial('ajax_ve_search_list', array('request' => $request, 'verzeinheiten' => $data, 've_pager' => $pager, 've_page' => $user_params['page']));
  }

}