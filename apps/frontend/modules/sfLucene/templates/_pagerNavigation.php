<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination pagination-small">
    <ul>
    <?php if ($pager->getPage() != $pager->getPreviousPage()): ?>
      <li>
        <a class="prev" href="<?php echo url_for('sfLucene/search') ?>?<?php echo $form->getQueryString($pager->getPreviousPage()) ?>" class="prev"><?php echo __('Seite zurück') ?></a>
      </li>
    <?php endif ?>
    <?php foreach ($pager->getLinks($radius) as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <li class="active">
          <a href="<?php echo url_for('sfLucene/search') ?>?<?php echo $form->getQueryString($page) ?>"><?php echo $page ?></a>
        </li>
      <?php else: ?>
        <li>
          <a href="<?php echo url_for('sfLucene/search') ?>?<?php echo $form->getQueryString($page) ?>"><?php echo $page ?></a>
        </li>
      <?php endif ?>
    <?php endforeach ?>
    <?php if ($pager->getPage() != $pager->getNextPage()): ?>
      <li>
        <a class="next" href="<?php echo url_for('sfLucene/search') ?>?<?php echo $form->getQueryString($pager->getNextPage()) ?>" class="next"><?php echo __('Nächste Seite') ?></a>
      </li>
    <?php endif ?>
    </ul>
  </div>
<?php endif ?>