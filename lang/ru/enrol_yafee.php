<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Strings for component 'enrol_yafee', language 'ru', version '4.3'.
 *
 * @package     enrol_yafee
 * @category    string
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['canntenrolearly'] = 'Вы пока не можете записаться; запись начнется с {$a}.';
$string['canntenrollate'] = 'Вы больше не можете записаться, так как запись закончилась {$a}.';
$string['canntenrol'] = 'Зачисление отключено или неактивно';
$string['cohortnonmemberinfo'] = 'Только члены глобальной группы «{$a}» могут самостоятельно записываться.';
$string['cohortonly_help'] = 'Можно ограничить самостоятельную запись только для членов указанной глобальной группы. Обратите внимание, что изменение этого параметра не влияет на уже записанных участников.';
$string['cohortonly'] = 'Только члены глобальной группы';
$string['confirmbulkdeleteenrolment'] = 'Вы уверены, что хотите отчислить этих пользователей?';
$string['customwelcomemessage_help'] = 'Пользовательское приветственное сообщение может быть добавлено в виде простого текста или авто-формата Moodle, включая HTML-теги и мультиязычные теги.

В сообщение могут быть включены следующие подстановки:

* Название курса - {$a->coursename}
* Ссылка на страницу профиля пользователя - {$a->profileurl}
* Эл. почта пользователя {$a->email}
* Полное имя пользователя {$a->fullname}';
$string['customwelcomemessage'] = 'Текст приветственного сообщения';
$string['defaultrole_desc'] = 'Выберите роль, которая будет назначена самостоятельно записанным пользователям';
$string['defaultrole'] = 'Назначение роли по умолчанию';
$string['deleteselectedusers'] = 'Отчислить выбранных пользователей';
$string['donate'] = '<div>Версия плагина: {$a->release} ({$a->versiondisk})<br>
Новые версии плагина вы можете найти на <a href=https://github.com/Snickser/moodle-enrol_yafee>GitHub.com</a>
<img src="https://img.shields.io/github/v/release/Snickser/moodle-enrol_yafee.svg"><br>
Пожалуйста, отправьте мне немножко <a href="https://yoomoney.ru/fundraise/143H2JO3LLE.240720">доната</a>😊</div>
BTC 1FHtZ82jLoBZ8ZsU7J2E9Cxy2xgUU7GJtD<br>
<iframe src="https://yoomoney.ru/quickpay/fundraise/button?billNumber=143H2JO3LLE.240720"
width="330" height="50" frameborder="0" allowtransparency="true" scrolling="no"></iframe><br>';
$string['editselectedusers'] = 'Изменить зачисления выбранных пользователей';
$string['enrolenddate_help'] = 'Если параметр включен, то пользователи могут самостоятельно записаться только до этой даты.';
$string['enrolenddaterror'] = 'Дата окончания записи не может быть ранее даты ее начала';
$string['enrolenddate'] = 'Конечная дата';
$string['enrolme'] = 'Записаться на курс';
$string['enrolperiod_desc'] = 'Продолжительность обучения по умолчанию. Если установлен ноль, то, по умолчанию, продолжительность обучения не будет ограничена.';
$string['enrolperiod_help'] = 'Продолжительность обучения, начиная с момента самостоятельной записи пользователя на курс. Если не включать этот параметр, то продолжительность обучения не будет ограничена.';
$string['enrolperiod'] = 'Продолжительность обучения';
$string['enrolstartdate_help'] = 'При включенном параметре пользователи могут самостоятельно записаться после этой даты.';
$string['enrolstartdate'] = 'Начальная дата';
$string['expiredaction_help'] = 'Выберите выполняемое действие при истечении срока записи пользователя в курсе. Обратите внимание, что из курса удаляются некоторые настройки  и данные пользователя при исключении его из курса.';
$string['expiredaction'] = 'Действие при истечении срока зачисления';
$string['expirymessageenrolledbody'] = 'Уважаемый(ая) {$a->user}, уведомляем, что обучение в курсе «{$a->course}» истекает {$a->timeend}.

При необходимости свяжитесь с {$a->enroller}.';
$string['expirymessageenrolledsubject'] = 'Уведомление об истечении срока обучения';
$string['expirymessageenrollerbody'] = 'Самостоятельная запись в курсе «{$a->course}» истекает в течение следующих {$a->threshold} для перечисленных пользователей:

{$a->users}.

Чтобы продлить их обучение, перейдите на {$a->extendurl}';
$string['expirymessageenrollersubject'] = 'Уведомление об истечении срока обучения';
$string['expirynotifyall'] = 'Преподавателя и учащегося';
$string['expirynotifyenroller'] = 'Только преподавателя';
$string['extremovedsuspendnoroles'] = 'Приостановить участие в курсе и удалить роли';
$string['freetrialbutton'] = 'Войти';
$string['freetrial'] = 'Бесплатная пробная запись';
$string['freetrial_desc'] = 'Доступно ознакомительное время';
$string['freetrial_help'] = 'Позволяет пользователю открыть курс один раз на определенный период времени без оплаты.';
$string['groupkey_desc'] = 'Использовать кодовые слова для групп по умолчанию';
$string['groupkey_help'] = 'В дополнение к ограничению доступа к курсу лишь тех, кто знает кодовое слово курса, использование кодового слова группы  позволяет автоматически добавить пользователей в группу при их записи на курс.

