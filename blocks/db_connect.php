<?
$db = FALSE;
$db = mysql_connect ("localhost", "purewebui", "purewebui");
if (!$db) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysql_error() . "</p>";
	die (mysql_error());
}
$table = FALSE;
$table = mysql_select_db ("pureftpd", $db);
if (!$table) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysql_error() . "</p>";
	die (mysql_error());
}
?>
