<?php slot('title', sprintf('%s News', get_slot('title'))) ?>
<?php use_helper('Date') ?>

<?php
$lang = $sf_user->getCulture();
$title = $ha_news->getNewsTitle();
$text = $ha_news->getNewsText();
?>

<div class="col-left">
  <h2><?php echo __('Aktuelles'); ?></h2>

  <div class="article">
    <h3>
      <span class="date"><?php echo format_date($ha_news->getUpdatedAt()); ?></span>
      <?php echo $title ?>
    </h3>
    <p><?php echo $text ?></p>
    <p class="meta">
      <?php echo $ha_news->getNewsEinsteller() ?>
    </p>
  </div>
</div>

<div class="col-right">
  <h2>Was kommt in diese Spalte?</h2>
</div>
<div class="fixfloat"></div>
