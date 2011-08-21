<?php
// Menu
$menu1_act = "Мониторинг активности";
$menu2_users = "Пользователи";
$menu3_userdel = "Удалить пользователя";
$menu4_daemon = "Управление Pure-FTPd";
$menu5_webuictl = "Пользователи Pure-FTPd WebUI";
// User activity
$ua_title = "Мониторинг активности";
$ua_t_title = "Активные пользователи";
$ua_t_th1 = "Пользователь";
$ua_t_th2 = "Время/Скорость";
$ua_t_th3 = "Статус";
$ua_t_th4 = "Файл/IP";
// User management
$um_title = "Управление пользователями";
$um_userform_login = "Логин";
$um_userform_status = "Статус пользователя</br>(0 - не активен, 1 - активен)";
$um_userform_pwd = "Пароль";
$um_userform_folder = "Папка пользователя</br>(оставьте поле пустым, чтобы использовать значение по умолчанию)";
$um_userform_ullimit = "Ограничение скорости загрузки на сервер KB/s, 0 - без ограничений</br>(оставьте поле пустым, чтобы использовать значение по умолчанию)";
$um_userform_dllimit = "Ограничение скорости скачивания с севера KB/s, 0 - без ограничений</br>(оставьте поле пустым, чтобы использовать значение по умолчанию)";
$um_userform_permip = "Разрешённый IP-адрес, * - любой IP-адрес</br>(оставьте поле пустым, чтобы использовать значение по умолчанию)";
$um_add_addbutton = "Добавить";
$um_add_backbutton = "Вренуться к списку пользователей";
$um_add_presultok = "Пользователь $User успешно добавлен";
$um_add_presulterror = "ОШИБКА: $result";
$um_add_checkfields = "Пользователь не добавлен, заполнены не все поля";
$um_add_checkfieldsback = "Вренуться к списку пользователей";
$um_edit_folderok = "Папка пользователя $array[User] успешно изменена";
$um_edit_foldererror = "ОШИБКА: $result";
$um_edit_loginok = "Логин $array[User] успешно изменено на $User";
$um_edit_loginerror = "ОШИБКА: $result";
$um_edit_statusok = "Статус пользователя $array[User] успешно изменён";
$um_edit_statuserror = "ОШИБКА: $result";
$um_edit_passwd = "Пароль пользователя $array[User] успешно изменён";
$um_edit_passwderror = "ОШИБКА: $result";
$um_edit_ullimitok = "Ограничение скорости загрузки на сервер для пользователя $array[User] успешно изменёно";
$um_edit_ullimiterror = "ОШИБКА: $result";
$um_edit_dllimitok = "Ограничение скорости скачивания с сервера для пользователя $array[User] успешно изменёно";
$um_edit_dllimiterror = "ОШИБКА: $result";
$um_edit_permipok = "Разрешённый IP адрес для пользователя $array[User] успешно изменён";
$um_edit_permiperror = "ОШИБКА: $result";
$um_edit_nochanges = "Вы не вносили изменений";
$um_edit_nochangesback = "Вренуться к списку пользователей";
$um_t_title = "Выберите пользователя для редактирования";
$um_t_th1 = "Логин";
$um_t_th2 = "Статус";
$um_t_th3 = "Папка";
$um_t_th4 = "upload limit (kb/s)";
$um_t_th5 = "download limit (kb/s)";
$um_t_th6 = "Разрешённый IP";
$um_adduserbutton = "Добавить пользователя";
$um_edit_savebutton = "Сохранить изменения";
$um_edit_backbutton = "Вренуться к списку пользователей";
// Drop user
$du_title = "Удаление пользователей";
$du_success = "Пользователь удален";
$du_error = "ОШИБКА: $result";
$du_notchecked = "Не выбран пользователь";
// Del user
$del_title = "Удаление пользователей";
$del_selecttitle = "Выберите пользователя для удаления";
$del_button = "Удалить пользователя";
// Daemon control
$dc_title = "Управление Pure-FTPd";
$dc_confeditbackbutton = "Назад";
$dc_confeditsavebutton = "Сохранить";
$dc_confeditsuccess = "Изменения сохранены, для принятия изменений перезапустите демон Pure-FTPd";
$dc_confeditnorights = "Конфиг небыл изменён, нехватает прав для записи в файл";
$dc_daemoncontrol = "Управление демоном";
$dc_wrongcommand = "Передана неверная команда демону Pure-FTPd";
$dc_wrongcommandback = "Назад";
$dc_dctitle = "Управление демоном Pure-FTPd";
$dc_dcperformbutton = "Выполнить";
$dc_dceditconfig = "Править конфиг";
// WebUI users
$wu_title = "Пользователи Pure-FTPd WebUI";
$wu_select = "Выберите пользователя для редактирования";
$wu_editbutton = "Редактировать";
$wu_adduserbutton = "Добавить пользователя";
$wu_add_resultok = "Пользователь успешно добавлен";
$wu_add_resulterror = "ОШИБКА: $result";
$wu_add_checkfields = "Пользователь не добавлен, заполнены не все поля";
$wu_add_checkfieldsback = "Вренуться к списку пользователей";
// Edit WebUI users
$ewu_form_login = "Логин";
$ewu_form_pwd = "Пароль";
$ewu_form_lang = "Язык";
$ewu_save = "Сохранить изменения";
$ewu_edit_loginok = "Логин $array[user] успешно изменено на $user";
$ewu_edit_loginerror = "ОШИБКА: $result";
$ewu_edit_passwdok = "Пароль пользователя $array[user] успешно изменён";
$ewu_edit_passwderror = "ОШИБКА: $result";
$ewu_edit_languageok = "Язык интерфейса для пользователя $array[user] удачно изменён";
$ewu_edit_languageerror = "ОШИБКА: $result";
$ewu_edit_nochanges = "Вы не вносили изменений";
$ewu_edit_nochangesback = "Вренуться к списку пользователей";
$ewu_deluserbutton = "Удалить пользователя";
$ewu_del_resultok = "Пользователь удален";
$ewu_del_resulterror = "ОШИБКА: $result";
$ewu_del_notchecked = "Не выбран пользователь";
?>