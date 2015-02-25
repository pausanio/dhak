<script type="text/javascript">
$(function(){
    $("#desc_more").click(
      function(event){
      event.preventDefault();
      $(this).toggle();
      $("span.desc_more").toggle();
      $("#desc_less").show();
      }
    );
    $("#desc_less").click(
      function(event){
      event.preventDefault();
      $(this).toggle();
      $("span.desc_more").toggle();
      $("#desc_more").show();
      }
    );
});
</script>
<?php
$data = array(array('label'=>'Titel', 'method'=>'getTektTitel'),
        array('label'=>'Beschreibung', 'method'=>'getTektBeschreibung')
        );
?>

<?php foreach($data as $i=>$d) :?>
  <?php if($text_data AND $text_data->$d['method']()) :
if($d['label'] === 'Beschreibung'){
  $limit = 500;
  $desc = nl2br(substr($text_data->$d['method'](),0,$limit));
  $desc_more = nl2br(substr($text_data->$d['method'](),$limit));
} else {
  $desc = $text_data->$d['method']();
  $desc_more = null;
}
  ?>
    <p>
      <span class="label"><?php echo __($d['label']); ?>:</span>
      <span class="desc"><?php echo $desc; ?>
      <?php if($desc_more) :?>
        <a href="" id="desc_more">... mehr</a>
        <span class="desc_more" style="display:none;"><?php echo $desc_more; ?><a href="" id="desc_less">... weniger</a></span>
      <?php endif; ?>
      </span>
    </p>
  <?php endif; ?>
<?php endforeach; ?>
