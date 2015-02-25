<?php

/**
 * HaNews
 *
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class HaNews extends BaseHaNews
{

    public function getNewest()
    {
        $q = $this->getTable()
                ->createQuery('a')
                ->select('news_title')
                ->where('status = ?', 1)
                ->orderby('publish_date DESC')
                ->limit(1);
        $return = array();
        foreach ($q->execute() as $i => $n) {
            $return['title']['de'] = $n->getNewsTitle();
            $return['text']['de'] = $n->getNewsText();
        }
        return $return;
    }

    public static function getArchiveMonths()
    {
        $q = Doctrine_Core::getTable('HaNews')
                ->createQuery('a')
                ->distinct(true)
                ->select('DATE_FORMAT(publish_date, \'%Y-%m\') as amonth')
                ->where('status = ?', 1)
                ->orderby('publish_date DESC');

        return $q->execute();
    }

    /**
     * Overwrite save method
     *
     * @param Doctrine_Connection $conn
     * @return mixed
     */
    public function save(Doctrine_Connection $conn = null)
    {
        if ($this->isModified() || $this->isNew()) {
            //$this->resizeImage();
            $this->generateThumbnail();
        }
        return parent::save($conn);
    }

    /**
     * Resize image
     *
     * @return boolean
     */
    protected function resizeImage()
    {
        $filename = substr($this->getImage(), 0, strrpos($this->getImage(), '.')) . '.jpg';
        $sourceFile = sfConfig::get('app_cms_news_imagefile_org') . $this->getImage();
        if ($this->getImage()) {
            $picture = new sfThumbnail(sfConfig::get('app_cms_news_image_maxwidth'), sfConfig::get('app_cms_news_image_maxheight'), true, true, sfConfig::get('app_cms_image_quality'));
            $picture->loadFile($sourceFile);
            $picture->save(sfConfig::get('app_cms_news_imagefile') . $filename, 'image/jpeg');
        }
        return true;
    }

    /**
     * Thumbnail
     *
     * @return boolean
     */
    protected function generateThumbnail()
    {
        $filename = substr($this->getImage(), 0, strrpos($this->getImage(), '.')) . '.jpg';
        $sourceFile = sfConfig::get('app_cms_news_imagefile_org') . $this->getImage();
        if ($this->getImage()) {
            $picture = new sfThumbnail(sfConfig::get('app_cms_news_thumb_maxwidth'), sfConfig::get('app_cms_news_thumb_maxheight'), true, true, sfConfig::get('app_cms_thumb_quality'));
            $picture->loadFile($sourceFile);
            $picture->save(sfConfig::get('app_cms_news_thumbfile') . $filename, 'image/jpeg');
        }
        return true;
    }

    /**
     * Image source
     *
     * @return string
     */
    protected function getImageSrc()
    {
        if ($this->getImage()) {
            //return sfConfig::get('app_cms_news_image') . substr($this->getImage(), 0, strrpos($this->getImage(), '.')) . '.jpg';
            return sfConfig::get('app_cms_news_image_org') . $this->getImage();
        } else {
            return false;
        }
    }

    /**
     * Thumbnail source
     *
     * @return string
     */
    protected function getThumbSrc()
    {
        if ($this->getImage()) {
            return sfConfig::get('app_cms_news_thumb') . substr($this->getImage(), 0, strrpos($this->getImage(), '.')) . '.jpg';
        } else {
            return "/images/news_default.jpg";
        }
    }

}
