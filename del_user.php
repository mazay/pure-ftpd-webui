<?php
$master = "del_user.php";
include("./config.php");
include("./blocks/db_connect.php"); /*Подлкючаемся к базе*/
include("./blocks/lock.php");
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
    <title><?= $del_title ?></title>

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

                            <p class="text_title" align="center"><?= $del_selecttitle ?></p>

                            <form action="drop_user.php" method="post"><?php
                                $i = 0;
                                $result = mysql_query("SELECT User,id FROM ftpd");
                                $myrow = mysql_fetch_array($result);
                                do
                                {
                                    echo '<p><input name="id" type="radio" id="name_'.$i.'" value="'.$myrow['id'].'"><label for="name_'.$i.'"> '.$myrow['User'].'</label></p>';
                                    $i++;
                                }
                                while ( $myrow = mysql_fetch_array($result) ); ?>
                                <p><input name="submit" type="submit" value="<?= $del_button ?>"></p>
                            </form>
                            <br><br>
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
