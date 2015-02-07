<?php
$master = "edit_users.php";
include("./config.php");
include("./blocks/db_connect.php"); /*Подключаемся к базе*/
include("./blocks/lock.php");
include("./blocks/default.php");
$user = $_SERVER['PHP_AUTH_USER'];
$info = '';

if ( isset ($_GET['id']) ) { $id = $_GET['id']; }

$language = 'english';
$get_user_language = FALSE;
$get_user_language = mysql_query("SELECT language FROM userlist WHERE user='$user';");
if ( !$get_user_language )
{
	if ( ($err = mysql_errno()) == 1054 )
	{
		$info = '<p align="center" class="table_error">
                Your version of Pure-FTPd WebUI users table is not currently supported by current version,
                please upgrade your database to use multi-language support.
            </p>';
	}
}
else
{
	$language_row = mysql_fetch_array($get_user_language);
	if ( $language_row['language'] != '' )
	{
		$language = $language_row['language'];
	}
}
include('./lang/'.$language.'.php');

?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= $um_title ?></title>

	<link rel="shortcut icon" href="./img/favicon.ico">
	<link href="./media/css/stile.css" rel="stylesheet" type="text/css">
	<link href="./media/css/demo_page.css" rel="stylesheet" type="text/css">
	<link href="./media/css/demo_table_jui.css" rel="stylesheet" type="text/css">
	<link href="./media/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css">
