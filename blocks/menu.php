<td valign="top" height="30px" class="navy">
	<div id="menu">
		<ul>
			<li<?= ($master == 'index.php' ? ' id="current"' : '') ?>>
				<a href="./index.php"><p class="menu_text"><?= $menu1_act ?></p></a>
			</li>
			<li<?= ($master == 'edit_users.php' ? ' id="current"' : '') ?>>
				<a href="./edit_users.php"><p class="menu_text"><?= $menu2_users ?></p></a>
			</li>
			<li<?= ($master == 'del_user.php' ? ' id="current"' : '') ?>>
				<a href="./del_user.php"><p class='menu_text'><?= $menu3_userdel ?></p></a>
			</li>
			<li<?= ($master == 'daemon_control.php' ? ' id="current"' : '') ?>>
				<a href="./daemon_control.php"><p class="menu_text"><?= $menu4_daemon ?></p></a>
			</li>
			<li<?= ($master == 'edit_settings.php' ? ' id="current"' : '') ?>>
				<a href="./edit_settings.php"><p class="menu_text"><?= $menu5_settings ?></p></a>
			</li>
			<li<?= ($master == 'webui_users.php' ? ' id="current"' : '') ?>>
				<a href="./webui_users.php"><p class="menu_text"><?= $menu6_webuictl ?></p></a>
			</li>
		</ul>
	</div>
</td>
