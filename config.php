Pure-FTPd WebUI configuration file

<?php

// Database connection
$mysql_host = 'localhost';
$mysql_user = 'purewebui';
$mysql_pass = 'purewebui';
$database = 'pureftpd';
$users_table = 'ftpd';
$admins_table = 'userlist';

// Pure-FTPd path
$daemon_path = '/etc/init.d/pure-ftpd';
$config_path = '/etc/pure-ftpd/pure-ftpd.conf';

?>