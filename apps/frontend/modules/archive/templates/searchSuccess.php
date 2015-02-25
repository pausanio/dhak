<?php slot('meta_title', 'Suche') ?>

<?php
$lang = $sf_user->getCulture();
$images = array(1 => 'abschrift', 2 => 'digital', 3 => 'foto', 4 => 'kopie', 5 => 'mikrofilm', 6 => 'mikrofiche', 7 => 'online', 8 => 'druck', 9 => 'archivexemplar');
//if(!$query)return;
?>
<div id="archiveWrapper" class="searchResults">
  <div class="header">
    <div class="searchResultsHeader">
      <?php echo $doc_total; ?> Suchergebnisse zu "<?php echo $query; ?>"
    </div>
    <div class="searchResultFilter">
      <form method="get" id="searchResultFilter" action="<?php echo url_for('lesesaal_search'); ?>" onchange="document.getElementById('searchResultFilter').submit();">
        <select name="archive_search_count">
          <?php
          foreach (array(16, 32, 48, 64, 96) as $i) :
            $archive_search_count = $sf_user->getAttribute('archive_search_count', 16);
            $selected = ($archive_search_count == $i) ? ' selected="selected"' : ''
            ?>
            <option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?> <?php echo __('pro Seite'); ?></option>
          <?php endforeach; ?>
        </select>
        <select name="sortBy">
          <option value="">sortieren nach...</option>
          <?php
          foreach (array("date" => "Alter", "name" => "Name", "bestand_sig" => "Systematik") as $v => $n) :
            $archive_search_sort = $sf_user->getAttribute('archive_search_sort', null);
            $selected = ($archive_search_sort == $v) ? ' selected="selected"' : ''
            ?>
            <option value="<?php echo $v; ?>"<?php echo $selected; ?>><?php echo $n; ?></option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="query" value="<?php echo $query; ?>"/>
      </form>
    </div>
  </div>
  <div class="content">
    <div class="gallery">
      <?php if (!$query) : ?>
        <?php echo __('Bitte geben Sie einen Suchbegriff ein'); ?>
      <?php else: ?>
        <?php
        $i = 1;
        foreach ($content_data as $item):
          $doc = $item->getHaDocuments()->get(0);

          if ($doc->getIntname() AND in_array(strtolower(substr($doc->getIntname(), -4)), array('.jpg', '.png', '.gif'))) {
            $thumb = '/images/documents/thumbs/th_' . $doc->getIntname();
          } else {
            $thumb = '/images/liegt_vor_' . $images[$doc->getVorlageId()] . '_' . $sf_user->getCulture() . '.png';
          }

          $title = $item->getTitel();
          $text = '';
          $alt = $title;
          $link = url_for('lesesaal', array(
            'tekt_nr' => $item->getHaBestand2()->getTektNr(),
            'bestand_sig' => $item->getHaBestand2()->getBestandSig(),
            've_sig' => str_replace('/', '-', $item->getSignatur())));
          ?>
          <div class="item <?php if ($i % 4 == 0): ?>no-margin-right<?php endif; ?>">
            <a href="<?php echo $link; ?>" class="prev">
              <img src="<?php echo $thumb ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>">
            </a>
            <p><strong><?php echo $item->getHaBestand2()->getBestandSig(); ?>, <?php echo $doc->getSignatur(); ?> <?php echo $title; ?></strong></p>
          </div>
          <?php
          $i++;
        endforeach;
      endif;
      ?>
      <div class="fixfloat"></div>
      <div class="hr"></div>

      <?php if ($doc_pager AND $doc_pager->haveToPaginate()): ?>
        <div class="paging">
          <a href="<?php echo url_for('lesesaal_search', array('query' => $query, 'page' => $doc_pager->getPreviousPage())); ?>" class="prev"><?php echo __('Seite zurück') ?></a>
          <div class="pages">
            <?php foreach ($doc_pager->getLinks() as $page): ?>
              <?php $active = ($page == $doc_pager->getPage()) ? ' class="active"' : ''; ?>
              <a href="<?php echo url_for('lesesaal_search', array('query' => $query, 'page' => $page)); ?>"<?php echo $active ?>><?php echo $page ?></a>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo url_for('lesesaal_search', array('query' => $query, 'page' => $doc_pager->getNextPage())); ?>" class="next"><?php echo __('Nächste Seite') ?></a>
        </div>
      <?php endif; ?>


    </div>
  </div>

  <div class="footerSpacer"></div>
  <div class="footer">

  </div>
</div>