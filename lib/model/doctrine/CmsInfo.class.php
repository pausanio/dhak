<?php

/**
 * CmsInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    historischesarchivkoeln.de
 * @subpackage model
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CmsInfo extends BaseCmsInfo {

    public function getParentId() {
        if (!$this->getNode()->isValidNode() || $this->getNode()->isRoot()) {
            return null;
        }

        $parent = $this->getNode()->getParent();
        return $parent['id'];
    }

    public function getIndentedName() {
        return str_repeat('- ', $this['level']) . $this['title'];
    }

}
