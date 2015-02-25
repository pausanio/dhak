      <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$ve_pager->getPreviousPage()) ?>" class="prev">
      <?php echo __('Vorherige') ?>
      </a>
      <div class="pages">
    <?php foreach ($ve_pager->getLinks() as $page): ?>
      <?php $a_class = ($page == $ve_pager->getPage())? 'strong' : ''; ?>
      <a class="<?php echo $a_class ?>" href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$page) ?>"><?php echo $page; ?></a>
    <?php endforeach; ?>
      </div>
      <?php  if($sf_request->isXmlHttpRequest()) : ?>
        <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$ve_pager->getNextPage()) ?>" class="next">
      <?php else : ?>
        <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$ve_pager->getNextPage()) ?>" class="next">
      <?php endif ?>
      <?php echo __('Nächste') ?>
      </a>
