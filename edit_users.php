<?php
$master = "edit_users.php";
include ("blocks/lock.php");
include ("blocks/db_connect.php"); /*Подключаемся к базе*/
if (isset ($_GET['id'])) {$id = $_GET['id'];}
$user = $_SERVER['PHP_AUTH_USER'];
$info = '';
$get_user_language = FALSE;
$get_user_language = mysql_query("SELECT language FROM userlist WHERE user='$user';");
if (!$get_user_language) {
	if (($err = mysql_errno()) == 1054) {
		$info = "<p align=\"center\" class=\"table_error\">Your version of Pure-FTPd WebUI users table is not currently supported by current version, please upgrade your database to use miltilanguage support.</p>";
		include("lang/english.php");
	}
}
else {
	$language_row = mysql_fetch_array ($get_user_language);
	$language = $language_row['language'];
	include("lang/$language.php");
}

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<head>");
echo("<title>$um_title</title>");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset='UTF-8'\" />");
?>
<link rel='shortcut icon' href='img/favicon.ico' />
<link href="media/css/stile.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_page.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_table_jui.css" rel="StyleSheet" type="text/css">
<link href="media/css/jquery-ui-1.7.2.custom.css" rel="StyleSheet" type="text/css">
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<? echo("
<script type=\"text/javascript\" charset=\"utf-8\">
            $(document).ready(function() {
                $('#users_table').dataTable( {
                    \"oLanguage\": {
                        \"sUrl\": \"media/dataTables.$language.txt\"
                    },
					\"bJQueryUI\": true,
					\"sPaginationType\": \"full_numbers\"
                } );
            } );
        </script> ");?>
</head>
<body id="dt_example" class="ex_highlight_row">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="main_border">
  <tbody>
