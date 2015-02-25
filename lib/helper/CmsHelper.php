<?php

/**
 * CMS Widget Helper
 *
 * @usage: <?php echo cms_widget('tooltip', 'news_feed_abonnieren') ?>
 *
 * @param type $module
 * @param type $name
 * @return type
 */
function cms_widget($module, $name, $class = false)
{
    $widget = Doctrine_Core::getTable('CmsText')
            ->createQuery()
            ->where('module = ?', $module)
            ->andWhere('name = ?', $name)
            ->execute()
            ->getFirst();

    if (!$widget) {
        $widget = new CmsText();
        $widget->setModule($module);
        $widget->setName($name);
        $widget->setText($name);
        $widget->setComment('neu');
        $widget->save();
    }

    # htmlspecialchars_decode ?
    if (false === $class) {
        return $widget->getText();
    } else {
        return '<div class="' . $class . '">' . $widget->getText() . '</div>';
    }
}

?>
