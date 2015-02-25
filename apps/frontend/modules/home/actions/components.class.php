<?php

class homeComponents extends sfComponents
{

    public function executeFooter(sfWebRequest $request)
    {
        $this->ha_news = Doctrine_Core::getTable('HaNews')
                ->createQuery()
                ->where('status = ?', 1)
                ->orderby('publish_date DESC')
                ->limit('5')
                ->execute();
        $this->active = 'news';
        $this->footer_content = get_partial('home/defaultfooter', array('ha_news' => $this->ha_news));
    }

    public function executeSlider(sfWebRequest $request)
    {
        $this->slider = Doctrine_Core::getTable('CmsSlider')
                ->createQuery()
                ->where('is_active = ?', 1)
                ->orderby('position ASC')
                ->execute();
    }

}

