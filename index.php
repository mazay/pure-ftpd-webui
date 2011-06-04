<?php
$master = "index.php";
include ("blocks/lock.php");

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<HTML xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<HEAD>");
echo("<title>Мониторинг активности</title>");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset='UTF-8'\" />");
echo("<meta http-equiv='refresh' content='30'/>");
?>
<link rel='shortcut icon' href='img/favicon.ico' />
<link href="media/css/stile.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_page.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_table_jui.css" rel="StyleSheet" type="text/css">
<link href="media/css/jquery-ui-1.7.2.custom.css" rel="StyleSheet" type="text/css">
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"oLanguage": {
						"sUrl": "media/dataTables.russian.txt"
					},
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"bSort": false
				} );
			} );
		</script>
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
</table><br><br><br><br>

	<p class="text_title" align="center">Активные пользователи</p>

	<div id="container">

	<?php
		echo("<div class='demo_jui'>");

		echo("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display\" id=\"example\">
				<thead>
					<tr>
						<th>Пользователь</th>
						<th>Время/Скорость</th>
						<th>Статус</th>
						<th>Файл/IP</th>
					</tr>
				</thead><tbody>");

		// Активные пользователи
		$result = shell_exec("sudo /etc/init.d/pure-ftpd status");
		$array = explode("\n", $result);
		foreach ($array as $users) {
		if (($users != "") and (substr($users, 0, 3) != "+--") and (substr($users, 2, 3) != "PID")) {
		list ($tmp, $pid, $user, $speed, $stat, $file) = explode("|", $users);
		
		echo("		<tr>
						<td class='center'>$user</td>
						<td class='center'>$speed</td>
						<td class='center'>$stat</td>
						<td>$file</td>
					</tr>");}}

		echo("	</tbody></div>");

	?>

	</div>

	</table>
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
