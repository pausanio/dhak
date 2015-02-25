<?php
ini_set("display_errors","true");
include ("general.php");
$fname=substr($_REQUEST["img"],strrpos($_REQUEST["img"],"/")+1);

$typ="inline";
if ((isset($_REQUEST["typ"]))&&($_REQUEST["typ"]=="download")) $typ="attachment";
$img=$dir.$_REQUEST["img"];
if ((!file_exists($img))&&(strpos($img,"thumbs")!==FALSE)) {
  $imgp=explode($img,"/");
  $orig=str_replace("/thumbs","",$img);
  $origpath=substr($img,0,strrpos($img,"/"));
  $origpath=substr($img,0,strrpos($origpath,"/"));
//  echo $origpath."<br />";
  if (!file_exists($origpath."/thumbs")) {
//    echo "Verzeichnis ".$origpath."/thumbs "."ist nicht da!"."<br />";
    mkdir($origpath."/thumbs");
  }
  if (file_exists($origpath."/".$fname)) {
//    echo '/usr/bin/convert "'.$origpath."/".$fname.'" -geometry "250x250>" -quality 95 "'.$img.'"';
    shell_exec('/usr/bin/convert "'.$origpath."/".$fname.'" -geometry "250x250>" -quality 95 "'.$img.'"');
  }
  else exit;
}
header( 'Content-type: image/jpeg;');
header('Content-Disposition: '.$typ.' ;filename='.$fname );
readfile($img);

?>

