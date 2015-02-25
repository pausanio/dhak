<script type="text/javascript">
$(function(){
    $("a.more").click(
      function(event){
      event.preventDefault();
      $(this).next("span").toggle();
      $(this).hide();
      }
    );
});
</script>
<?php
use_helper('Text');
$text_limit = 300;
$data = array(array('label'=>'Bestand', 'method'=>'getBestandSig'),
              array('label'=>'Signatur', 'method'=>'getSignatur'),
              array('label'=>'Titel', 'method'=>'getTitel'),
              array('label'=>'Datierung', 'method'=>'getLaufzeit'),
              array('label'=>'Beschreibung', 'method'=>'getEnthaelt'),
        );
        $text_data = $text_data->get(0);
?>
<?php foreach($data as $i=>$d) :?>
  <?php if($text_data AND $text_data->$d['method']()) {
  
    $text = $text_data->$d['method']();
    $text1 = '';
    $text2 = '';
    if(strlen($text)>$text_limit){
      $text1 = substr($text_data->$d['method'](), 0, $text_limit);
      $text1 = substr($text_data->$d['method'](), 0, strrpos($text1, ' ')+1);
      $text2 = '<span class="extra_text" style="display:none;">'.str_replace($text1, '', $text).'</span>';
      $text1 .= ' <a class="more" href=""> ...'.__('mehr').'</a>';
    } else {
      $text1 = $text;
    }
    unset($text);
  ?>
    <p>
      <span class="label"><?php echo __($d['label']); ?>:</span>
      <span class="desc"><?php echo $text1; ?><?php echo $text2; ?></span>
    </p>
  <?php } ?>
<?php endforeach; ?>
