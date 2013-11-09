<td valign="top" height="30px" class="navy">
<div id="menu">
	<ul><?php
		if ($master == "index.php")
		{
			echo "<li id='current'><a href='index.php'><p class='menu_text'>$menu1_act</p></a></li>";
		}
		else
		{
			echo "<li><a href='index.php'><p class='menu_text'>$menu1_act</p></a></li>";
		}
		if ($master == "edit_users.php")
		{
			echo "<li id='current'><a href='edit_users.php'><p class='menu_text'>$menu2_users</p></a></li>";
		}
		else
		{
			echo "<li><a href='edit_users.php'><p class='menu_text'>$menu2_users</p></a></li>";
		}
		if ($master == "del_user.php")
		{
			echo "<li id='current'><a href='del_user.php'><p class='menu_text'>$menu3_userdel</p></a></li>";
		}
		else
		{
			echo "<li><a href='del_user.php'><p class='menu_text'>$menu3_userdel</p></a></li>";
		}
		if ($master == "daemon_control.php")
		{
			echo "<li id='current'><a href='daemon_control.php'><p class='menu_text'>$menu4_daemon</p></a></li>";
		}
		else
		{
			echo "<li><a href='daemon_control.php'><p class='menu_text'>$menu4_daemon</p></a></li>";
		}
		if ($master == "edit_settings.php")
		{
			echo "<li id='current'><a href='edit_settings.php'><p class='menu_text'>$menu5_settings</p></a></li>";
		}
		else
		{
			echo "<li><a href='edit_settings.php'><p class='menu_text'>$menu5_settings</p></a></li>";
		}
		if ($master == "webui_users.php")
		{
			echo "<li id='current'><a href='webui_users.php'><p class='menu_text'>$menu6_webuictl</p></a></li>";
		}
		else
		{
			echo "<li><a href='webui_users.php'><p class='menu_text'>$menu6_webuictl</p></a></li>";
		}
		?>
	</ul>
</div>
