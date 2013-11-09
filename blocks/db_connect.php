<?php
include ("config.php");
$db = FALSE;
$db = mysql_connect ("$mysql_host", "$mysql_webui_user", "$mysql_webui_passwd");
if (!$db) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysql_error() . "</p>";
	die (mysql_error());
}
$table = FALSE;
$table = mysql_select_db ("$mysql_database", $db);
if (!$table) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysql_error() . "</p>";
	die (mysql_error());
}
?>
