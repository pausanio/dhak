<?php slot('title', sprintf('%s Home', get_slot('title'))); ?>

<?php $lang = $sf_user->getCulture(); ?>
<?php $text = array(); ?>
<?php foreach ($cms_text as $c): ?>
  <?php $text[$c->getName()]['de'] = htmlspecialchars_decode($c->getText()); ?>
<?php endforeach ?>

<script type="text/javascript">
  $(document).ready(function(){
    $("a.banner").click(
    function(event){
      event.preventDefault();
      $("div.info").removeClass("active");
      $(this).parent().addClass("active");
    }
  );
  });
</script>
<div id="infoWrapper" class="">
  <div class="nav"></div>
  <div class="body">
    <ul>
      <li>
        <div class="info active">
          <a href="<?php echo url_for('static/info?pagename=intro&paragraph=01'); ?>" class="banner info-01 <?php echo $lang ?>" title="<?php echo $text['vertical01_title'][$lang]; ?>" ><?php echo $text['vertical01_title'][$lang]; ?></a>
          <div class="col-left">
            <h2><?php echo $text['vertical01_title'][$lang]; ?></h2>
            <p><?php echo $text['vertical01_intro'][$lang]; ?></p>
            <p>
              <a href="<?php echo url_for('static/info?pagename=intro&paragraph=01'); ?>" class="moreInfo <?php echo $lang ?>"><?php echo __('Mehr Informationen'); ?></a>
            </p>
          </div>
          <div class="col-right">
            <img src="/images/header_info_01.jpg" alt="Archiv" />
          </div>
        </div>
      </li>
      <li>
        <div class="info">
          <a href="<?php echo url_for('static/info?pagename=forschen&paragraph=01'); ?>" class="banner info-02 <?php echo $lang ?>" title="<?php echo $text['vertical02_title'][$lang]; ?>" ><?php echo $text['vertical02_title'][$lang]; ?></a>
          <div class="col-left">
            <h2><?php echo $text['vertical02_title'][$lang]; ?></h2>
            <p><?php echo $text['vertical02_intro'][$lang]; ?></p>
            <p><a href="<?php echo url_for('static/info?pagename=forschen&paragraph=01'); ?>" class="moreInfo <?php echo $lang ?>"><?php echo __('Mehr Informationen'); ?></a></p>
          </div>
          <div class="col-right">
            <img src="/images/header_info_02.jpg" alt="altes Buch" />
          </div>
        </div>
      </li>
      <li>
        <div class="info">
          <a href="<?php echo url_for('static/info?pagename=hastk&paragraph=01'); ?>" class="banner info-03 <?php echo $lang ?>" title="<?php echo $text['vertical03_title'][$lang]; ?>" ><?php echo $text['vertical03_title'][$lang]; ?></a>
          <div class="col-left">
            <h2><?php echo $text['vertical03_title'][$lang]; ?></h2>
            <p><?php echo $text['vertical03_intro'][$lang]; ?></p>
            <p><a href="<?php echo url_for('static/info?pagename=hastk&paragraph=01'); ?>" class="moreInfo <?php echo $lang ?>"><?php echo __('Mehr Informationen'); ?></a></p>
          </div>
          <div class="col-right">
            <img src="/images/header_info_03.jpg" alt="Pläne" />
          </div>
        </div>
      </li>
      <li>
        <div class="info">
          <a href="<?php echo url_for('static/info?pagename=dhak&paragraph=01'); ?>" class="banner info-04 <?php echo $lang ?>" title="<?php echo $text['vertical04_title'][$lang]; ?>" ><?php echo $text['vertical04_title'][$lang]; ?></a>
          <div class="col-left">
            <h2><?php echo $text['vertical04_title'][$lang]; ?></h2>
            <p><?php echo $text['vertical04_intro'][$lang]; ?></p>
            <p><a href="<?php echo url_for('static/info?pagename=dhak&paragraph=01'); ?>" class="moreInfo <?php echo $lang ?>"><?php echo __('Mehr Informationen'); ?></a></p>
          </div>
          <div class="col-right">
            <img src="/images/header_info_04.jpg" alt="Airbook" />
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
