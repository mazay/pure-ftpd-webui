<?php
$master = "daemon_control.php";
include ("blocks/default.php");
include ("blocks/lock.php");
include ("blocks/db_connect.php"); /*Подключаемся к базе*/
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
echo("<title>$dc_title</title>");
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
				// Эта часть используется, если была нажата кнопка "Править конфиг"
				if($_POST['edit']) {
					// Путь к файлу
					$filename = "$pureftpd_conf_path";
					// Открываем файл для чтения
					$handle = fopen($filename, "r");
					// Вытаскиваем содержимое файла
					$contents = fread($handle, filesize($filename));
					// Выводим форму для редактирования
					echo "	<form method='post' action='daemon_control.php?area=edit'>
								<p align='center'>
									<input type='hidden' name='file' value='$filename'>
									<textarea name='content' cols='100' rows='30'>".$contents."</textarea></br></br>
									<input type='submit' name='daemon' value='$dc_confeditbackbutton'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
									<input type='submit' name='update' value='$dc_confeditsavebutton'>
								</p>
							</form>";
					// Закрываем файл
					fclose($handle);
				}
 
				// Эта часть используется, если была нажата кнопка "Сохранить"
				elseif($_POST['update']) {
					$filename = $_POST['file'];
					// Проверяем есть ли права на запись в файл
					if(is_writable($filename)) {
						// Открываем файл для записи
						$handle = fopen($filename, "w+");
						// Записываем изменения в файл
						fwrite($handle, $_POST['content']);
						// Закрываем файл
						fclose($handle);
						// Выводим сообщение о удачном завершении операции
						echo "<p align='center'><strong>$dc_confeditsuccess</strong></p>";
					// Если прав для записи в файл недостаточно, выводим сообщение об ошибке
					} else {
				echo "<p align='center'><strong>$dc_confeditnorights</strong></p></br>";}

				echo "	<form method='post' action='$PHP_SELF'>
							<p align='center'>
								<input type='submit' name='edit' value='$dc_confeditbackbutton'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type='submit' name='daemon' value='$dc_daemoncontrol'>
							</p>
						</form>";
				}

				// Эта часть используется, если была нажата кнопка "Выполнить"
				elseif($_POST['control']) {
					// Проверяем какая была дана команда
					if (isset ($_POST['daemon_ctl'])) {$daemon_ctl = $_POST['daemon_ctl']; if ($daemon_ctl == '') {unset ($daemon_ctl);}}

					// Если была дана команда "старт" - стартуем демона
					if ($daemon_ctl == 'start') {
						$result = shell_exec("sudo $pureftpd_init_script_path start");
						echo("<p>$result</p>");}

					// Если была дана комнда "стоп" - останавливаем демона
					elseif ($daemon_ctl == 'stop') {
						$result = shell_exec("sudo $pureftpd_init_script_path stop");
						echo("<p>$result</p>");}

					// Если была дана команда "рестарт" - рестартуем демона
					elseif ($daemon_ctl == 'restart') {
						$result = shell_exec("sudo $pureftpd_init_script_path stop");
						echo("<p>$result</p>");
						$result = shell_exec("sudo $pureftpd_init_script_path start");
						echo("<p>$result</p>");}

					// Если ни один вариант не верен - выдаём ошибку
					else {echo("<p><strong>$dc_wrongcommand</strong></p>");}

					echo "</br>
							<form method='post' action='$PHP_SELF'>
								<p align='center'>
									<input type='submit' name='daemon' value='$dc_wrongcommandback'>
								</p>
							</form>";
				}

				else {
					if ((isset ($_POST['daemon'])) || (!isset ($_POST['']))) {
					echo "
							<p class='text_title' align='center'>$dc_dctitle</p>
							<form name='dc' method='post' action='$PHP_SELF'><table align='center'><tr>
								<td width='150px'><p>
									<label>
									<input type='radio' name='daemon_ctl' id='start' value='start'> Start
									</label>
								</p>
								<p>
									<label>
									<input type='radio' name='daemon_ctl' id='stop' value='stop'> Stop
									</label>
								</p>
								<p>
									<label>
									<input type='radio' name='daemon_ctl' id='restart' value='restart'> Restart
									</label>
								</p></td>
								<td><p>
									<label>
									<input type='submit' name='control' value='$dc_dcperformbutton'>
									</label>
								</p></td></tr></table></form></br>

							<form method='post' action='$PHP_SELF'>
								<p align='center'>
									<input type='submit' name='edit' value='$dc_dceditconfig'>
								</p>
							</form>";}
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
