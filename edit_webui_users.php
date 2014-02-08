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
</table></br><?php echo("$info"); ?></br>
				<?php
					if (isset ($_POST['id']) && !isset($_POST['edit']) && !isset($_POST['delete'])) {
						$id = $_POST['id'];
                 		$result = mysql_query ("SELECT * FROM userlist WHERE id=$id");
						$myrow = mysql_fetch_array ($result);
						echo("
							<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">
								<p>
									<label>$ewu_form_login</br>
									<input value=\"$myrow[user]\" type=\"text\" name=\"user\" id=\"user\">
									</label>
								</p>
								<p>
									<label>$ewu_form_pwd</br>
									<input value=\"\" type=\"pass\" name=\"pass\" id=\"pass\">
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
									<INPUT type=\"submit\" name=\"edit\" value=\"$ewu_save\">
								</p>
								<INPUT type=\"hidden\" name=\"id\" value=\"$id\">
							</form>");
					}
					elseif ($_POST['edit']) {
						if (isset ($_POST['id'])) {$id = $_POST['id']; if ($id == '') {unset ($id);}}
						if (isset ($_POST['user'])) {$user = $_POST['user']; if ($user == '') {unset ($user);}}
						if (isset ($_POST['pass'])) {$pass = $_POST['pass']; if ($pass == '') {unset ($pass);}}
						if (isset ($_POST['language'])) {$language = $_POST['language']; if ($language == '') {unset ($language);}}
						$result = mysql_query ("SELECT * FROM userlist WHERE id=$id");
						$array = mysql_fetch_array ($result);
						// Проверяем были ли внесены какие-то изменения
						if (($user != $array[user]) || (isset ($pass)) || ($language != $array[language])) {
							// Если изменено имя пользователя, вносим изменения в базу
							if (($user != $array[user]) && isset ($id)) {
								$result = mysql_query ("UPDATE userlist SET user='$user' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$ewu_edit_loginok</strong></p>";}
								else {echo "<p><strong>$ewu_edit_loginerror</strong></p>";}
							}
							// Если изменен пароль пользователя, вносим изменения в базу
							if (isset ($pass)) { 
								$pass = md5($pass);
								if (($pass != $array[pass]) && isset ($id)) {
									$result = mysql_query ("UPDATE userlist SET pass='$pass' WHERE id='$id'");
									if ($result == 'true') {echo "<p><strong>$ewu_edit_passwdok</strong></p>";}
									else {echo "<p><strong>$ewu_edit_passwderror</strong></p>";}
								}
							}
							// Если изменён язык пользователя, вносим изменения в базу
							if (($language != $array[language]) && isset ($id)) {
								$result = mysql_query ("UPDATE userlist SET language='$language' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$ewu_edit_languageok</strong></p>";}
								else {echo "<p><strong>$ewu_edit_languageerror</strong></p>";}
							}
						}
						else {echo"<p><strong>$um_edit_nochanges</strong></p>";}
						echo"</br>
								<form name='to_list' action='webui_users.php'>
									<p>
										<label>
										<input type='submit' name='users' id='users' value='$ewu_edit_nochangesback'>
										</label>
									</p>
								</form>";
					}
					elseif ($_POST['delete']) {
						if (isset ($_POST['id'])) {
							$id = $_POST['id'];
							$result = mysql_query ("DELETE FROM userlist WHERE id='$id'");
							if ($result == 'true') {echo "<p>$ewu_del_resultok<p>";}
							else {echo "<p>$ewu_del_resulterror</p>";}
						}
						else {
							echo "<p>$ewu_del_notchecked</p>";
						}
					}
				?>
                  </br></br>
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