<?php
error_reporting(0);

include ("version.php");
echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<HTML xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">
<HEAD>
<title>Pure-FTPd WebUI installation</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
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
								<b>Setup connection to Pure-FTPd database</b></br></br>
								<label>MySQL host</br>
									<input type=\"text\" name=\"mysql_host\" id=\"mysql_host\" value=\"localhost\"></br>
								</label>

								<label>MySQL administrator login</br>
									<input type=\"text\" name=\"mysql_admin\" id=\"mysql_admin\"></br>
								</label>

								<label>MySQL administrator password</br>
									<input type=\"password\" name=\"mysql_passwd\" id=\"mysql_passwd\"></br>
								</label>

								<label>Database</br>
									<input type=\"text\" name=\"mysql_database\" id=\"mysql_database\" value=\"pureftpd\"></br></br>
								</label>
								
								<b>MySQL user for Pure-FTPd WebUI</b></br></br>
								
								<label>Login</br>
									<input type=\"text\" name=\"mysql_webui_user\" id=\"mysql_webui_user\" value=\"purewebui\"></br>
								</label>
								
								<label>Password</br>
									<input type=\"password\" name=\"mysql_webui_passwd\" id=\"mysql_webui_passwd\" value=\"purewebui\"></br></br>
								</label>

								<label>
									<input type=\"submit\" name=\"install_mysql\" id=\"install_mysql\" value=\"Next\">
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
						echo("<p class=\"info\" align=\"left\">ERROR: Can not create user $mysql_webui_user.</p>\n");
					}
					$mysql_webui_create2 = mysql_query("GRANT ALL PRIVILEGES ON $mysql_database.* TO '$mysql_webui_user'@'$mysql_host' IDENTIFIED BY '$mysql_webui_passwd';");
					if (!$mysql_webui_create2) {
						echo("<p class=\"info\" align=\"left\">ERROR: Can not create user $mysql_webui_user.</p>\n");
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
						echo("<p class=\"error\" align=\"left\">ERROR: Can not create users table for Pure-FTPd.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Created users table for Pure-FTPd.</p>\n");
					}
				}
				// Функция создания таблицы settings
				function create_table_settings() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_admin = $_POST['mysql_admin'];
					$mysql_passwd = $_POST['mysql_passwd'];
					$mysql_database = $_POST['mysql_database'];
					$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
					$settings_table = "CREATE TABLE `settings` (
														  `name` varchar(50) NOT NULL DEFAULT '',
														  `value` varchar(255) NOT NULL DEFAULT '',
														  PRIMARY KEY (`name`)
														) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
					$settings_table_create = FALSE;
					mysql_select_db($mysql_database, $db_handler);
					$settings_table_create = mysql_query($settings_table,$db_handler);
					if (!$settings_table_create) {
						echo("<p class=\"error\" align=\"left\">ERROR: Can not create settings table for Pure-FTPd WebUI.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Created settings table for Pure-FTPd WebUI.</p>\n");
						$settings_table_content = "INSERT INTO `settings` (`name`, `value`)
														VALUES
															('ftp_dir','/media/FTP'),
															('upload_speed','0'),
															('download_speed','0'),
															('quota_size','0'),
															('quota_files','0'),
															('permitted_ip','*'),
															('pureftpd_conf_path','/etc/pure-ftpd/pure-ftpd.conf'),
															('pureftpd_init_script_path','/etc/init.d/pure-ftpd');";
						$settings_table_content_create = FALSE;
						mysql_select_db($mysql_database, $db_handler);
						$settings_table_content_create = mysql_query($settings_table_content,$db_handler);
						if (!$settings_table_create) {
							echo("<p class=\"error\" align=\"left\">ERROR: Can not create content of settings table for Pure-FTPd WebUI.</p>\n");
						}
						else {
							echo("<p class=\"info\" align=\"left\">Created content of settings table for Pure-FTPd WebUI.</p>\n");
						}
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
										  `language` varchar(50) COLLATE utf8_bin DEFAULT NULL,
										  PRIMARY KEY (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
					$webui_table_create = FALSE;
					mysql_select_db($mysql_database, $db_handler);
					$webui_table_create = mysql_query($webui_users_table,$db_handler);
					if (!$webui_table_create) {
						echo("<p class=\"error\" align=\"left\">ERROR: Can not create users table for Pure-FTPd WebUI.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Created users table for Pure-FTPd WebUI.</p>\n");
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
						echo("<p class=\"error\" align=\"left\">ERROR: Can not create database. Maybe you don't have permissions to create database.</p>\n");
					}
					else {
						echo("<p class=\"info\" align=\"left\">Created Pure-FTPd database - \"$mysql_database\".</p>\n");
					}
				}
				// Функция создания конфига Pure-FTPd WebUI
				function create_webui_conf() {
					$mysql_host = $_POST['mysql_host'];
					$mysql_database = $_POST['mysql_database'];
					$mysql_webui_user = $_POST['mysql_webui_user'];
					$mysql_webui_passwd = $_POST['mysql_webui_passwd'];
					$webui_config = "config.php";
					$fh = fopen($webui_config, 'w') or die("can't open file");
					$stringData = "<?php\n";
					fwrite($fh, $stringData);
					$stringData = "\$mysql_host = \"$mysql_host\";\n\$mysql_webui_user = \"$mysql_webui_user\";\n\$mysql_webui_passwd = \"$mysql_webui_passwd\";\n\$mysql_database = \"$mysql_database\";\n";
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
					echo("<p class=\"error\" align=\"left\">ERROR: Can not connect to MySQL server.</p>\n");
				}

				if (isset ($mysql_database) && $mysql_database != '') {
					$db_con = FALSE;
					$db_con = mysql_select_db($mysql_database, $db_handler);
					if (!$db_con) {
						if (($err = mysql_errno()) == 1049) {
							create_database();
							create_table_ftp();
							create_table_settings();
							create_table_webui();
							create_webui_user();
						}
					}
					else {
						echo("<p class=\"info\" align=\"left\">Using already existing database $mysql_database.</p>\n");
						create_webui_user();
						$test_ftp_table = "SELECT * FROM ftpd";
						$result = mysql_query($test_ftp_table);
						if (!$result) {
							create_webui_user();
							create_table_ftp();
						}
						else {
							echo("<p class=\"info\" align=\"left\">Using already existing Pure-FTPd users table.</p><p class=\"warning\" align=\"left\">WARNING the structure of the table may be differ.</p>\n");
						}
						$test_settings_table = "SELECT * FROM settings";
						$result = mysql_query($test_settings_table);
						if (!$result) {
							create_table_settings();
						}
						else {
							echo("<p class=\"info\" align=\"left\">Using already existing Pure-FTPd WebUI settings table.</p><p class=\"warning\" align=\"left\">WARNING the structure of the table may be differ.</p>\n");
						}
						$test_webui_table = "SELECT * FROM userlist";
						$result = mysql_query($test_webui_table);
						if (!$result) {
							create_table_webui();
						}
						else {
							echo("<p class=\"info\" align=\"left\">Using already existing Pure-FTPd WebUI users table.</p><p class=\"warning\" align=\"left\">WARNING the structure of the table may be differ.</p>\n");
						}
					}
				}
				// Здесь будет создание конфига для конекта к базе //
				create_webui_conf();
				echo("
					<form name=\"3\" method=\"post\" action=\"$PHP_SELF\">
                    <tr>
                        <td align=\"right\" width=\"10%\">
                        	<p align=\"left\" class=\"text_title\" >To create Pure-FTPd WebUI administrator</br>fill in the following fields</p>
                            <p align=\"left\">
                            	                            
                                <label>Pure-FTPd WebUI administrator login</br>
                                    <input type=\"text\" name=\"webui_admin\" id=\"webui_admin\"></br>
                                </label>

                                <label>Pure-FTPd WebUI administrator password</br>
                                    <input type=\"password\" name=\"webui_admin_passwd\" id=\"webui_admin_passwd\"></br>
                                </label>
                                
                                <label>Pure-FTPd WebUI administrator language</br>
                                    <select name=\"webui_admin_language\">");
                                    $directory = "lang/";
									$languages = glob("" . $directory . "*.php");
									foreach($languages as $lang) {
										$rest = substr($lang, 5);
										$lng = substr($rest, 0, -4);
										echo("<option>$lng</option>");
									}
									echo("</select></label></br></br>
                                </label>

                                <label>
                                    <input type=\"submit\" name=\"add_webui_admin\" id=\"add_webui_admin\" value=\"next\">
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
			if (isset ($_POST['webui_admin']) && isset ($_POST['webui_admin_passwd']) && isset ($_POST['webui_admin_language'])) {
				$mysql_host = $_POST['mysql_host'];
				$mysql_admin = $_POST['mysql_admin'];
				$mysql_passwd = $_POST['mysql_passwd'];
				$mysql_database = $_POST['mysql_database'];
				$webui_admin = $_POST['webui_admin'];
				$webui_admin_passwd = $_POST['webui_admin_passwd'];
				$webui_admin_language = $_POST['webui_admin_language'];
				$db_handler = FALSE;
				$db_handler = mysql_connect($mysql_host,$mysql_admin,$mysql_passwd);
				$db_con = mysql_select_db($mysql_database, $db_handler);
				$add_user_queue = "INSERT INTO userlist (user,pass,language) VALUES ('$webui_admin',md5('$webui_admin_passwd'),'$webui_admin_language')";
				$result = mysql_query($add_user_queue);
				if ($result != FALSE) {
					echo("<p class=\"info\" align=\"left\">The administrator account is added. </br> Pure-FTPd WebUI available <a href=\"/pure-ftpd-webui\">here</a>.</p>\n");
				}
				else {
					echo("<p class=\"error\" align=\"left\">An error occurred while performing an operation, the user is not added.</p>\n");
				}
			}
			else {
				echo("<p class=\"error\" align=\"left\">Not filled in all fields!</p>\n");
			}
		}

		else {
			echo("
				<p align=\"center\" class=\"text_title\" >Welcome to </br> Pure-FTPd WebUI $version installer</p>

				<form name=\"1\" method=\"post\" action=\"$PHP_SELF\">
					<p align=\"center\">
						<label>
							<input type=\"submit\" name=\"install\" id=\"install\" value=\"Install\">
						</label>
					</p>
				</form>
			");
		}
echo("
	<tr valign=\"bottom\">
		<td>
			<p class=\"link\"><a href=\"http://pure-ftpd-webui.org/projects/pure-ftpd-webui/wiki\">Pure-FTPd WebUI Wiki</a></p>
		</td>
	</tr>
	</td>
</tr>
</tbody>
</table>
</body>
</html>");
?>
