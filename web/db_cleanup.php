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
/*
$sql ="SELECT table_name, table_type, engine, table_collation FROM INFORMATION_SCHEMA.TABLES
  WHERE engine='InnoDB'
  AND table_schema = '$db_name' AND table_name LIKE 'ha_tek%' AND table_name NOT LIKE '%_bak%' ORDER by table_name";
*/

$sql ="SELECT table_name, table_type, engine, table_collation FROM INFORMATION_SCHEMA.TABLES   WHERE engine='InnoDB'
  AND table_schema = '$db_name' AND table_name LIKE 'ha_%'  AND table_name NOT LIKE '%_bak_%'  AND table_name NOT LIKE 'ha_pat%'   ORDER by table_name";


$result = mysql_query($sql);
if(!$result) echo mysql_error();
$tables = array();
  while ($row = mysql_fetch_assoc($result)) {
    $tables[$row['table_name']] = $row;
  };

//print_r($tables);exit;
$cols = array();
foreach ($tables as $table=>$t_data){

/*mysql_query("DROP TABLE IF EXISTS ".$table."_bak_html");
mysql_query("CREATE TABLE IF NOT EXISTS ".$table."_bak_html LIKE $table");*/
mysql_query("INSERT INTO ".$table."_bak_html SELECT * FROM  $table");


  $sql ="SELECT table_name, column_name, column_type, data_type, character_set_name, collation_name FROM INFORMATION_SCHEMA.COLUMNS
  WHERE data_type IN ('text', 'varchar')
  AND table_schema = '$db_name' AND table_name='$table'";

  $result = mysql_query($sql);
  if(!$result) echo mysql_error();
    while ($row = mysql_fetch_assoc($result)) {
      $col = $row['column_name'];
      $_sql = "SELECT COUNT(*) FROM $table WHERE $col LIKE '%&%'";
      //echo $_sql;
      $_row =  mysql_fetch_row(mysql_query($_sql));
      if ($_row[0]>0) {
        $cols[$table][] = $row;
        //echo $_row[0];exit;
      }
    };
}

$count = 0;
$id_cols=array('ha_bestand2'=>array('bestand_sig', 'tekt_nr'), 'ha_verzeinheiten'=>array('bestand_sig', 'signatur'));
//print_r($cols); exit;
foreach($cols as $table=>$columns){
echo "<h2>$table</h2>";
  foreach($columns as $column){
    $col = $column['column_name'];
    $id = (isset($id_cols[$table]))? implode(',', $id_cols[$table]): 'id';
    $sql = "SELECT $id, $col FROM $table WHERE $col LIKE '%&%'";
    $result = mysql_query($sql);
    if(!$result) echo mysql_error()."\n $sql";

    while ($row = mysql_fetch_assoc($result)) {
      //echo "$table, $col, ID:". $row['id'];
$where = '';
      if(isset($id_cols[$table])){
      //echo implode(' AND ', $id_cols[$table]); exit;
        foreach($id_cols[$table] as $k){
          $where .= " $k='".$row[$k]."' AND ";
        }
      $where = substr($where,0, -4 );
      } else {
        $where = "id=".$row['id'];
      }
//echo $where; exit;
      $orig = $row[$col];
      $new = cleanUmlaut($row[$col]);
      if($orig !== $new){
        echo "<xmp>";#print_r($orig);echo "</xmp>";
        #echo "<xmp>";print_r($new);
$sql = "UPDATE $table SET $col='".trim(mysql_real_escape_string($new))."' WHERE $where ";
echo "$sql\n";
mysql_query($sql);
$count++;
echo "</xmp>";
      }

      //exit;
    }
  }
}
echo "
count: $count";

function cleanUmlaut($string){
return html_entity_decode($string,ENT_QUOTES, 'UTF-8');

  $uml = array('&auml;'=>'ä','&Auml;'=>'Ä','&ouml;'=>'ö','&Ouml;'=>'Ö','&uuml;'=>'ü','&Uuml;'=>'Ü', '&szlig;'=>'ß');
  return strtr($string, $uml);
}


?>