<?php
  slot('title', sprintf('%s %s', get_slot('title'), __('Informationen')));
  $lang = $sf_user->getCulture();

  $pagenames = array(1 => 'intro', 2 => 'forschen', 3 => 'hastk', 4 => 'dhak');
  $pagenames_index = array_flip($pagenames);
  $top_text = array();
  for ($i = 1; $i <= 4; $i++) {
    if ($lang === 'en' AND $cms_text_top['vertical0' . $i . '_title']['en'] !== '') {
      $top_text[$i] = htmlspecialchars_decode($cms_text_top['vertical0' . $i . '_title']['en']);
    } else {
      $top_text[$i] = htmlspecialchars_decode($cms_text_top['vertical0' . $i . '_title']['de']);
    }
  }
  $navi_text = array();
  $i = 0;
  foreach ($cms_text_navi as $nav) {
    $i++;
    if ($lang === 'en' AND $nav['en'] !== '') {
      $navi_text[$i] = htmlspecialchars_decode($nav['en']);
    } else {
      $navi_text[$i] = htmlspecialchars_decode($nav['de']);
    }
  }
?>

<ul id="navInfo">
  <?php
  foreach ($top_text as $i => $top) {
    $arrow = ($i === $pagenames_index[$pagename]) ? '<span class="arrow">&nbsp;</span>' : '';
    $active = ($i === $pagenames_index[$pagename]) ? 'active' : '';
    ?>
    <li class="<?php echo $active; ?>">
      <a href="<?php echo url_for('static/info?pagename=' . $pagenames[$i] . '&paragraph=01'); ?>" class="<?php echo $active; ?>">
        <?php echo $top_text[$i]; ?><?php echo $arrow; ?>
      </a>

      <?php if ($pagename == $pagenames[$i]) { ?>
        <ul>
          <?php
          $c = 1;
          foreach ($navi_text as $navi) {
            $_arrow = ($c === (int) $paragraph) ? '<span class="arrow">&nbsp;</span>' : '';
            $_active = ($c !== (int) $paragraph) ? 'active' : '';
            ?>
            <li class="<?php echo $_active; ?>"><a href="<?php echo url_for('static/info?pagename=' . $pagenames[$i] . '&paragraph=0' . $c); ?>"><?php echo $navi; ?><?php # echo $_arrow; ?></a></li>
            <?php $c++;
          }
          ?>
        </ul>
    <?php } ?>
    </li>
<?php } ?>

</ul>

<div class="infoNavSpacer"></div>
<?php
if ($lang === 'en' AND $cms_text['main']['en'] !== '') {
  echo htmlspecialchars_decode($cms_text['main']['en']);
} else {
  echo htmlspecialchars_decode($cms_text['main']['de']);
}
?>

<div class="fixfloat"></div>