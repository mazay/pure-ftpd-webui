<?php
$master = "daemon_control.php";
include("./config.php");
include("./blocks/db_connect.php"); /*Подключаемся к базе*/
include("./blocks/lock.php");
include("./blocks/default.php");
$user = $_SERVER['PHP_AUTH_USER'];
$info = '';

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
	<title><?= $dc_title ?></title>

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

						<?php if ( isset($_POST['edit']) )
						{
							// Путь к файлу
							$filename = $pureftpd_conf_path;
							// Открываем файл для чтения
							$handle = fopen($filename, "r");
							// Вытаскиваем содержимое файла
							$contents = fread($handle, filesize($filename));
							// Выводим форму для редактирования
							?><form method="post" action="./daemon_control.php?area=edit">
								<p align="center">
									<input type="hidden" name="file" value="<?= $filename ?>">
									<textarea name="content" cols="100" rows="30"><?= $contents ?></textarea>
									<br><br>
									<input type="submit" name="daemon" value="<?= $dc_confeditbackbutton ?>">
									<input type="submit" name="update" value="<?= $dc_confeditsavebutton ?>">
								</p>
							</form><?php
							// Закрываем файл
							fclose($handle);
						}
 
						// Эта часть используется, если была нажата кнопка "Сохранить"
						elseif ( isset($_POST['update']) )
						{
							$filename = $_POST['file'];
							// Проверяем есть ли права на запись в файл
							if ( is_writable($filename) )
							{
								// Открываем файл для записи
								$handle = fopen($filename, "w+");
								// Записываем изменения в файл
								fwrite($handle, $_POST['content']);
								// Закрываем файл
								fclose($handle);
								// Выводим сообщение о удачном завершении операции
								echo '<p align="center"><strong>'.$dc_confeditsuccess.'</strong></p>';
								// Если прав для записи в файл недостаточно, выводим сообщение об ошибке
							}
							else
							{
								echo '<p align="center"><strong>'.$dc_confeditnorights.'</strong></p></br>';
							}

							?><form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
								<p align="center">
									<input type="submit" name="edit" value="<?= $dc_confeditbackbutton ?>">
									<input type="submit" name="daemon" value="<?= $dc_daemoncontrol ?>">
								</p>
							</form><?php
						}

						// Эта часть используется, если была нажата кнопка "Выполнить"
						elseif ( isset($_POST['control']) )
						{
							// Проверяем какая была дана команда
							if ( isset($_POST['daemon_ctl']) && $_POST['daemon_ctl'] != '' )
							{
								$daemon_ctl = $_POST['daemon_ctl'];

								// Если была дана команда "старт" - стартуем демона
								if ( $daemon_ctl == 'start' ) {
									$result = shell_exec("sudo $pureftpd_init_script_path start");
									echo("<p>$result</p>");}

								// Если была дана комнда "стоп" - останавливаем демона
								elseif ( $daemon_ctl == 'stop' ) {
									$result = shell_exec("sudo $pureftpd_init_script_path stop");
									echo("<p>$result</p>");}

								// Если была дана команда "рестарт" - рестартуем демона
								elseif ( $daemon_ctl == 'restart' ) {
									$result = shell_exec("sudo $pureftpd_init_script_path stop");
									echo("<p>$result</p>");
									$result = shell_exec("sudo $pureftpd_init_script_path start");
									echo("<p>$result</p>");}
							}

							// Если ни один вариант не верен - выдаём ошибку
							else { echo("<p><strong>$dc_wrongcommand</strong></p>"); }

							?><br>
							<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
								<p align="center">
									<input type="submit" name="daemon" value="<?= $dc_wrongcommandback ?>">
								</p>
							</form><?php
						}

						else
						{
							if ( isset($_POST['daemon']) || !isset($_POST['']) )
							{
							?><p class="text_title" align="center"><?= $dc_dctitle ?></p>
							<form name="dc" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
								<table align="center">
									<tr>
										<td width="150px">
											<p>
												<label>
												<input type="radio" name="daemon_ctl" id="start" value="start"> Start
												</label>
											</p>
											<p>
												<label>
												<input type="radio" name="daemon_ctl" id="stop" value="stop"> Stop
												</label>
											</p>
											<p>
												<label>
												<input type="radio" name="daemon_ctl" id="restart" value="restart"> Restart
												</label>
											</p>
										</td>
										<td>
											<p>
												<label>
												<input type="submit" name="control" value="<?= $dc_dcperformbutton ?>">
												</label>
											</p>
										</td>
									</tr>
								</table>
							</form>
							<br>
							<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
								<p align="center">
									<input type="submit" name="edit" value="<?= $dc_dceditconfig ?>">
								</p>
							</form><?php
							}
						}
							?><br><br>
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
