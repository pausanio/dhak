<?php slot('meta_title', 'Lesesaal') ?>
<?php slot('meta_title', sprintf('Lesesaal - %s', $content_data->getTitleDe())) ?>

<?php
use_helper('Date');
$images = array(1 => 'abschrift', 2 => 'digital', 3 => 'foto', 4 => 'kopie', 5 => 'mikrofilm', 6 => 'mikrofiche', 7 => 'online', 8 => 'druck', 9 => 'archivexemplar');
?>
<div id="archiveWrapper" class="">
  <div class="header">
    <div class="viewType"><?php include_partial('viewtype'); ?></div>
    <div class="viewOptions"><?php include_partial('viewoptions'); ?></div>
  </div>
  <div class="sidebar ">
    <div class="sidebarWrapper">
      <?php include_partial('sidebar_' . $archive_view['type'], $sidebar_params); ?>
    </div>
  </div>
  <div class="content ">

    <div class="gallery">
      <div class="">
        <div class=""></div>
        <?php
        $target = ' target="_blank"';
        $link = '/images/documents/org/' . $content_data->getIntname();
        if ($content_data->getIntname() AND in_array(strtolower(substr($content_data->getIntname(), -4)), array('.jpg', '.png', '.gif'))) {
          $img = '/images/documents/medium/m_' . $content_data->getIntname();
          $link = 'http://dfg-viewer.de/show/?set[image]=' . $doc_page . '&set[mets]=' . urlencode('http://www.historischesarchivkoeln.de' . url_for('dfgviewer', array('sf_format' => 'xml', 'tekt_nr' => $tekt_nr, 'bestand_sig' => $bestand_sig, 've_sig' => $ve_sig)));
          $target = ' target="dfgviewer"';
        } else {
          $img = '/images/liegt_vor_' . $images[$content_data->getVorlageId()] . '_' . $sf_user->getCulture() . '.png';
        }
        ?>
        <?php if ($content_data->getIntname()) : ?>
          <a href="<?php echo $link; ?>"<?php echo $target; ?>>
            <img src="<?php echo $img; ?>" title="<?php echo $content_data->getTitleDe(); ?>" alt="<?php echo $content_data->getTitleDe(); ?>"/>
          </a>
        <?php else : ?>
          <img src="<?php echo $img; ?>" title="<?php echo $content_data->getTitleDe(); ?>" alt="<?php echo $content_data->getTitleDe(); ?>"/>
        <?php endif; ?>
      </div>
    </div>
    <div class="desc">
      <div class="_col-3-5">
        <p><!--Nr. 378 von 380--></p>
        <h3><?php echo $content_data->getTitleDe(); ?></h3>
        <p><?php echo $content_data->getDescrDe(); ?></p>
      </div>
      <div class="_col-2-5">
        <p>&nbsp;</p>
        <p>
          <span class="label"><?php echo __('Datierung'); ?>:</span>
          <?php
          $datierung = ($content_data->getDatierung()) ? $content_data->getDatierung() : __('keine Angabe');
          if ($content_data->getDateYear())
            $datierung = $content_data->getDateYear();

          if ($content_data->getDateYear() AND $content_data->getDateMonth())
            $datierung = format_date($content_data->getDateYear() . '-' . $content_data->getDateMonth() . '-01', 'MMMM y');

          if ($content_data->getDateYear() AND $content_data->getDateMonth() AND $content_data->getDateDay())
            $datierung = format_date($content_data->getDateYear() . '-' . $content_data->getDateMonth() . '-' . $content_data->getDateDay(), 'D');

          echo $datierung;
          ?>
        </p>
        <?php if ($content_data->getFolio()) : ?>
          <p>
            <span class="label"><?php echo __('Folio'); ?>:</span>
            <?php echo $content_data->getFolio(); ?>
          </p>
        <?php endif; ?>
        <?php if ($content_data->getVorlageComment()) : ?>
          <p>
            <span class="label"><?php echo __('Kommentar'); ?>:</span>
            <?php echo $content_data->getVorlageComment(); ?>
          </p>
        <?php endif; ?>
        <p>
          <span class="label"><?php echo __('Rechtlicher Hinweis'); ?>:</span>
          <?php echo ($content_data->getBildVorlage()) ? $content_data->getBildVorlage() : __('keine Angabe'); ?>
        </p>
        <p>
          <span class="label"><?php echo __('Eingestellt am'); ?>:</span>
          <?php echo format_date($content_data->getCreatedAt(), 'D'); ?>
          <?php if ($content_data->getEinsteller()): ?>
            von <?php echo $content_data->getEinsteller(); ?>
          <?php else: ?>
            von <?php echo $content_data->getCreator(); ?>
          <?php endif; ?>
        </p>
        <?php if ($content_data->getAufnahmedatum()) : ?>
          <p>
            <span class="label"><?php echo __('Aufnahmedatum'); ?>:</span>
            <?php echo $content_data->getAufnahmedatum(); ?>
          </p>
        <?php endif; ?>
        <?php if ($content_data->getProvenienz()) : ?>
          <p>
            <span class="label"><?php echo __('Provenienz'); ?>:</span>
            <?php echo $content_data->getProvenienz(); ?>
          </p>
        <?php endif; ?>
        <?php if ($content_data->getMaterial()) : ?>
          <p>
            <span class="label"><?php echo __('Material'); ?>:</span>
            <?php echo $content_data->getMaterial(); ?>
          </p>
        <?php endif; ?>
        <p>
          <?php if ($content_data->getLink()) : ?>
          <p>
            <span class="label"><?php echo __('Link'); ?>:</span>
            <a href="<?php echo $content_data->getLink(); ?>"><?php echo ($content_data->getLinkname()) ? $content_data->getLinkname() : $content_data->getLink(); ?></a>
          </p>
        <?php endif; ?>
<!--        <p>
  <span class="label">Patenschaft:</span>
  <br />
  DUMMY Diese Verzeichnungseinheit sucht einen Paten zur Restauration. <a href="?show=paten1">...mehr Infos</a>
</p>-->
      </div>
      <div class="fixfloat"></div>
    </div>
  </div>
  <div class="footerSpacer"></div>
  <div class="footer">
    <div class="subline">
      <?php
      if ($hastk_contact) :
        $short = trim(strtolower(substr($hastk_contact['name'], strrpos($hastk_contact['name'], ' '))));
        ?>
        <h3>Falls Sie Fragen haben</h3>
        <p>schauen Sie doch mal in unsere <a href="<?php echo url_for('faq'); ?>">FAQs</a> oder schreiben Sie <br />
          mir über das <a href="<?php echo url_for('faq') . '?contact=' . $short; ?>">Kontaktformular</a>. <br />Ihr<?php echo ($hastk_contact['gender'] == 'female') ? 'e' : ''; ?> <?php echo $hastk_contact['name']; ?>.
        </p>
      <?php endif; ?>

    </div>
    <img class="contact" src="/images/ansprechpartner_<?php echo $short; ?>.jpg" alt="Bild <?php echo $hastk_contact['name']; ?>" />
  </div>
</div>