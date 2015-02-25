<?php slot('meta_title', 'Netzwerk') ?>

<script type="text/javascript">

  $(function() {
    $("a.projektmail").click(function(event) {
      event.preventDefault();

      var a = $(event.target);
      $('#dia').load(
              '<?php echo url_for('mailform') ?>',
              {type: 'project', id: a.attr('projectID')}
      );

      $("#dia").dialog({
        autoOpen: false,
        //height: 300,
        width: 450,
        modal: true,
        title: 'Email',
        dialogClass: 'my-ui'
      });

      $("#dia").dialog("open");
    });
  });
</script>
<div id="dia"></div>

<?php slot('title', sprintf('%s Netzwerk', get_slot('title'))) ?>
<?php $lang = $sf_user->getCulture() ?>

<div class="content network">
  <?php echo htmlspecialchars_decode($cms_text['intro']['de']); ?>
  <div class="hr"></div>
  <?php foreach ($ha_projektes as $i => $ha_projekt): ?>
    <div class="member">
      <?php if (strlen(trim($ha_projekt->getProjektEinsteller())) > 0) : ?>
        <p><span class="label"><?php echo __('Name:'); ?></span>
          <?php echo $ha_projekt->getProjektEinsteller(); ?>
        </p>
      <?php endif; ?>

      <?php $text = trim($ha_projekt->getProjektTitle()) ?>

      <?php if (strlen($text) > 0) : ?>
        <p>
          <span class="label"><?php echo __('Projekt:'); ?></span>
          <?php echo $text; ?>
          <?php if ($ha_projekt->getProjektType() !== "0" AND $ha_projekt->getProjektType() !== '') : ?>
            (<?php echo $ha_projekt->getProjektType(); ?>)
          <?php endif; ?>
        </p>
      <?php endif; ?>

      <?php if (strlen(trim($ha_projekt->getProjektBestand())) > 0) : ?>
        <p>
          <span class="label"><?php echo __('Bestände:'); ?></span>
          <span class="small"><?php echo nl2br($ha_projekt->getProjektBestand()); ?></span>
        </p>
      <?php endif; ?>

      <?php if (strlen(trim($ha_projekt->getProjektNotiz())) > 0) : ?>
        <p>
          <span class="label"><?php echo __('Notiz:'); ?></span>
          <span class="small"><?php echo nl2br($ha_projekt->getProjektNotiz()); ?></span>
        </p>
      <?php endif; ?>

      <?php $rcpt_email = $ha_projekt->getProjektEinsteller() . " <" . $ha_projekt->getUser()->getEmailAddress() . ">"; ?>
      <?php if ($ha_projekt->getUser()->getEmailAddress()) : ?>
        <p>
          <span class="label"><?php echo __('Kontakt:'); ?></span>
          <?php if ($sf_user->isAuthenticated()) : ?>
            <span class="small"><a href="" class="projektmail" projectID="<?php echo $ha_projekt->getId() ?>">Nachricht schreiben</a> an <?php echo $ha_projekt->getProjektEinsteller() ?></span>
          <?php else: ?>
            <span class="small"><a href="<?php echo url_for('sf_guard_signin') ?>">Melden Sie sich an</a>, um direkt mit <?php echo $ha_projekt->getProjektEinsteller() ?> Kontakt aufzunehmen!</span>
          <?php endif; ?>
        </p>
      <?php endif; ?>

    </div>
    <div class="hr"></div>
  <?php endforeach; ?>
</div>