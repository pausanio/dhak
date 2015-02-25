<?php

/**
 * news actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage news
 * @author     Norman Fiedler / Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class newsActions extends myActions
{

    public function executeIndex(sfWebRequest $request)
    {

        $this->setMetaTitle('Aktuelles');
        $this->setMetaDescription('Aktuelle Meldungen des Digitalen Historischen Archivs.');
        $this->getRequest()->setAttribute('layout', 'footer');

        $query = null;

        if ($request->getParameter('tags')) {
            $query = PluginTagTable::getObjectTaggedWithQuery('HaNews', $request->getParameter('tags'));
        }

        if ($request->getParameter('archive')) {
            $query = Doctrine_Core::getTable('HaNews')
                    ->createQuery('a')
                    ->addWhere('publish_date LIKE ?', $request->getParameter('archive') . '%');
        }

        if ($request->getParameter('q')) {
            $q = '%' . $request->getParameter('q') . '%';
            $query = Doctrine_Core::getTable('HaNews')
                    ->createQuery('a')
                    ->addWhere('news_title LIKE ? OR news_text LIKE ?', array($q, $q));
        }

        if (is_null($query)) {
            $query = Doctrine_Core::getTable('HaNews')
                    ->createQuery('a');
        }

        $query->addWhere('status=1');
        $query->orderby('publish_date DESC');

        $this->pager = new sfDoctrinePager('HaNews', 10);
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        $this->id = false;
        if ($request->getParameter('id')) {
            $this->id = $request->getParameter('id');
            foreach (range(1, $this->pager->getLastPage()) as $page) {
                foreach ($this->pager as $i) {
                    if ($i->getId() == $this->id) {
                        break(2);
                    }
                }
                $this->pager->setPage($page);
                $this->pager->init();
            }
        }

        $this->ha_news = $this->pager->getResults();
        $this->categories = PluginTagTable::getPopulars(null, array('model' => 'HaNews'));
        $this->archive_months = HaNews::getArchiveMonths();

        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('index', array('ha_news' => $this->ha_news, 'pager' => $this->pager, 'page' => $request->getParameter('page', 1),
            'archive_months' => $this->archive_months, 'categories' => $this->categories,
            'id' => $this->id, 'q' => $request->getParameter('q')));
        if ($request->getParameter('sf_format') === 'atom')
            die($content);
        $this->content = $this->getPartial('home/footer', array('active' => 'news', 'big' => true, 'footer_content' => $content));
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->getRequest()->setAttribute('layout', 'footer');

        $this->ha_news = Doctrine_Core::getTable('HaNews')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->ha_news);
        $this->getRequest()->setAttribute('content_in_footer', true);
        $content = $this->getPartial('show', array('ha_news' => $this->ha_news));
        $this->content = $this->getPartial('home/footer', array('active' => 'news', 'big' => true, 'footer_content' => $content));
    }

}
