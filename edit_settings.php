<?php
$master = "edit_settings.php";
include ("blocks/default.php");
include ("blocks/lock.php");
include ("blocks/db_connect.php"); /*Подлкючаемся к базе*/
$user = $_SERVER['PHP_AUTH_USER'];
$info = '';
$get_user_language = FALSE;
$get_user_language = mysql_query("SELECT language FROM userlist WHERE user='$user';");
if (!$get_user_language) {
	if (($err = mysql_errno()) == 1054) {
		$info = "<p align=\"center\" class=\"table_error\">Your version of Pure-FTPd WebUI users table is not currently supported by current version, please upgrade your database to use miltilanguage support.</p>";
	}
	$language = "english";
	include("lang/english.php");
}
else {
	$language_row = mysql_fetch_array ($get_user_language);
	$language = $language_row['language'];
	if ($language == '') {
		$language = "english";
	}
	include("lang/$language.php");
}

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<head>");
echo("<title>$settings_title</title>");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />");
?>
<link rel='shortcut icon' href='img/favicon.ico' />
<link href="media/css/stile.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_page.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_table_jui.css" rel="StyleSheet" type="text/css">
<link href="media/css/jquery-ui-1.7.2.custom.css" rel="StyleSheet" type="text/css">
</head>
<body id="dt_example" class="ex_highlight_row">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="main_border">
  <tbody>
<?php include("blocks/header.php"); ?>
  <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
               <td valign="top">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <?php include("blocks/menu.php"); ?>
    </tr>
