<?php
$symFoot = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/default/getFooter');
echo $symFoot;
?>
