<?php
include ("db_connect.php");

$result = mysql_query ("SELECT * FROM settings WHERE name='ftp_dir'");
$array = mysql_fetch_array ($result);
$ftp_dir = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='upload_speed'");
$array = mysql_fetch_array ($result);
$upload_speed = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='download_speed'");
$array = mysql_fetch_array ($result);
$download_speed = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='quota_size'");
$array = mysql_fetch_array ($result);
$quota_size = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='quota_files'");
$array = mysql_fetch_array ($result);
$quota_files = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='permitted_ip'");
$array = mysql_fetch_array ($result);
$permitted_ip = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='pureftpd_conf_path'");
$array = mysql_fetch_array ($result);
$pureftpd_conf_path = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='pureftpd_init_script_path'");
$array = mysql_fetch_array ($result);
$pureftpd_init_script_path = $array["value"];

$result = mysql_query ("SELECT * FROM settings WHERE name='pureftpwho_path'");
$array = mysql_fetch_array ($result);
$pureftpwho_path = $array["value"];

?>