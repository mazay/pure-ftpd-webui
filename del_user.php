<?php
$master = "del_user.php";
include ("lock.php");
include ("blocks/db_connect.php"); /*Подлкючаемся к базе*/
if (isset ($_GET['id'])) {$id = $_GET['id'];}

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<head>");
echo("<title> Удаление пользователей </title>");
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
                 <p class="text_title">Выберите пользователя для удаления</p>
                 <form action="drop_user.php" method="post">
                 <?php
                   $result = mysql_query ("SELECT User,id FROM ftpd");
                   $myrow = mysql_fetch_array ($result);
                   do
                    {
                    printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>", $myrow["id"], $myrow["User"]);
                    }
                   while ($myrow = mysql_fetch_array ($result));
                  ?>
                  <p><input name="submit" type="submit" value="Удалить пользователя"></p>
                  </form><br><br>
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