<? include("blocks/header.php"); ?>
  <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
               <td valign="top">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <? include("blocks/menu.php"); ?>
    </tr>
		</table></br><? echo("$info"); ?></br>
				<?php
					// Эта часть используется, если была нажата кнопка "Добавить пользователя"
					if($_POST['add_user']) {
						echo"
							<FORM name='add' method='post' action='$PHP_SELF'>
								<p>
									<label>$um_userform_login</br>
									<INPUT type='text' name='User' id='User'>
									</label>
								</p>
								<p>
									<label>$um_userform_status</br>
									<INPUT type='text' name='status' id='status'>
									</label>
								</p>
								<p>
									<label>$um_userform_pwd</br>
									<INPUT type='password' name='Password' id='Password'>
									</label>
								</p>
								<p>
									<label>$um_userform_folder</br>
									<INPUT type='text' name='Dir' id='Dir'>
									</label>
								</p>
								<p>
									<label>$um_userform_ullimit</br>
									<INPUT type='text' name='ULBandwidth' id='ULBandwidth'>
									</label>
								</p>
								<p>
									<label>$um_userform_dllimit</br>
									<INPUT type='text' name='DLBandwidth' id='DLBandwidth'>
									</label>
								</p>
								<p>
									<label>$um_userform_permip</br>
									<INPUT type='text' name='ipaccess' id='ipaccess'>
									</label>
								</p></br>
								<p>
									<label>
									<INPUT type='submit' name='add' id='add' value='$um_add_addbutton'>
									</label>
								</p>
								<p>
									<label>
									<INPUT type='submit' name='users' id='users' value='$um_add_backbutton'>
									</label>
								</p>
							</FORM>";
					}

					// Эта часть используется, если была нажата кнопка "Добавить"
					elseif($_POST['add']) {
						echo "</br></br></br>";

						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if (isset ($_POST['User'])) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if (isset ($_POST['status'])) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if (isset ($_POST['Password'])) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if (isset ($_POST['Dir'])) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if (isset ($_POST['ULBandwidth'])) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if (isset ($_POST['DLBandwidth'])) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if (isset ($_POST['ipaccess'])) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}

						// Если папка не была задана - выставляем значение по умолчанию
						if ($Dir == '') {
							$Dir = '/media/FTP';}

						// Если ограничение скорости аплода не было задано - выставляем значение по умолчанию
						if ($ULBandwidth == '') {
							$ULBandwidth = '0';}

						// Если ограничение скорости даунлода не было задано - выставляем значение по умолчанию
						if ($DLBandwidth == '') {
							$DLBandwidth = '0';}

						// Если разрешённый IP-адрес не был задан - выставляем значение по умолчанию
						if ($ipaccess == '') {
							$ipaccess = '*';}

						// Если все нужные поля заполнены, добавляем пользователя в базу pureftpd
						if (isset ($User) && isset($status) && isset($Password) && isset ($Dir) && isset ($DLBandwidth) && isset ($ULBandwidth) && isset ($_POST['ipaccess'])) {
							$result = mysql_query ("INSERT INTO ftpd (User,status,Password,Dir,ULBandwidth,DLBandwidth,ipaccess) VALUES ('$User','$status',md5('$Password'),'$Dir','$ULBandwidth','$DLBandwidth','$ipaccess')");
							if ($result == 'true') {echo "<p><strong>$um_add_presultok</strong></p>";}
							else {echo "<p><strong>$um_add_presulterror</strong></p>";}
						}

						else {echo "<p><strong>$um_add_checkfields</strong></p>";}

						echo "</br>
							<form name='to_list' method='post' action='$PHP_SELF'>
								<p>
									<label>
									<input type='submit' name='users' id='users' value='$um_add_checkfieldsback'>
									</label>
								</p>
							</form>";
					}

					// Эта часть используется, если была нажата кнопка "Сохранить изменения"
					elseif($_POST['edit']) {
						echo "</br></br></br>";

						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if (isset ($_POST['User'])) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if (isset ($_POST['status'])) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if (isset ($_POST['Password'])) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if (isset ($_POST['Dir'])) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if (isset ($_POST['ULBandwidth'])) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if (isset ($_POST['DLBandwidth'])) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if (isset ($_POST['ipaccess'])) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}
						if (isset ($_POST['id'])) {$id = $_POST['id']; if ($id == '') {unset ($id);}}

						// Запрашиваем из БД настройки пользователя
						$result = mysql_query ("SELECT * FROM ftpd WHERE id=$id");
						$array = mysql_fetch_array ($result);

						// Проверяем были ли внесены какие-то изменения
						if (($Dir != $array[Dir]) || ($User != $array[User]) || ($status != $array[status]) || (isset ($Password)) || ($ULBandwidth != $array[ULBandwidth]) || ($DLBandwidth != $array[DLBandwidth]) || ($ipaccess != $array[ipaccess])) {

							// Если изменена папка пользователя, вносим изменения в базу
							if (($Dir != $array[Dir]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET Dir='$Dir' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_folderok</strong></p>";}
								else {echo "<p><strong>$um_edit_foldererror</strong></p>";}

							}
							// Если изменено имя пользователя, вносим изменения в базу
							if (($User != $array[User]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET User='$User' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_loginok</strong></p>";}
								else {echo "<p><strong>$um_edit_loginerror</strong></p>";}
							}

							// Если изменён статус пользователя, вносим изменения в базу
							if (($status != $array[status]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET status='$status' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_statusok</strong></p>";}
								else {echo "<p><strong>$um_edit_statuserror</strong></p>";}
							}

							// Если изменён пароль пользователя, вносим изменения в базу
							if (isset ($Password)) {$Password = md5($Password);
								if (($Password != $array[Password]) && isset ($id)) {
									$result = mysql_query ("UPDATE ftpd SET Password='$Password' WHERE id='$id'");
									if ($result == 'true') {echo "<p><strong>$um_edit_passwdok</strong></p>";}
									else {echo "<p><strong>$um_edit_passwderror</strong></p>";}}
							}

							// Если изменено ограничение скорости загрузки, вносим изменения в базу
							if (($ULBandwidth != $array[ULBandwidth]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET ULBandwidth='$ULBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_ullimitok</strong></p>";}
								else {echo "<p><strong>$um_edit_ullimiterror</strong></p>";}
							}

							// Если изменено ограничение скорости скачивания, вносим изменения в базу
							if (($DLBandwidth != $array[DLBandwidth]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET DLBandwidth='$DLBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_dllimitok</strong></p>";}
								else {echo "<p><strong>$um_edit_dllimiterror</strong></p>";}
							}
							// Если изменён разрешенный IP адрес, вносим изменения в базу
							if (($ipaccess != $array[ipaccess]) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET ipaccess='$ipaccess' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_permipok</strong></p>";}
								else {echo "<p><strong>$um_edit_permiperror</strong></p>";}
							}
						}
						else {echo"<p><strong>$um_edit_nochanges</strong></p>";}

					echo"</br>
							<form name='to_list' method='post' action='$PHP_SELF'>
								<p>
									<label>
									<input type='submit' name='users' id='users' value='$um_edit_nochangesback'>
									</label>
								</p>
							</form>";
					}
					else {
						if ((!isset ($id)) || (isset ($_POST['users']))) {
						// Шапка таблицы
						echo("
							<p class='text_title' align='center'><strong>$um_t_title</strong></p><div id='container'>
							<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display\" id=\"users_table\">
								<thead>
									<tr>
										<th>$um_t_th1</th>
										<th>$um_t_th2</th>
										<th>$um_t_th3</th>
										<th>$um_t_th4</th>
										<th>$um_t_th5</th>
										<th>$um_t_th6</th>
									</tr>
								</thead><tbody>");
						$result = mysql_query ("SELECT * FROM ftpd");
						$myrow = mysql_fetch_array ($result);
						do {
							// Выводим список пользователей
							printf ("<tr>
										<td align='center'></a><a href='edit_users.php?id=%s'>%s</a></td>
										<td align='center'>$myrow[status]</td>
										<td>$myrow[Dir]</td>
										<td align='center'>$myrow[ULBandwidth]</td>
										<td align='center'>$myrow[DLBandwidth]</td>
										<td align='center'>$myrow[ipaccess]</td>
									</tr>",$myrow ["id"],$myrow ["User"]);
						}
					while ($myrow = mysql_fetch_array ($result));

						echo("	</tbody></table>");
											

						echo("</br></br><table width='95%' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td align='right'>
								<form name='edit' method='post' action='$PHP_SELF'>
									<p>
										<label>
										<input type='submit' name='add_user' id='add_user' value='$um_adduserbutton'>
										</label>
									</p>
								</form></tr><td></table>");
					}
					// Эта часть используется, если выбран пользователь для редактирования
					else {
						$result = mysql_query ("SELECT * FROM ftpd WHERE id=$id");
						$myrow = mysql_fetch_array ($result);
						print <<<HERE
							<FORM name="form1" method="post" action="$PHP_SELF">
								<p>
									<label>$um_userform_login</br>
									<INPUT value="$myrow[User]" type="text" name="User" id="User">
									</label>
								</p>
								<p>
									<label>$um_userform_status</br>
									<INPUT value="$myrow[status]" type="text" name="status" id="status">
									</label>
								</p>
								<p>
									<label>$um_userform_pwd</br>
									<INPUT value="" type="password" name="Password" id="Password">
									</label>
								</p>
								<p>
									<label>$um_userform_folder</br>
									<INPUT value="$myrow[Dir]" type="text" name="Dir" id="Dir">
									</label>
								</p>
								<p>
									<label>$um_userform_ullimit</br>
									<INPUT value="$myrow[ULBandwidth]" type="text" name="ULBandwidth" id="ULBandwidth">
									</label>
								</p>
								<p>
									<label>$um_userform_dllimit</br>
									<INPUT value="$myrow[DLBandwidth]" type="text" name="DLBandwidth" id="DLBandwidth">
									</label>
								</p>
								<p>
									<labe>$um_userform_permip</br>
									<INPUT value="$myrow[ipaccess]" type="text" name="ipaccess" id="ipaccess">
									</label>
								</p></br>
									<INPUT name="id" type="hidden" value="$myrow[id]">
								<p>
									<label>
									<INPUT type="submit" name="edit" id="edit" value="$um_edit_savebutton">
									</label>
								</p>
								<p>
									<label>
									<INPUT type="submit" name="users" id="users" value="$um_edit_backbutton">
									</label>
								</p>
							</FORM>
HERE;
						}
				}
				?>
		</td></tr></table>
	</td></tr>
<? include("blocks/footer.php"); ?>
</tbody></table>
</body>
</html>
