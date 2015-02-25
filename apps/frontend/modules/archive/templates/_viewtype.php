<?php
$archive_view = $sf_user->getAttribute('archive_view');
$active = array('list'  => ($archive_view['type']==='list')  ? ' active' : '',
                 'slider'=>($archive_view['type']==='slider')? ' active' : '');
?><!--
      <a href="<?php echo url_for('archive/index?viewtype=slider'); ?>" class="slider<?php echo $active['slider'];?>">Slider</a>
      <a href="<?php echo url_for('archive/index?viewtype=list'); ?>" class="list<?php echo $active['list']; ?>">Liste</a>-->