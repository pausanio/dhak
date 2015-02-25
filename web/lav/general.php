<?php

$dir = '/www/dhastk/dhastk/digitalisate/arbeitsdateien/Personenstandsarchiv_Rhld';

function nice($k)
{
	$k=str_replace("_"," ",$k);
	$k=str_replace("oeln","öln",$k);
	$k=str_replace("Mue","Mü",$k);
	$k=str_replace("Sterbefaelle","Sterbefälle",$k);
	return $k;
}

/**
 * get index intro
 */
function getIntro()
{

    // read symfony db connection params
    require_once(dirname(__FILE__).'/../../lib/vendor/symfony/lib/yaml/sfYaml.php');
    $yaml = sfYaml::load(dirname(__FILE__).'/../../config/databases.yml');
    $param = $yaml['all']['doctrine']['param'];

    $dsn = explode(';',$param['dsn']);
    $dbname = explode('=',$dsn[0]);
    $host = explode('=',$dsn[1]);

    $conn = array(
        'dbname' => $dbname[1],
        'host' => $host[1],
        'username' => $param['username'],
        'password' => $param['password']
    );

    // get intro text

    mysql_connect($conn['host'], $conn['username'], $conn['password']);
    mysql_select_db($conn['dbname']);

    mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    $results = mysql_query("SELECT text FROM cms_text WHERE module = 'lav' AND name = 'intro'");
    $result = mysql_fetch_row($results);

    return $result[0];
}

?>
