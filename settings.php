<?php

/**
 * @author Mikael Karlsson <i8myshoes@gmail.com>
 * @copyright 2016 CSC - IT Center for Science Ltd.
 * @license AGPLv3
 */

\OCP\User::checkAdminUser();

$params = [
	"shib_handler",
	"shib_login",
	"shib_logout",
	"shib_group",
	"shib_quota",

	"shib_idp",

	"shib_mail_attr",
	"shib_pid_attr",
	"shib_group_attr",
	"shib_fn_attr",
];
$config = \OC::$server->getConfig();

if ($_POST) {
	\OCP\Util::callCheck();

	foreach ($params as $param) {
		if (isset($_POST[$param])) {
			$config->setAppValue(APP_NAME, $param, $_POST[$param]);
		}
	}
}

$backend = new \OCA\user_shib\ShibbolethApacheBackend();
$template = new \OCP\Template(APP_NAME, "settings");

$template->assign("shib_handler", $backend->handler);
$template->assign("shib_login", $backend->login);
$template->assign("shib_logout", $backend->logout);
$template->assign("shib_group", $backend->basegroup);
$template->assign("shib_quota", $backend->quota);

$template->assign("shib_idp", $backend->idp);

$template->assign("shib_mail_attr", $backend->mail);
$template->assign("shib_pid_attr", $backend->pid);
$template->assign("shib_group_attr", $backend->group);
$template->assign("shib_fn_attr", $backend->fn);

return $template->fetchPage();
