<?php
error_reporting(0);

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<HTML xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">
<HEAD>
<title>Pure-FTPd WebUI installation</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset='UTF-8'\" />
<link rel='shortcut icon' href='INSTALL/media/img/favicon.ico' />
<link href=\"INSTALL/media/stile.css\" rel=\"StyleSheet\" type=\"text/css\">
</head>
<body>
<table width=\"50%\" height=\"500px\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"main_border\">
<tbody>
<tr>
	<td>
");
		if (isset ($_POST['install'])) {
			echo("
				<form name=\"2\" method=\"post\" action=\"$PHP_SELF\">
					<tr>
						<td align=\"right\" width=\"10%\">
							<p align=\"left\">
								<b>Информация для подключения к базе данных Pure-FTPd</b></br></br>
								<label>MySQL хост</br>
									<input type=\"text\" name=\"mysql_host\" id=\"mysql_host\" value=\"localhost\"></br>
								</label>

								<label>Логин администратора</br>
									<input type=\"text\" name=\"mysql_admin\" id=\"mysql_admin\"></br>
								</label>

								<label>Пароль</br>
									<input type=\"password\" name=\"mysql_passwd\" id=\"mysql_passwd\"></br>
								</label>

								<label>База данных</br>
									<input type=\"text\" name=\"mysql_database\" id=\"mysql_database\" value=\"pureftpd\"></br></br>
								</label>
								
								<b>Пользователь MySQL для Pure-FTPd WebUI</b></br></br>
								
								<label>Имя пользователя</br>
									<input type=\"text\" name=\"mysql_webui_user\" id=\"mysql_webui_user\" value=\"purewebui\"></br>
								</label>
								
								<label>Пароль</br>
									<input type=\"password\" name=\"mysql_webui_passwd\" id=\"mysql_webui_passwd\" value=\"purewebui\"></br></br>
								</label>

								<label>
									<input type=\"submit\" name=\"install_mysql\" id=\"install_mysql\" value=\"Далее\">
								</label>
							</p>
						</td>
					</tr>
			");
		}

		elseif (isset ($_POST['install_mysql'])) {
			if (isset ($_POST['mysql_host']) && isset ($_POST['mysql_admin'])) {
				// Описание функций
				// Функция для создания MySQL пользователя WebUI
				function create_webui_user() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_admin = $_POST['mysql_admin'];
					$mysql_passwd = $_POST['mysql_passwd'];
					$mysql_database = $_POST['mysql_database'];
					$mysql_webui_user = $_POST['mysql_webui_user'];
					$mysql_webui_passwd = $_POST['mysql_webui_passwd'];
					$local_ip = $_SERVER['SERVER_ADDR'];
					$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
					mysql_select_db($mysql_database, $db_handler);
					$mysql_webui_create = mysql_query("GRANT ALL PRIVILEGES ON $mysql_database.* TO '$mysql_webui_user'@'$local_ip' IDENTIFIED BY '$mysql_webui_passwd';");
					if (!$mysql_webui_create) {
						echo("<p class=\"info\" align=\"left\">ОШИБКА: Не удалось создать пользователя $mysql_webui_user.</p>\n");
					}
					$mysql_webui_create2 = mysql_query("GRANT ALL PRIVILEGES ON $mysql_database.* TO '$mysql_webui_user'@'$mysql_host' IDENTIFIED BY '$mysql_webui_passwd';");
					if (!$mysql_webui_create2) {
						echo("<p class=\"info\" align=\"left\">ОШИБКА: Не удалось создать пользователя $mysql_webui_user.</p>\n");
					}
				}
				// Функция для создания таблицы Pure-FTPd
				function create_table_ftp() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_admin = $_POST['mysql_admin'];
					$mysql_passwd = $_POST['mysql_passwd'];
					$mysql_database = $_POST['mysql_database'];
					$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
					$ftp_users_table = "CREATE TABLE `ftpd` (
										  `id` int(11) NOT NULL AUTO_INCREMENT,
										  `User` varchar(16) NOT NULL DEFAULT '',
										  `status` enum('0','1') NOT NULL DEFAULT '0',
										  `Password` varchar(64) NOT NULL DEFAULT '',
										  `Uid` varchar(11) NOT NULL DEFAULT '2001',
										  `Gid` varchar(11) NOT NULL DEFAULT '2001',
										  `Dir` varchar(128) NOT NULL DEFAULT '/media/FTP',
										  `ULBandwidth` int(5) NOT NULL DEFAULT '0',
										  `DLBandwidth` int(5) NOT NULL DEFAULT '0',
										  `comment` tinytext NOT NULL,
										  `ipaccess` varchar(15) NOT NULL DEFAULT '*',
										  `QuotaSize` int(6) NOT NULL DEFAULT '0',
										  `QuotaFiles` int(11) NOT NULL DEFAULT '0',
										  PRIMARY KEY (`User`),
										  KEY `id` (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
					$users_table_create = FALSE;
					mysql_select_db($mysql_database,$db_handler);
					$users_table_create = mysql_query($ftp_users_table,$db_handler);
					if (!$users_table_create) {
						echo("<p class=\"error\" align=\"left\">ОШИБКА: Не удалось создать таблицу пользователей Pure-FTPd.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Создана таблица пользователей Pure-FTPd - \"ftpd\".</p>\n");
					}
				}
				// Функция создания таблицы WebUI
				function create_table_webui() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_admin = $_POST['mysql_admin'];
					$mysql_passwd = $_POST['mysql_passwd'];
					$mysql_database = $_POST['mysql_database'];
					$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
					$webui_users_table = "CREATE TABLE `userlist` (
										  `id` int(3) NOT NULL AUTO_INCREMENT,
										  `user` varchar(50) COLLATE utf8_bin NOT NULL,
										  `pass` varchar(50) COLLATE utf8_bin NOT NULL,
										  PRIMARY KEY (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
					$webui_table_create = FALSE;
					mysql_select_db($mysql_database, $db_handler);
					$webui_table_create = mysql_query($webui_users_table,$db_handler);
					if (!$webui_table_create) {
						echo("<p class=\"error\" align=\"left\">ОШИБКА: Не удалось создать таблицу пользователей Pure-FTPd WebUI.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Создана таблица пользователей Pure-FTPd WebUI - \"userlist\".</p>\n");
					}
				}
				// Функция для создания БД
				function create_database() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_admin = $_POST['mysql_admin'];
					$mysql_passwd = $_POST['mysql_passwd'];
					$mysql_database = $_POST['mysql_database'];
					$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
					$db_create = FALSE;
					$db_create = mysql_query("CREATE DATABASE $mysql_database",$db_handler);
					if (!$db_create) {
						echo("<p class=\"error\" align=\"left\">ОШИБКА: Не удалось создать базу данных. Возможно недостаточно прав для данной операции.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Создана база данных Pure-FTPd - \"$mysql_database\".</p>\n");
					}
				}
				// Функция создания конфига Pure-FTPd WebUI
				function create_webui_conf() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_database = $_POST['mysql_database'];
					$mysql_webui_user = $_POST['mysql_webui_user'];
					$mysql_webui_passwd = $_POST['mysql_webui_passwd'];
					$webui_config = "blocks/db_connect.php";
					$fh = fopen($webui_config, 'w') or die("can't open file");
					$stringData = "<?\n";
					fwrite($fh, $stringData);
					$stringData = "\$db = mysql_connect (\"$mysql_host\", \"$mysql_webui_user\", \"$mysql_webui_passwd\");\n";
					fwrite($fh, $stringData);
					$stringData = "mysql_select_db (\"$mysql_database\", \$db);\n";
					fwrite($fh, $stringData);
					$stringData = "?>\n";
					fwrite($fh, $stringData);
					fclose($fh);
				}
				$mysql_host = $_POST['mysql_host'];
				$mysql_admin = $_POST['mysql_admin'];
				$mysql_passwd = $_POST['mysql_passwd'];
				$mysql_database = $_POST['mysql_database'];
				$db_handler = FALSE;
				$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);

				if (!$db_handler) {
					echo("<p class=\"error\" align=\"left\">ОШИБКА: Не удалось подключиться к MySQL серверу.</p>\n");
				}

				if (isset ($mysql_database) && $mysql_database != '') {
					$db_con = FALSE;
					$db_con = mysql_select_db($mysql_database, $db_handler);
					if (!$db_con) {
						if (($err = mysql_errno()) == 1049) {
							create_database();
							create_table_ftp();
							create_table_webui();
							create_webui_user();
						}
					}
					else {
						echo("<p class=\"info\" align=\"left\">Используется существующая база данных $mysql_database.</p>\n");
						create_webui_user();
						$test_ftp_table = "SELECT * FROM ftpd";
						$result = mysql_query($test_ftp_table);
						if (!$result) {
							create_webui_user();
							create_table_ftp();
						}
						else {
							echo("<p class=\"info\" align=\"left\">Используется существующая таблица пользователей Pure-FTPd.</p><p class=\"warning\" align=\"left\">ВНИМАНИЕ структура таблицы может отличаться.</p>\n");
						}
						$test_webui_table = "SELECT * FROM userlist";
						$result = mysql_query($test_webui_table);
						if (!$result) {
							create_table_webui();
						}
						else {
							echo("<p class=\"info\" align=\"left\">Используется существующая таблица пользователей Pure-FTPd WebUI.</p><p class=\"warning\" align=\"left\">ВНИМАНИЕ структура таблицы может отличаться.</p>\n");
						}
					}
				}
				// Здесь будет создание конфига для конекта к базе //
				create_webui_conf();
				echo("
					<form name=\"3\" method=\"post\" action=\"$PHP_SELF\">
                    <tr>
                        <td align=\"right\" width=\"10%\">
                        	<p align=\"left\" class=\"text_title\" >Для создания администратора Pure-FTPd WebUI </br> требуется заполнить следующие поля</p>
                            <p align=\"left\">
                            	                            
                                <label>Логин администратора </br> Pure-FTPd WebUI</br>
                                    <input type=\"text\" name=\"webui_admin\" id=\"webui_admin\"></br>
                                </label>

                                <label>Пароль администратора</br>
                                    <input type=\"password\" name=\"webui_admin_passwd\" id=\"webui_admin_passwd\"></br></br>
                                </label>

                                <label>
                                    <input type=\"submit\" name=\"add_webui_admin\" id=\"add_webui_admin\" value=\"Далее\">
                                </label>

									<input type=\"hidden\" name=\"mysql_host\" id=\"mysql_host\" value=\"$mysql_host\"></br>
									<input type=\"hidden\" name=\"mysql_admin\" id=\"mysql_admin\" value=\"$mysql_admin\"></br>
									<input type=\"hidden\" name=\"mysql_passwd\" id=\"mysql_passwd\" value=\"$mysql_passwd\"></br>
									<input type=\"hidden\" name=\"mysql_database\" id=\"mysql_database\" value=\"$mysql_database\"></br></br>
									<input type=\"hidden\" name=\"mysql_webui_user\" id=\"mysql_webui_user\"></br>
									<input type=\"hidden\" name=\"mysql_webui_passwd\" id=\"mysql_webui_passwd\"></br></br>
                            </p>
                        </td>
                    </tr>
				");
			}
		}
		
		elseif (isset ($_POST['add_webui_admin'])) {
			if (isset ($_POST['webui_admin']) && isset ($_POST['webui_admin_passwd'])) {
				$mysql_host = $_POST['mysql_host'];
				$mysql_admin = $_POST['mysql_admin'];
				$mysql_passwd = $_POST['mysql_passwd'];
				$mysql_database = $_POST['mysql_database'];
				$webui_admin = $_POST['webui_admin'];
				$webui_admin_passwd = $_POST['webui_admin_passwd'];
				$db_handler = FALSE;
				$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
				$db_con = mysql_select_db($mysql_database, $db_handler);
				$add_user_queue = "INSERT INTO userlist (user,pass) VALUES ('$webui_admin',md5('$webui_admin_passwd'))";
				$result = mysql_query($add_user_queue);
				if ($result != FALSE) {
					echo("<p class=\"info\" align=\"left\">Учётная запись администратора добавлена. </br> Pure-FTPd WebUI доступен по <a href=\"/pure-ftpd-webui\">ссылке</a>.</p>\n");
				}
				else {
					echo("<p class=\"error\" align=\"left\">Ошибка при выполнении операции, пользователь не создан.</p>\n");
				}
			}
			else {
				echo("<p class=\"error\" align=\"left\">Заполнены не все поля!</p>\n");
			}
		}

		else {
			echo("
				<p align=\"center\" class=\"text_title\" >Вас приветствует мастер установки </br> Pure-FTPd WebUI beta 0.1.0</p>

				<form name=\"1\" method=\"post\" action=\"$PHP_SELF\">
					<p align=\"center\">
						<label>
							<input type=\"submit\" name=\"install\" id=\"install\" value=\"Установить\">
						</label>
					</p>
				</form>
			");
		}
echo("
	<tr valign=\"bottom\">
		<td>
			<p class=\"link\"><a href=\"http://mazay.home.dyndns.org/redmine/projects/pure-ftpd-webui/wiki\">Pure-FTPd WebUI Wiki</a></p>
		</td>
	</tr>
	</td>
</tr>
</tbody>
</table>
</body>
</html>");
?>
