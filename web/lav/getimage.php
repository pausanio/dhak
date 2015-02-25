<?php
ini_set("display_errors", "true");
header('Content-type: text/html; charset=UTF-8');
include ("general.php");
$_REQUEST["img"] = str_replace("::", "/", $_REQUEST["img"]);
$thedir = substr($_REQUEST["img"], 0, strrpos($_REQUEST["img"], "/"));
$dir = $dir . $thedir;
$dirparts = explode("/", substr($thedir, 1));
foreach ($dirparts as $k => $v)
  $dirparts[$k] = nice($v);
$thefile = substr($_REQUEST["img"], strrpos($_REQUEST["img"], "/") + 1);
$i = 0;
$next = 0;
$files = array();
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
$last = $files[0];
foreach ($files as $i => $file) {
  if ($next == -1)
    $next = $file;
  if ($file == $thefile) {$nr = $i;
    $prev = $last;
    $next = -1;
  }
  $last = $file;
  if ($i == 0)
    $first = $file;
  $i++;
}
if ($next == -1)
  $next = $last;
echo '<div class="image-nav">
<div class="info"><h1>' . implode(", ", $dirparts) . '</h1></div>
<div class="info paging fl">
    <a class="first icon" href="getimage.php?img=' . $thedir . '/' . $first . '" onclick="$(\'#image\').load(this.href);return false;">Erste Seite</a>
    <a class="prev icon" href="getimage.php?img=' . $thedir . '/' . $prev . '" onclick="$(\'#image\').load(this.href);return false;">Seite zurück</a>
    <ul class="pages">';
if ($thefile != $prev)
  echo '<li>
         <a href="getimage.php?img=' . $thedir . '/' . $prev . '" onclick="$(\'#image\').load(this.href);return false;">' . ($nr) . '</a>
      </li>';
else
  echo "<li>&nbsp;</li>";
echo '<li>
         <a class="active" href="getimage.php?img=' . $thedir . '/' . $thefile . '" onclick="$(\'#image\').load(this.href);return false;">' . ($nr + 1) . '</a>
      </li>';
if ($thefile != $next)
  echo '<li>
         <a href="getimage.php?img=' . $thedir . '/' . $next . '" onclick="$(\'#image\').load(this.href);return false;">' . ($nr + 2) . '</a>
      </li>';
else
  echo '<li>' . str_repeat("&nbsp", strlen($nr)) . '</li>';
echo '</ul>
    <a class="next icon" href="getimage.php?img=' . $thedir . '/' . $next . '" onclick="$(\'#image\').load(this.href);return false;">Nächste Seite</a>
    <a class="last icon" href="getimage.php?img=' . $thedir . '/' . $last . '" onclick="$(\'#image\').load(this.href);return false;">Letzte Seite</a>
    </div>
<div class="info fl">' . $thefile . '</div>
<!--<div class="info fl"><img id="zoom" src="img/zoom_in.png" alt="zoom" /></div>-->
<div class="info fl"><a target="_blank" title="download" href="getimg.php?typ=download&img=' . $thedir . '/' . $thefile . '"><img src="img/disk.png" alt="download" /></a>
<a style="margin-left:15px" target="_blank" title="permalink" href="index.php?img=' . $thedir . '/' . $thefile . '"><img src="img/link.png" alt="download" /></a></div>
<div class="cl"></div>
</div>
';
echo '<img id="large" style="width:100%;height:auto" src="getimg.php?img=' . $_REQUEST["img"] . '" />';
?>
<script type="text/javascript">
	var i=new Image();i.src="getimg.php?img=<?php echo $_REQUEST["img"]?>";
	$('#large').click(function(e) {
		var i=$("#large");
		var p=i.parent();
		var nw=i.prop("naturalWidth");
		if (!nw) nw=i.width;
		var nh=i.prop("naturalHeight");
		if (!nh) nh=i.height;
		if (i.width()!=nw) {
			var ml=parseInt(((e.pageX-p.offset().left)/p.width()*nw)-(e.pageX-p.offset().left));
			var mt=parseInt(((e.pageY-p.offset().top)/p.height()*nh)-(e.pageY-p.offset().top));
			i.css("width",nw);
//			i.css("margin-left","-"+ml+"px");
//			i.css("margin-top","-"+mt+"px");
		} else {
			i.css("width",p.innerWidth()-24);
			i.css("margin-left","0px");
			i.css("margin-top","0px");
		}
	});
</script>