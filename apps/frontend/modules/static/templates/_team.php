<h2>Historisches Archiv der Stadt Köln</h2>

<div class="teampics clearfix">

    <?php foreach ($hastk_images as $i => $image): ?>
        <a href="#"><img id="i<?php echo substr($image, 12, 4) ?>" src="/images/content/<?php echo $image ?>" alt="Team HASTK <?php echo $i ?>" /></a>
    <?php endforeach ?>

    <h2 style="clear:both;">Das digitale Historische Archiv Köln</h2>
    <?php foreach ($dhak_images as $i => $image): ?>
        <a href="#"><img id="i<?php echo substr($image, 11, 4) ?>" src="/images/content/<?php echo $image ?>" alt="Team HASTK <?php echo $i ?>" /></a>
    <?php endforeach ?>

</div>

<div class="box well" style="display:none;">
    <div class="arrowUp"></div>
    <?php slot('title', sprintf('%s %s', get_slot('title'), __('Team'))) ?>
    <?php $lang = $sf_user->getCulture() ?>
    <?php echo htmlspecialchars_decode($cms_text['main']['de']) ?>
</div>

<script type="text/javascript">
    $(function() {
        $(".box").hide();
        $(".teampics img").hover(function(event) {
            event.preventDefault();
            $(".box").show();
            $("#teambox div").hide();
            var t = "#txt" + $(this).attr("id").substr(1, 4);
            $(t).show();
        })
    });
</script>