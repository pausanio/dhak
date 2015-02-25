<?php slot('meta_title', 'Lesesaal') ?>

<div id="archiveWrapper" class="">
 <div class="header">
  <div class="viewType"><?php include_partial('viewtype'); ?></div>
  <div class="viewOptions"><?php include_partial('viewoptions'); ?></div>
 </div>
 <div class="sidebar ">
  <div class="sidebarWrapper">
<?php include_partial('sidebar_'.$archive_view['type'], $sidebar_params); ?>
  </div>
</div>
<div class="content">
<?php
foreach(array('text', 'gallery', 'detail') as $option){
  if($archive_view['options'][$option]){
    if($option === 'text') $option .= '_' . $archive_level;
    include_partial($option, $content_params);
  }
}

?>
</div>

  <div class="footerSpacer"></div>
  <div class="footer">
   <div class="subline">
<?php if($hastk_contact) :
      $short = trim(strtolower(substr($hastk_contact['name'], strrpos($hastk_contact['name'], ' '))));?>
      <h3>Falls Sie Fragen haben</h3>
      <p>schauen Sie doch mal in unsere <a href="<?php echo url_for('faq');?>">FAQs</a> oder schreiben Sie <br />
      mir über das <a href="<?php echo url_for('faq').'?contact='.$short;?>">Kontaktformular</a>. <br />Ihr<?php echo ($hastk_contact['gender']=='female')? 'e' :''; ?> <?php echo $hastk_contact['name']; ?>.
      </p>
<?php endif; ?>

    </div>
<?php if($hastk_contact) :?>
    <img class="contact" src="/images/ansprechpartner_<?php echo $short; ?>.jpg" alt="Bild <?php echo $hastk_contact['name']; ?>" />
<?php endif; ?>
  </div>
</div>