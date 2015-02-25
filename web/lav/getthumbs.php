<?php
ini_set("display_errors", "true");
header('Content-type: text/html; charset=UTF-8');

$dir = "/www/dhastk/dhastk/digitalisate/arbeitsdateien/Personenstandsarchiv_Rhld" . str_replace("::", "/", substr($_REQUEST["dir"], 1)) . "";

$files = array();

if (!file_exists($dir)) {
    echo "<script>alert('Das Verzeichnis konnte nicht gefunden werden, wird aber in Kürze wieder zur Verfügung stehen.')</script>";
    die();
}

if ($dh = opendir($dir)) {
  while (($file = readdir($dh)) !== false) {
    if (substr($file, 0, 1) == ".")
      continue;
    if ($file == "thumbs")
      continue;
    if (substr($file, -3) != "jpg")
      continue;
    $files[] = $file;
  }
  closedir($dh);
}
sort($files);
foreach ($files as $i => $file) {
  echo '<img class="thumb" id="' . $_REQUEST["dir"] . '::' . $file . '" src="getimg.php?img=' . str_replace("::", "/", substr($_REQUEST["dir"], 1)) . '/thumbs/' . $file . '" />';
}
?>
<script type="text/javascript">
	$('.thumb').click(function(e) {
		$("#image").load("getimage.php?img="+$(this).attr("id").substring(1));
	});

	// @todo
   <?php if(isset($_GET['dontload']) === false):?>
  $("#image").load("getimage.php?img=<?php echo substr($_REQUEST["dir"],1).'::'.$files[0];?>");
    <?php endif; ?>

</script>