</head>
<body id="dt_example" class="ex_highlight_row">
	<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="main_border">
		<tbody>
		<?php include("blocks/header.php"); ?>
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<?php include("blocks/menu.php"); ?>
							</tr>
						</table>

						<br><?= $info ?><br>

					<?php if( isset($_POST['add_user']) ) : ?>
						<FORM name="add" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
							<p>
								<label><?= $um_userform_login ?><br>
								<INPUT type="text" name="User" id="User">
								</label>
							</p>
							<p>
								<label><?= $um_userform_status ?><br>
								<select name="status">
									<option value="0">inactive</option>
									<option value="1">active</option>
								</select>
								</label>
							</p>
							<p>
								<label><?= $um_userform_pwd ?><br>
								<INPUT type="password" name="Password" id="Password">
								</label>
							</p>
							<p>
								<label><?= $um_userform_folder ?><br>
								<INPUT type="text" name="Dir" id="Dir" value="<?= $ftp_dir ?>">
								</label>
							</p>
							<p>
								<label><?= $um_userform_ullimit ?><br>
								<INPUT type="text" name="ULBandwidth" id="ULBandwidth" value="<?= $upload_speed ?>">
								</label>
							</p>
							<p>
								<label><?= $um_userform_dllimit ?><br>
								<INPUT type="text" name="DLBandwidth" id="DLBandwidth" value="<?= $download_speed ?>">
								</label>
							</p>
							<p>
								<label><?= $um_userform_permip ?><br>
								<INPUT type="text" name="ipaccess" id="ipaccess" value="<?= $permitted_ip ?>">
								</label>
							</p>
							<p>
								<label><?= $um_userform_quotasize ?><br>
								<INPUT type="text" name="quotasize" id="quotasize" value="<?= $quota_size ?>">
								</label>
							</p>
							<p>
								<label><?= $um_userform_quotafiles ?><br>
								<INPUT type="text" name="quotafiles" id="quotafiles" value="<?= $quota_files ?>">
								</label>
							</p>
							<p>
								<label>
								<INPUT type="submit" name="add" id="add" value="<?= $um_add_addbutton ?>">
								</label>
							</p>
							<p>
								<label>
								<INPUT type="submit" name="users" id="users" value="<?= $um_add_backbutton ?>">
								</label>
							</p>
						</FORM>
					<?php elseif( isset($_POST['add']) ) : ?>
						<br><br><br>
						<?php
						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if (isset ($_POST['User'])) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if (isset ($_POST['status'])) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if (isset ($_POST['Password'])) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if (isset ($_POST['Dir'])) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if (isset ($_POST['ULBandwidth'])) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if (isset ($_POST['DLBandwidth'])) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if (isset ($_POST['ipaccess'])) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}
						if (isset ($_POST['quotasize'])) {$quotasize = $_POST['quotasize']; if ($quotasize == '') {unset ($quotasize);}}
						if (isset ($_POST['quotafiles'])) {$quotafiles = $_POST['quotafiles']; if ($quotafiles == '') {unset ($quotafiles);}}

						// Если папка не была задана - выставляем значение по умолчанию
						if ( !isset($Dir) ) $Dir = $ftp_dir;

						// Если ограничение скорости аплода не было задано - выставляем значение по умолчанию
						if ( !isset($ULBandwidth) ) $ULBandwidth = $upload_speed;

						// Если ограничение скорости даунлода не было задано - выставляем значение по умолчанию
						if ( !isset($DLBandwidth) ) $DLBandwidth = $download_speed;

						// Если разрешённый IP-адрес не был задан - выставляем значение по умолчанию
						if ( !isset($ipaccess) ) $ipaccess = $permitted_ip;

						// Если размер квоты не был задан - выставляем значение по умолчанию
						if ( !isset($quotasize) ) $quotasize = $quota_size;

						// Если размер квоты не был задан - выставляем значение по умолчанию
						if ( !isset($quotafiles) ) $quotafiles = $quota_files;

						// Если все нужные поля заполнены, добавляем пользователя в базу pureftpd
						if ( isset($User) && isset($status) && isset($Password) &&
							isset($Dir) && isset($DLBandwidth) && isset($ULBandwidth) &&
							isset($ipaccess) && isset($quotasize) && isset($quotafiles) )
						{
							$result = mysql_query ("INSERT INTO ftpd (User,status,Password,Dir,ULBandwidth,DLBandwidth,ipaccess,QuotaSize,QuotaFiles)
								VALUES ('$User','$status',md5('$Password'),'$Dir','$ULBandwidth','$DLBandwidth','$ipaccess','$quotasize','$quotafiles')");
							if ($result == 'true')
							{
								echo "<p><strong>$um_add_presultok $User</strong></p>";
							}
							else
							{
								echo "<p><strong>$error $result</strong></p>";
							}
						}
						else
						{
							echo "<p><strong>$um_add_checkfields</strong></p>";
						} ?>
						<br>
						<form name="to_list" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
							<p>
								<label>
								<input type="submit" name="users" id="users" value="<?= $um_add_checkfieldsback ?>">
								</label>
							</p>
						</form>
					<?php elseif( isset($_POST['edit']) ) : ?>
						<br><br><br>
						<?php
						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if ( isset($_POST['User']) ) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if ( isset($_POST['status']) ) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if ( isset($_POST['Password']) ) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if ( isset($_POST['Dir']) ) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if ( isset($_POST['ULBandwidth']) ) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if ( isset($_POST['DLBandwidth']) ) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if ( isset($_POST['ipaccess']) ) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}
						if ( isset($_POST['quotasize']) ) {$quotasize = $_POST['quotasize']; if ($quotasize == '') {unset ($quotasize);}}
						if ( isset($_POST['quotafiles']) ) {$quotafiles = $_POST['quotafiles']; if ($quotafiles == '') {unset ($quotafiles);}}
						if ( isset($_POST['id']) ) {$id = $_POST['id']; if ($id == '') {unset ($id);}}

						// Запрашиваем из БД настройки пользователя
						$result = mysql_query ("SELECT * FROM ftpd WHERE id=$id");
						$array = mysql_fetch_array ($result);

						// Проверяем были ли внесены какие-то изменения
						if ( ($Dir != $array['Dir']) || ($User != $array['User']) || ($status != $array['status']) ||
							(isset ($Password)) || ($ULBandwidth != $array['ULBandwidth']) || ($DLBandwidth != $array['DLBandwidth']) ||
							($ipaccess != $array['ipaccess']) || ($quotasize != $array['QuotaSize']) || ($quotafiles != $array['QuotaFiles']) )
						{
							// Если изменена папка пользователя, вносим изменения в базу
							if (($Dir != $array['Dir']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET Dir='$Dir' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_folderok  $array[User]</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}

							}
							// Если изменено имя пользователя, вносим изменения в базу
							if (($User != $array['User']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET User='$User' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$array[User] $um_edit_loginok $User</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}

							// Если изменён статус пользователя, вносим изменения в базу
							if (($status != $array['status']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET status='$status' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>".$array['User']." $um_edit_statusok</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}

							// Если изменён пароль пользователя, вносим изменения в базу
							if (isset ($Password)) {$Password = md5($Password);
								if (($Password != $array['Password']) && isset ($id)) {
									$result = mysql_query ("UPDATE ftpd SET Password='$Password' WHERE id='$id'");
									if ($result == 'true') {echo "<p><strong>".$array['User']." $um_edit_passwdok</strong></p>";}
									else {echo "<p><strong>$error $result</strong></p>";}
								}
							}

							// Если изменено ограничение скорости загрузки, вносим изменения в базу
							if (($ULBandwidth != $array['ULBandwidth']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET ULBandwidth='$ULBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_ullimitok ".$array['User']."</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}

							// Если изменено ограничение скорости скачивания, вносим изменения в базу
							if (($DLBandwidth != $array['DLBandwidth']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET DLBandwidth='$DLBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_dllimitok ".$array['User']."</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}
							// Если изменён разрешенный IP адрес, вносим изменения в базу
							if (($ipaccess != $array['ipaccess']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET ipaccess='$ipaccess' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_permipok ".$array['User']."</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}
							// Если изменён размер квоты, вносим изменения в базу
							if (($quotasize != $array['QuotaSize']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET QuotaSize='$quotasize' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_quotasizeok ".$array['User']."</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}
							// Если изменён размер квоты, вносим изменения в базу
							if (($quotafiles != $array['QuotaFiles']) && isset ($id)) {
								$result = mysql_query ("UPDATE ftpd SET QuotaFiles='$quotafiles' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_quotafilesok ".$array['User']."</strong></p>";}
								else {echo "<p><strong>$error $result</strong></p>";}
							}
						}
						else
						{
							echo"<p><strong>$um_edit_nochanges</strong></p>";
						} ?>
						<br>
						<form name="to_list" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
							<p>
								<label>
								<input type="submit" name="users" id="users" value="<?= $um_edit_nochangesback ?>">
								</label>
							</p>
						</form>
					<?php else :
						if ( !isset($id) || isset($_POST['users']) ) {
						// Шапка таблицы
						?><p class="text_title" align="center"><strong><?= $um_t_title ?></strong></p>
							<div id="container">
							<table cellpadding="0" cellspacing="0" border="0" class="display" id="users_table">
								<thead>
									<tr>
										<th><?= $um_t_th1 ?></th>
										<th><?= $um_t_th2 ?></th>
										<th><?= $um_t_th3 ?></th>
										<th><?= $um_t_th4 ?></th>
										<th><?= $um_t_th5 ?></th>
										<th><?= $um_t_th6 ?></th>
										<th><?= $um_t_th7 ?></th>
										<th><?= $um_t_th8 ?></th>
									</tr>
								</thead>
								<tbody><?php

						$result = mysql_query ("SELECT * FROM ftpd");
						$myrow = mysql_fetch_array ($result);
						do {
							// Выводим список пользователей
									?><tr>
										<td align="center"><a href="edit_users.php?id=<?= $myrow['id'] ?>"><?= $myrow['User'] ?></a></td>
										<td align='center'><?= $myrow['status'] ?></td>
										<td><?= $myrow['Dir'] ?></td>
										<td align='center'><?= $myrow['ULBandwidth'] ?></td>
										<td align='center'><?= $myrow['DLBandwidth'] ?></td>
										<td align='center'><?= $myrow['ipaccess'] ?></td>
										<td align='center'><?= $myrow['QuotaSize'] ?></td>
										<td align='center'><?= $myrow['QuotaFiles'] ?></td>
									</tr><?php
						}
						while ( $myrow = mysql_fetch_array ($result) );

								?></tbody>
							</table>

							<br><br>

							<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td align="right">
										<form name="edit" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
											<p>
												<label>
												<input type="submit" name="add_user" id="add_user" value="<?= $um_adduserbutton ?>">
												</label>
											</p>
										</form>
									</td>
								</tr>
							</table><?php
						}
						// Эта часть используется, если выбран пользователь для редактирования
						else
						{
							$result = mysql_query ("SELECT * FROM ftpd WHERE id=$id");
							$myrow = mysql_fetch_array ($result);
							if ( $myrow['status'] == 0 ) {
								$select = '<option value="0" selected="selected">inactive</option><option value="1">active</option>';
							}
							else {
								$select = '<option value="0">inactive</option><option value="1" selected="selected">active</option>';
							}
							?><FORM name="form1" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
								<p>
									<label><?= $um_userform_login ?><br>
									<INPUT value="<?= $myrow['User'] ?>" type="text" name="User" id="User">
									</label>
								</p>
								<p>
									<label><?= $um_userform_status ?><br>
									<select name="status">
										<?= $select ?>
									</select>
									</label>
								</p>
								<p>
									<label><?= $um_userform_pwd ?><br>
									<INPUT value="" type="password" name="Password" id="Password">
									</label>
								</p>
								<p>
									<label><?= $um_userform_folder ?><br>
									<INPUT value="<?= $myrow['Dir'] ?>" type="text" name="Dir" id="Dir">
									</label>
								</p>
								<p>
									<label><?= $um_userform_ullimit ?><br>
									<INPUT value="<?= $myrow['ULBandwidth'] ?>" type="text" name="ULBandwidth" id="ULBandwidth">
									</label>
								</p>
								<p>
									<label><?= $um_userform_dllimit ?><br>
									<INPUT value="<?= $myrow['DLBandwidth'] ?>" type="text" name="DLBandwidth" id="DLBandwidth">
									</label>
								</p>
								<p>
									<label><?= $um_userform_permip ?><br>
									<INPUT value="<?= $myrow['ipaccess'] ?>" type="text" name="ipaccess" id="ipaccess">
									</label>
								</p>
								<p>
									<label><?= $um_userform_quotasize ?><br>
									<INPUT value="<?= $myrow['QuotaSize'] ?>" type="text" name="quotasize" id="quotasize">
									</label>
								</p>
								<p>
									<label><?= $um_userform_quotafiles ?><br>
									<INPUT value="<?= $myrow['QuotaFiles'] ?>" type="text" name="quotafiles" id="quotafiles">
									</label>
								</p>
								<br>
								<INPUT name="id" type="hidden" value="<?= $myrow['id'] ?>">
								<p>
									<label>
									<INPUT type="submit" name="edit" id="edit" value="<?= $um_edit_savebutton ?>">
									</label>
								</p>
								<p>
									<label>
									<INPUT type="submit" name="users" id="users" value="<?= $um_edit_backbutton ?>">
									</label>
								</p>
							</FORM><?php
						}
					endif; ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php include("blocks/footer.php"); ?>
		</tbody>
	</table>
	<script type="text/javascript" language="javascript" src="./media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="./media/js/jquery.dataTables.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$('#users_table').dataTable({
				"oLanguage": {
					"sUrl": "./media/dataTables.<?= $language ?>.txt"
				},
				"bJQueryUI": true,
				"sPaginationType": "full_numbers"
			});
		});
	</script>
</body>
</html>
