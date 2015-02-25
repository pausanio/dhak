<?php
$archive_view = $sf_user->getAttribute('archive_view');
$active = array('text'  =>  ($archive_view['options']['text'])    ? ' active' : '',
                'detail'=>  ($archive_view['options']['detail'])  ? ' active' : '',
                'gallery'=> ($archive_view['options']['gallery']) ? ' active' : '');
//print_r($active);exit;
?><!--
  <a href="<?php echo url_for('archive/index?viewoptions[text]='.($active['text']=='')); ?>" class="text<?php echo $active['text'];?>">Text ein</a>
  <a href="<?php echo url_for('archive/index?viewoptions[detail]='.($active['detail']==='')); ?>" class="detail<?php echo $active['detail'];?>">Detail</a>
  <a href="<?php echo url_for('archive/index?viewoptions[gallery]='.($active['gallery']==='')); ?>" class="gallery<?php echo $active['gallery'];?>">Galerie</a>-->
