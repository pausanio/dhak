<?php if ($pager): ?>
    <div class="paging clearfix">
        <?php if ($prev): ?>
            <a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . $prev->id . '&slug=' . $prev->getSignaturSlug()) ?>" class="prev" title="<?php echo __('Seite zurück') ?>"><?php echo __('Seite zurück') ?></a>
        <?php endif ?>
        <?php if ($next): ?>
            <a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . $next->id . '&slug=' . $next->getSignaturSlug()) ?>" class="next" title="<?php echo __('Nächste Seite') ?>"><?php echo __('Nächste Seite') ?></a>
        <?php endif ?>
    </div>
<?php endif; ?>