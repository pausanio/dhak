<?php
  ini_set("display_errors", "true");
  header('Content-type: text/html; charset=UTF-8');
  include ("general.php");
  
  if (file_exists("navi")) {
    $arr = unserialize(file_get_contents("navi"));
  } else {
    $arr = getdir($dir);
    ksortTree($arr);
    file_put_contents("navi", serialize($arr));
  }
  
  function shownav(&$arr, $sumdir)
  {
    if (count($arr) > 1) {
      echo '<ul>';
    }
    foreach ($arr as $k => $val) {
      if ($k != "count") {
        echo '<li id="a' . $sumdir . "::" . $k . '"><b>' . nice($k) . '</b><br />&nbsp;&nbsp;' . $val["count"] . ' Einträge';
        if (is_array($val))
          shownav($val, $sumdir . "::" . $k);
        echo '</li>';
      }
    }
    if (count($arr) > 1) {
      echo '</ul>';
    }
  }

  function getdir($dir)
  {
    $arr = array();
    $count = 0;
    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
          if (substr($file, 0, 1) == ".")
            continue;
          if ($file == "thumbs")
            continue;
          if (is_dir($dir . "/" . $file)) {
            $ar = getdir($dir . "/" . $file);
            if (is_array($ar))
              $count += $ar["count"];
            else
              $count += $ar;
            $arr[$file] = $ar;
          } else {
            $count++;
          }
        }
        closedir($dh);
      }
    }
    if (empty($arr))
      return array("count" => $count);
    $arr["count"] = $count;
    return $arr;
  }

  function ksortTree( &$array )
  {
    if (!is_array($array)) {
      return false;
    }
    ksort($array);
    foreach ($array as $k=>$v) {
      ksortTree($array[$k]);
    }
    return true;
  }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Das digitale Historische Archiv Köln</title>
<link rel="stylesheet" href="css/styles.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/taskmanager.js"></script>
<script type="text/javascript">
  // <![CDATA[
  $(document).ready(function () {
      var ve_count = 10,
          ve_page = 1,
          ve_term = '';

      //NAVIGATION
      var taskmanager = window.setInterval(function () {
          $("#nav").css("height", ($(window).height() - $("#header").height() - 1) + "px");
          $("#thumbs").css("height", ($(window).height() - $("#header").height() - 1) + "px");
          $("#image").css("height", ($(window).height() - $("#header").height() - 1) + "px");
          $("#image").css("width", ($(window).width() - 2 - $("#nav").width() - $("#thumbs").width()) + "px");
      }, 100);

      $('#nav ul').first().css("display", "block");

      $('li').click(function (e) {
          if ($(this).hasClass("active")) {
              $(this).removeClass("active");
              return false;
          }
          if ($(this).children("UL").length > 0) {
              $(this).addClass("active");
          } else {
              $("#thumbs").load("getthumbs.php?dir=" + $(this).attr("id"));
              $("#image").css("background", "url('/lav/img/loading.gif') no-repeat center");
          }
          return false;
      });
      $('.thumb').click(function (e) {
          $(".image").load("getimg.php?img=" + $(this).attr("id"));
      });
	<?php
  if (isset($_REQUEST["img"])) {
    $thedir = substr($_REQUEST["img"], 0, strrpos($_REQUEST["img"], "/"));
    echo '$("#thumbs").load("getthumbs.php?dontload=true&dir=a' . $thedir . '");
  	$("#image").load("getimage.php?img=' . $_REQUEST["img"] . '");
  		var l=$("#a' . str_replace("/", '\\\\:\\\\:', $thedir) . '");
  		while(l.length>0) {
  			if (l.prop("tagName")=="LI") l.addClass("active");
  			l=l.parent();
  		}
    ';
  }
  ?>
  });
  // ]]>
</script>
</head>

<body>
<div id="globalWrapper">
    <div id="header">
        <h1>
            <a id="logo" href="http://historischesarchivkoeln.de">
                Das digitale historische Archiv der Stadt Köln
            </a>
    </div>
    <div id="nav">
        <a id="home" href="/lav/index.php">Startseite</a>
        <?php echo shownav($arr, "") ?>
    </div>
    <div id="image">

        <div class="content">
            <div class="inner">
                <?php echo getIntro() ?>
            </div>
        </div>
    </div>
    <div id="thumbs"></div>
</div>
</body>
</html>