</table></br><?php echo("$info</br>");
			if (isset ($_POST['save'])) {
				if (($_POST['ftp_dir'] != $ftp_dir) || ($_POST['upload_speed'] != $upload_speed) || ($_POST['download_speed'] != $download_speed) || ($_POST['quota_size'] != $quota_size) || ($_POST['quota_files'] != $quota_files) || ($_POST['permitted_ip'] != $permitted_ip) || ($_POST['pureftpd_conf_path'] != $pureftpd_conf_path) || ($_POST['pureftpd_init_script_path'] != $pureftpd_init_script_path) || ($_POST['pureftpwho_path'] != $pureftpwho_path)) {
					if (isset ($_POST['ftp_dir'])) {
						if ($_POST['ftp_dir'] != $ftp_dir) {
							$ftp_dir_ = $_POST['ftp_dir'];
							$result = mysql_query ("UPDATE settings SET value='$ftp_dir_' WHERE name='ftp_dir'");
							if ($result == 'true') {
								echo "<p><strong>$settings_ftp_dir_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_ftp_dir_error</strong></p>";
							}
						}
					}
					if (isset($_POST['upload_speed'])) {
						if ($_POST['upload_speed'] != $upload_speed) {
							$upload_speed_ = $_POST['upload_speed'];
							$result = mysql_query ("UPDATE settings SET value='$upload_speed_' WHERE name='upload_speed'");
							if ($result == 'true') {
								echo "<p><strong>$settings_upload_speed_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_upload_speed_error</strong></p>";
							}
						}
					}
					if (isset($_POST['download_speed'])) {
						if ($_POST['download_speed'] != $download_speed) {
							$download_speed_ = $_POST['download_speed'];
							$result = mysql_query ("UPDATE settings SET value='$download_speed_' WHERE name='download_speed'");
							if ($result == 'true') {
								echo "<p><strong>$settings_download_speed_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_download_speed_error</strong></p>";
							}
						}
					}
					if (isset($_POST['quota_size'])) {
						if ($_POST['quota_size'] != $quota_size) {
							$quota_size_ = $_POST['quota_size'];
							$result = mysql_query ("UPDATE settings SET value='$quota_size_' WHERE name='quota_size'");
							if ($result == 'true') {
								echo "<p><strong>$settings_quota_size_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_quota_size_error</strong></p>";
							}
						}
					}
					if (isset($_POST['quota_files'])) {
						if ($_POST['quota_files'] != $quota_files) {
							$quota_files_ = $_POST['quota_files'];
							$result = mysql_query ("UPDATE settings SET value='$quota_files_' WHERE name='quota_files'");
							if ($result == 'true') {
								echo "<p><strong>$settings_quota_files_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_quota_files_error</strong></p>";
							}
						}
					}
					if (isset($_POST['permitted_ip'])) {
						if ($_POST['permitted_ip'] != $permitted_ip) {
							$permitted_ip_ = $_POST['permitted_ip'];
							$result = mysql_query ("UPDATE settings SET value='$permitted_ip_' WHERE name='permitted_ip'");
							if ($result == 'true') {
								echo "<p><strong>$settings_permitted_ip_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_permitted_ip_error</strong></p>";
							}
						}
					}
					if (isset($_POST['pureftpd_conf_path'])) {
						if ($_POST['pureftpd_conf_path'] != $pureftpd_conf_path) {
							$pureftpd_conf_path_ = $_POST['pureftpd_conf_path'];
							$result = mysql_query ("UPDATE settings SET value='$pureftpd_conf_path_' WHERE name='pureftpd_conf_path'");
							if ($result == 'true') {
								echo "<p><strong>$settings_pureftpd_conf_path_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_pureftpd_conf_path_error</strong></p>";
							}
						}
					}
					if (isset($_POST['pureftpd_init_script_path'])) {
						if ($_POST['pureftpd_init_script_path'] != $pureftpd_init_script_path) {
							$pureftpd_init_script_path_ = $_POST['pureftpd_init_script_path'];
							$result = mysql_query ("UPDATE settings SET value='$pureftpd_init_script_path_' WHERE name='pureftpd_init_script_path'");
							if ($result == 'true') {
								echo "<p><strong>$settings_pureftpd_init_script_path_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_pureftpd_init_script_path_error</strong></p>";
							}
						}
					}
					if (isset($_POST['pureftpwho_path'])) {
						if ($_POST['pureftpwho_path'] != $pureftpwho_path) {
							$pureftpwho_path = $_POST['pureftpwho_path'];
							$result = mysql_query ("UPDATE settings SET value='$pureftpwho_path' WHERE name='pureftpwho_path'");
							if ($result == 'true') {
								echo "<p><strong>$settings_pureftpwho_path_ok</strong></p>";
							}
							else {
								echo "<p><strong>$settings_pureftpwho_path_error</strong></p>";
							}
						}
					}
				}
				else {
					echo"<p><strong>$settings_nochanges</strong></p>";
				}
			}
			else {
				echo("<p class=\"text_title\">$settings_title2</p>");
				echo("
					<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">
						<p>
							<label>$settings_ftp_dir</br>
							<input type=\"text\" name=\"ftp_dir\" id=\"ftp_dir\" value=\"$ftp_dir\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_upload_speed</br>
							<input type=\"text\" name=\"upload_speed\" id=\"upload_speed\" value=\"$upload_speed\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_download_speed</br>
							<input type=\"text\" name=\"download_speed\" id=\"download_speed\" value=\"$download_speed\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_quota_size</br>
							<input type=\"text\" name=\"quota_size\" id=\"quota_size\" value=\"$quota_size\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_quota_files</br>
							<input type=\"text\" name=\"quota_files\" id=\"quota_files\" value=\"$quota_files\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_permitted_ip</br>
							<input type=\"text\" name=\"permitted_ip\" id=\"permitted_ip\" value=\"$permitted_ip\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_pureftpd_conf_path</br>
							<input type=\"text\" name=\"pureftpd_conf_path\" id=\"pureftpd_conf_path\" value=\"$pureftpd_conf_path\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_pureftpd_init_script_path</br>
							<input type=\"text\" name=\"pureftpd_init_script_path\" id=\"pureftpd_init_script_path\" value=\"$pureftpd_init_script_path\" size=\"40\">
							</label>
						</p>
						<p>
							<label>$settings_pureftpwho_path</br>
							<input type=\"text\" name=\"pureftpwho_path\" id=\"pureftpwho_path\" value=\"$pureftpwho_path\" size=\"40\">
							</label>
						</p>
						<p>
							<label>
							<INPUT type=\"submit\" name=\"save\" value=\"$settings_save_button\" size=\"40\">
							</label>
						</p>
					</form>");
			} ?>
               </td>
            </tr>
          </table>
        </td>
       </tr>
<?php include("blocks/footer.php"); ?>
  </tbody>
</table>
</body>
</html>
