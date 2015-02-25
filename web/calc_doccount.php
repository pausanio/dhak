<pre>
<?php
$db = mysql_connect('localhost', 'harchiv', 'weidmarkt02');
if (!$db) {
    die('Could not connect: ' . mysql_error());
}

$db_name='harchiv_dev';
$db_selected = mysql_select_db($db_name, $db);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}
mysql_query("SET NAMES utf8");

$sql = "select tekt_nr from ha_tektonik order by tekt_nr";
$result = mysql_query($sql);
if(!$result) echo mysql_error();
$t = array();
  while ($row = mysql_fetch_row($result)) {
    $t[$row[0]] = 0;
  };
$t_ve = $t;
$sql = "select t.tekt_nr, count(d.id) dc from ha_tektonik t left join ha_bestand2 b on (t.tekt_nr=b.tekt_nr ) left join ha_verzeinheiten v on b.bestand_sig=v.bestand_sig left join ha_documents d on v.signatur=d.ve_signatur and b.bestand_sig=d.bestand_sig where t.status=1 and b.status=1 and v.status=1 and d.status=1 group by t.tekt_nr";
//die($sql);
$result = mysql_query($sql);
if(!$result) echo mysql_error();
$dc = array();
  while ($row = mysql_fetch_assoc($result)) {
    $dc[$row['tekt_nr']] = $row['dc'];
  };


print_r($dc);
foreach($dc as $tn=>$c){
if(isset($t[$tn])){$t[$tn]=$c;}
echo "$tn";
$o=1;
  foreach(range(1, substr_count($tn,'.')) as $i){
    $p = strpos($tn, '.', $o);
    $ti = substr($tn,0,$p);
    if(!isset($dc[$ti])){
      $t[$ti]+=$c;
    }
    $o=$p+1;
  }
  echo "\n";
  //break; //exit;
}
foreach($t as $tn=>$c){
$sql = "UPDATE ha_tektonik set doc_count=$c WHERE tekt_nr='$tn'";
echo "$sql\n";
$result = mysql_query($sql);
}


// ///// VERZEINHEITEN

$t = $t_ve;
$sql = "select t.tekt_nr, count(v.id) dc from ha_tektonik t left join ha_bestand2 b on (t.tekt_nr=b.tekt_nr ) left join ha_verzeinheiten v on b.bestand_sig=v.bestand_sig  where t.status=1 and b.status=1 and v.status=1  group by t.tekt_nr";
$result = mysql_query($sql);
if(!$result) echo mysql_error();
$dc = array();
  while ($row = mysql_fetch_assoc($result)) {
    $dc[$row['tekt_nr']] = $row['dc'];
  };


print_r($dc);
foreach($dc as $tn=>$c){
if(isset($t[$tn])){$t[$tn]=$c;}
echo "$tn";
$o=1;
  foreach(range(1, substr_count($tn,'.')) as $i){
    $p = strpos($tn, '.', $o);
    $ti = substr($tn,0,$p);
    if(!isset($dc[$ti])){
      $t[$ti]+=$c;
    }
    $o=$p+1;
  }
  echo "\n";
  //break; //exit;
}
foreach($t as $tn=>$c){
$sql = "UPDATE ha_tektonik set ve_count=$c WHERE tekt_nr='$tn'";
echo "$sql\n";
$result = mysql_query($sql);
}


// DOC_COUT FÜR HA_BESTAND2


$sql = "select b.tekt_nr, b.bestand_sig, count(d.id) dc from ha_bestand b left join ha_verzeinheiten v on b.bestand_sig=v.bestand_sig left join ha_documents d on v.signatur=d.ve_signatur and b.bestand_sig=d.bestand_sig where b.status=1 and v.status=1 and d.status=1 group by b.tekt_nr, b.bestand_sig";
$result = mysql_query($sql);
if(!$result) echo mysql_error();
while ($row = mysql_fetch_assoc($result)) {
  $sql = "UPDATE ha_bestand2 SET doc_count=".$row['dc']." WHERE tekt_nr='".$row['tekt_nr']."' AND bestand_sig='".$row['bestand_sig']."'";
  echo " $sql\n";
  mysql_query($sql);
};









?>