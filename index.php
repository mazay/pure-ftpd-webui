<?php
$master = "index.php";
include("./config.php");
include("./blocks/db_connect.php");
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
    <title><?= $ua_title ?></title>
    <meta http-equiv="refresh" content="30">

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

                            <p class="text_title" align="center"><?= $ua_t_title ?></p>

                            <div id="container">
                                <div class="demo_jui">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                        <thead>
                                        <tr>
                                            <th><?= $ua_t_th1 ?></th>
                                            <th><?= $ua_t_th2 ?></th>
                                            <th><?= $ua_t_th3 ?></th>
                                            <th><?= $ua_t_th4 ?></th>
                                        </tr>
                                        </thead>
                                        <tbody><?php // Активные пользователи
                                            $result = shell_exec("$pureftpwho_path");
                                            $array = explode("\n", $result);
                                            foreach ( $array as $users )
                                            {
                                                if (($users != "") and (substr($users, 0, 3) != "+--") and (substr($users, 2, 3) != "PID"))
                                                {
                                                    list($tmp, $pid, $user, $speed, $stat, $file) = explode("|", $users);
                                                    ?><tr>
                                                        <td class="center"><?= $user ?></td>
                                                        <td class="center"><?= $speed ?></td>
                                                        <td class="center"><?= $stat ?></td>
                                                        <td><?= $file ?></td>
                                                    </tr><?php
                                                }
                                            }
                                        ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php include("blocks/footer.php"); ?>
        </tbody>
    </table>

    <!-- Javascript-->
    <script type="text/javascript" language="javascript" src="./media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="./media/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#example').dataTable({
                "oLanguage": {
                    "sUrl": "media/dataTables.<?= $language ?>.txt"
                },
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bSort": false
            });
        });
    </script>
</body>
</html>