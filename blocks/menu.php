<td valign="top" height="30px" class="navy">
<div id="menu">
  <ul><?php
if ($master == "index.php")
{
echo "<li id='current'><a href='index.php'><p class='menu_text'>Мониторинг активности</p></a></li>";
}
else
{
echo "<li><a href='index.php'><p class='menu_text'>Мониторинг активности</p></a></li>";
}
?>
<?php
if ($master == "edit_users.php")
{
echo "<li id='current'><a href='edit_users.php'><p class='menu_text'>Пользователи</p></a></li>";
}
else
{
echo "<li><a href='edit_users.php'><p class='menu_text'>Пользователи</p></a></li>";
}
?>
<?php
if ($master == "del_user.php")
{
echo "<li id='current'><a href='del_user.php'><p class='menu_text'>Удалить пользователя</p></a></li>";
}
else
{
echo "<li><a href='del_user.php'><p class='menu_text'>Удалить пользователя</p></a></li>";
}
?>
<?php
if ($master == "daemon_control.php")
{
echo "<li id='current'><a href='daemon_control.php'><p class='menu_text'>Pure-FTPd</p></a></li>";
}
else
{
echo "<li><a href='daemon_control.php'><p class='menu_text'>Pure-FTPd</p></a></li>";
}
?>
  </ul>
</div>