Примечание: кодовое слово для курса должно быть указано в настройках самостоятельной записи, а кодовое слово группы - в настройках группы. Эти кодовые слова должны быть разными.';
$string['groupkey'] = 'Использовать кодовые слова для групп';
$string['keyholder'] = 'Вы должны были получить кодовое слово для записи на курс от:';
$string['longtimenosee_help'] = 'Если пользователь долго не заходил на курс, то он будет автоматически исключен через заданный период времени.';
$string['longtimenosee'] = 'Исключать неактивных пользователей через';
$string['maxenrolled_help'] = 'Максимальное количество пользователей, которые могут записаться самостоятельно. 0 означает без ограничений.';
$string['maxenrolledreached'] = 'Было достигнуто максимальное число самостоятельно записавшихся пользователей';
$string['maxenrolled'] = 'Макс. количество пользователей';
$string['messageprovider:expiry_notification'] = 'Уведомления об истечении срока обучения при самостоятельной записи';
$string['newenrols_desc'] = 'По умолчанию разрешить пользователям самостоятельно записываться на новые курсы.';
$string['newenrols_help'] = 'Этот параметр определяет, может ли пользователь записаться на этот курс.';
$string['newenrols'] = 'Разрешить новые самозаписи';
$string['nopassword'] = 'Кодовое слово не требуется.';
$string['password_help'] = 'Кодовое слово позволяет предоставить доступ к курсу только тем, кто знает пароль.

Если поле оставить пустым, то любой пользователь сможет записаться на курс.

Если задано кодовое слово, то любому пользователю при попытке записаться на курс потребуется его ввести. Это нужно будет сделать лишь ОДНАЖДЫ, при записи на курс.';
$string['passwordinvalidhint'] = 'Введено неверное кодовое слово, попробуйте еще раз<br />(Подсказка - оно начинается с «{$a}»)';
$string['passwordinvalid'] = 'Неверное кодовое слово, попробуйте еще раз';
$string['passwordmatchesgroupkey'] = 'Ключ регистрации соответствует ключу регистрации имеющейся группы.';
$string['password'] = 'Кодовое слово';
$string['pluginname_desc'] = 'Метод платной регистрации позволяет вам устанавливать курсы, требующие оплаты. Если плата за какой-либо курс установлена на ноль, то студентам не нужно платить за регистрацию. Существует плата за весь сайт, которую вы устанавливаете здесь как значение по умолчанию для всего сайта, а затем настройка курса, которую вы можете установить для каждого курса индивидуально. Плата за курс перекрывает плату за сайт.';
$string['pluginname'] = 'Новое Зачисление за оплату';
$string['privacy:metadata'] = 'Плагин «Самостоятельная запись» не хранит никаких персональных данных.';
$string['requirepassword_desc'] = 'Этот параметр отвечает за обязательное использование кодового слова в новых курсах, а также запрещает его отключение в уже созданных.';
$string['requirepassword'] = 'Обязательно использовать кодовое слово';
$string['role'] = 'Роль, назначаемая по умолчанию';
$string['self:config'] = 'Настраивать экземпляры способа записи на курс «Самостоятельная запись»';
$string['self:enrolself'] = 'Самостоятельная запись на курс';
$string['self:holdkey'] = 'Отображаться в качестве владельца кодового слова для самостоятельной записи';
$string['self:manage'] = 'Управлять записанными на курс пользователями';
$string['self:unenrolself'] = 'Исключать себя из курса';
$string['self:unenrol'] = 'Исключать пользователей из курса';
$string['sendcoursewelcomemessage_help'] = 'Пользователям, которые записываются на курс самостоятельно, может быть отправлено приветственное сообщение по электронной почте. В случае, если отправка осуществляется от имени контакта курса (по умолчанию это пользователь с ролью «Учитель»), и в курсе есть несколько пользователей с соответствующей ролью, электронное письмо будет отправлено от имени первого такого пользователя.';
$string['sendcoursewelcomemessage'] = 'Отправлять приветственное сообщение';
$string['sendexpirynotificationstask'] = 'Задача отправки уведомлений об истечении срока действия самостоятельного зачисления';
$string['showduration'] = 'Показывать длительность обучения на странице';
$string['showhint_desc'] = 'Показывать первую букву кодового слова.';
$string['showhint'] = 'Показывать подсказку';
$string['status_desc'] = 'Использовать способ самостоятельной регистрации в новых курсах (отключен по умолчанию).';
$string['status_help'] = 'Если установлено значение «Нет», все существующие участники, записавшиеся на курс, больше не будут иметь доступа.';
$string['status'] = 'Поддерживать активной текущую самостоятельную регистрацию';
$string['syncenrolmentstask'] = 'Синхронизация самостоятельного зачисления';
$string['unenrolselfconfirm'] = 'Вы действительно хотите исключить себя из курса «{$a}»?';
$string['unenrolusers'] = 'Исключить пользователей';
$string['unenroluser'] = 'Вы действительно хотите исключить пользователя «{$a->user}» из курса «{$a}»?';
$string['unenrol'] = 'Исключить пользователя';
$string['usepasswordpolicy_desc'] = 'Использовать политику паролей для кодовых слов';
$string['usepasswordpolicy'] = 'Использовать политику паролей';
$string['validationerror'] = 'Регистрация не может быть включена без указания платежного счета';
$string['welcometocoursetext'] = 'Добро пожаловать в курс «{$a->coursename}»!

Если Вы еще не сделали этого, то отредактируйте свой профиль так, чтобы мы узнали больше о Вас:

  {$a->profileurl}';

$string['welcometocourse'] = 'Добро пожаловать в {$a}';
