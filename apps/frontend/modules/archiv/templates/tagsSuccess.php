<?php $lang = $sf_user->getCulture() ?>

<div id="archiveWrapper" class="searchResults">
  <div class="header">
    <div class="searchResultsHeader">
      <?php echo $headline ?>
    </div>
  </div>

  <div class="content">
    <div class="gallery">
      <?php if ($items): ?>
      <?php foreach ($items as $item): ?>
        <div class="item ">
            <a href="<?php echo url_for('@lesesaal?type='.Archiv::getTypeSlug(DhastkImporter::INTBESTAND).'tektonik&id='.$item['archiv_id']) ?>" class="prev">
            <img src="<?php echo $item['filename'] ?>" alt="">
          </a>
          <p><strong>
              <?php echo $item['signatur'] ?>,
              <?php echo $item['name'] ?>
            </strong></p>
        </div>
      <?php endforeach ?>
      <?php else: ?>
        Es würden keine Bestände gefunden.
      <?php endif ?>
    </div>
    <div class="fixfloat"></div>
  </div>

</div>