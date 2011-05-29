<?php
$master = "daemon_control.php";
include ("lock.php");
include ("blocks/db_connect.php"); /*Подключаемся к базе*/

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<head>");
echo("<title> Управление Pure-FTPd </title>");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset='UTF-8'\" />");
?>

<link href="stile.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_page.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_table_jui.css" rel="StyleSheet" type="text/css">
<link href="media/css/jquery-ui-1.7.2.custom.css" rel="StyleSheet" type="text/css">
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
</table><br><br>

		
			<?php
				// Эта часть используется, если была нажата кнопка "Править конфиг"
				if($_POST['edit']) {
					// Путь к файлу
					$filename = "/etc/pure-ftpd/pure-ftpd.conf";
					// Открываем файл для чтения
					$handle = fopen($filename, "r");
					// Вытаскиваем содержимое файла
					$contents = fread($handle, filesize($filename));
					// Выводим форму для редактирования
					echo "	<form method='post' action='daemon_control.php?area=edit'>
								<p align='center'>
									<input type='hidden' name='file' value='$filename'>
									<textarea name='content' cols='100' rows='30'>".$contents."</textarea><br><br>
									<input type='submit' name='daemon' value='Назад'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
									<input type='submit' name='update' value='Сохранить'>
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
						echo "<p align='center'><strong>Изменения сохранены, для принятия изменений перезапустите демон Pure-FTPd.</strong></p>";
					// Если прав для записи в файл недостаточно, выводим сообщение об ошибке
					} else {
				echo "<p align='center'><strong>Конфиг небыл изменён, нехватает прав для записи.</strong></p><br>";}

				echo "	<form method='post' action='$PHP_SELF'>
							<p align='center'>
								<input type='submit' name='edit' value='Назад'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type='submit' name='daemon' value='Управление демоном'>
							</p>
						</form>";
				}

				// Эта часть используется, если была нажата кнопка "Выполнить"
				elseif($_POST['control']) {
					// Проверяем какая была дана команда
					if (isset ($_POST['start'])) {$start = $_POST['start']; if ($start == '') {unset ($start);}}
					if (isset ($_POST['stop'])) {$stop = $_POST['stop']; if ($stop == '') {unset ($stop);}}
					if (isset ($_POST['restart'])) {$restart = $_POST['restart']; if ($restart == '') {unset ($restart);}}

					// Если была дана команда "старт" - стартуем демона
					if ((isset ($start)) and ($start == 'start')) {
						$result = shell_exec("sudo /etc/init.d/pure-ftpd start");
						echo("<p>$result</p>");}

					// Если была дана комнда "стоп" - останавливаем демона
					elseif ((isset ($stop)) and ($stop == 'stop')) {
						$result = shell_exec("sudo /etc/init.d/pure-ftpd stop");
						echo("<p>$result</p>");}

					// Если была дана команда "рестарт" - рестартуем демона
					elseif ((isset ($restart)) and ($restart == 'restart')) {
						$result = shell_exec("sudo /etc/init.d/pure-ftpd stop");
						echo("<p>$result</p>");
						$result = shell_exec("sudo /etc/init.d/pure-ftpd start");
						echo("<p>$result</p>");}

					// Если ни один вариант не верен - выдаём ошибку
					else {echo("<p><strong>Передана неверная команда демону Pure-FTPd</strong></p>");}

					echo "<br>
							<form method='post' action='$PHP_SELF'>
								<p align='center'>
									<input type='submit' name='daemon' value='Назад'>
								</p>
							</form>";
				}

				else {
					if ((isset ($_POST['daemon'])) || (!isset ($_POST['']))) {
					echo "
							<p class='text_title' align='center'>Управление демоном Pure-FTPd</p>
							<form name='dc' method='post' action='$PHP_SELF'><table align='center'><tr>
								<td width='150px'><p>
									<label>
									<input type='radio' name='start' id='start' value='start'> Start
									</label>
								</p>
								<p>
									<label>
									<input type='radio' name='stop' id='stop' value='stop'> Stop
									</label>
								</p>
								<p>
									<label>
									<input type='radio' name='restart' id='restart' value='restart'> Restart
									</label>
								</p></td>
								<td><p>
									<label>
									<input type='submit' name='control' value='Выполнить'>
									</label>
								</p></td></tr></table></form><br>

							<form method='post' action='$PHP_SELF'>
								<p align='center'>
									<input type='submit' name='edit' value='Править конфиг'>
								</p>
							</form>";}
				}
			?>

				<br><br>
               </td>
            </tr>
          </table>
        </td>
       </tr>
<? include("blocks/footer.php"); ?>
  </tbody>
</table>
</body>
</html>
