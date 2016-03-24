<?php

/**
 * @author Mikael Karlsson <i8myshoes@gmail.com>
 * @copyright 2016 CSC - IT Center for Science Ltd.
 * @license AGPLv3
 */

const APP_NAME = 'user_shib';

\OCP\App::registerAdmin(APP_NAME, 'settings');

$backend = new \OCA\user_shib\ShibbolethApacheBackend();
\OC_User::useBackend($backend);

\OCP\Util::connectHook('OC_User', 'pre_login', \OCA\user_shib\Hooks::class, 'pre_login');
\OCP\Util::connectHook('OC_User', 'post_login', \OCA\user_shib\Hooks::class, 'post_login');

\OC_App::registerLogIn([
	'href' => $backend->handler . $backend->login,
	'name' => 'Shibboleth'
]);
