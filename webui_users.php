<?php
$master = "webui_users.php";
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
echo("<title>$wu_title</title>");
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
			if (isset ($_POST['add'])) {
				echo("
					<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">
						<p>
							<label>$ewu_form_login</br>
							<input type=\"text\" name=\"user_add\" id=\"user_add\">
							</label>
						</p>
						<p>
							<label>$ewu_form_pwd</br>
							<input type=\"password\" name=\"pass\" id=\"pass\">
							</label>
						</p>
						<p>
							<label>$ewu_form_lang</br>
							<select name=\"language\">");
							$directory = "lang/";
							$languages = glob("" . $directory . "*.php");
							foreach($languages as $lang) {
								$rest = substr($lang, 5);
								$lng = substr($rest, 0, -4);
								echo("<option>$lng</option>");
							}
							echo("</label>
							</select>
						</p>
						<p>
							<label>
							<INPUT type=\"submit\" name=\"adduser\" value=\"$wu_adduserbutton\">
							</label>
						</p>
					</form>");
			}
			elseif (isset ($_POST['adduser'])) {
				if (isset ($_POST['user_add'])) {$user_add = $_POST['user_add']; if ($user_add == '') {unset ($user_add);}}
				if (isset ($_POST['password'])) {$pass = $_POST['password']; if ($pass == '') {unset ($pass);}}
				if (isset ($_POST['language'])) {$language = $_POST['language']; if ($language == '') {unset ($language);}}
				if (isset ($user) && isset($pass) && isset($language)) {
					$result = mysql_query ("INSERT INTO userlist (user,pass,language) VALUES ('$user_add',md5('$pass'),'$language')");
					if ($result == 'true') {echo "<p><strong>$wu_add_resultok</strong></p>";}
					else {echo "<p><strong>$wu_add_resulterror</strong></p>";}
				}
				else {echo "<p><strong>$wu_add_checkfields</strong></p>";}

						echo "</br>
							<form name='to_list' method='post' action='$PHP_SELF'>
								<p>
									<label>
									<input type='submit' name='users' id='users' value='$wu_add_checkfieldsback'>
									</label>
								</p>
							</form>";
			}
			else {
				echo("<p class=\"text_title\">$wu_select</p>");
				echo("<form action=\"edit_webui_users.php\" method=\"post\">");
				$result = mysql_query ("SELECT user,id FROM userlist");
				$myrow = mysql_fetch_array ($result);
				$id = $myrow["id"];
					do {
						printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>", $myrow["id"], $myrow["user"]);
					}
				while ($myrow = mysql_fetch_array ($result));
                echo("<p><input name=\"submit\" type=\"submit\" value=\"$wu_editbutton\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                	<input type=\"submit\" name=\"delete\" value=\"$ewu_deluserbutton\"></p></form>");
                echo("<form name=\"to_list\" method=\"post\" action=\"$PHP_SELF\">
                	<p><input type=\"submit\" name=\"add\" value=\"$wu_adduserbutton\"></p></form>");
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
