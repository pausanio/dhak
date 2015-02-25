<?php
$data = array(array('label'=>'Signatur', 'method'=>'getBestandSig'),
              array('label'=>'Name', 'method'=>'getBestandsname'),
              //array('label'=>'Beschreibung', 'method'=>'getTektBeschreibung')
        );
?>

<?php foreach($data as $i=>$d) :?>
  <?php if($text_data AND $text_data->$d['method']()) :?>
    <p>
      <span class="label"><?php echo __($d['label']); ?>:</span>
      <span class="desc"><?php echo nl2br($text_data->$d['method']()); ?></span>
    </p>
  <?php endif; ?>
<?php endforeach; ?>
