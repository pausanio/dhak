<?php if ($pager): ?><?php if ($prev): ?><li class="ui-state-default ui-corner-all"><a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . $prev->id . '&slug=' . $prev->getSignaturSlug()) ?>" title="<?php echo __('Seite zurück') ?>"><span class="ui-icon ui-icon-triangle-1-w"></span></a></li><?php endif ?>
<?php if ($next): ?><li class="ui-state-default ui-corner-all"><a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . $next->id .'&slug=' . $next->getSignaturSlug()) ?>" title="<?php echo __('Nächste Seite') ?>"><span class="ui-icon ui-icon-triangle-1-e"></span></a></li><?php endif ?><?php endif; ?>