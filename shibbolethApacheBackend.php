<?php

/**
 * @author Mikael Karlsson <i8myshoes@gmail.com>
 * @copyright 2016 CSC - IT Center for Science Ltd.
 * @license AGPLv3
 */

namespace OCA\user_shib;

use OC_User_Backend;
use OCP\Authentication\IApacheBackend;
use OCP\IUserBackend;
use OCP\Util;

/**
 * @property string handler
 * @property string login
 * @property string logout
 * @property string basegroup
 * @property string quota
 * @property string idp
 * @property string mail
 * @property string pid
 * @property string group
 * @property string fn
 */
class ShibbolethApacheBackend extends OC_User_Backend implements IApacheBackend, IUserBackend {
	public function __construct() {
		$config = \OC::$server->getConfig();

		$this->handler = $config->getAppValue(APP_NAME, "shib_handler", "/Shibboleth.sso");
		$this->login = $config->getAppValue(APP_NAME, "shib_login", "/Login");
		$this->logout = $config->getAppValue(APP_NAME, "shib_logout", "/Logout");
		$this->basegroup = $config->getAppValue(APP_NAME, "shib_group", "SAML");
		$this->quota = $config->getAppValue(APP_NAME, "shib_quota", "");

		$this->idp = $config->getAppValue(APP_NAME, "shib_idp", "Shib-Identity-Provider");

		$this->mail = $config->getAppValue(APP_NAME, "shib_mail_attr", "mail");
		$this->pid = $config->getAppValue(APP_NAME, "shib_pid_attr", "eppn");
		$this->group = $config->getAppValue(APP_NAME, "shib_group_attr", "schacHomeOrganization");
		$this->fn = $config->getAppValue(APP_NAME, "shib_fn_attr", "cn");
	}

	/**
	 * In case the user has been authenticated by Apache true is returned.
	 *
	 * @return boolean whether Apache reports a user as currently logged in.
	 * @since 6.0.0
	 */
	public function isSessionActive() {
		Util::writeLog(APP_NAME, "isSessionActive() called", Util::DEBUG);
		return isset($_SERVER[$this->idp]);
	}

	/**
	 * Creates an attribute which is added to the logout hyperlink. It can
	 * supply any attribute(s) which are valid for <a>.
	 *
	 * @return string with one or more HTML attributes.
	 * @since 6.0.0
	 */
	public function getLogoutAttribute() {
		Util::writeLog(APP_NAME, "getLogoutAttribute() called", Util::DEBUG);
		Util::callRegister(); // Burn one token, otherwise logout fails
		return 'href="' . $this->handler . $this->logout . "?return=" . urlencode(\OC::$server->getConfig()->getSystemValue("overwrite.cli.url") . link_to("", "index.php") . "?logout=true&requesttoken=" . Util::callRegister()) . '"';
	}

	/**
	 * Return the id of the current user
	 * @return string
	 * @since 6.0.0
	 */
	public function getCurrentUserId() {
		Util::writeLog(APP_NAME, "getCurrentUserId() called", Util::DEBUG);
		return hash("md5", $_SERVER[$this->pid]);
	}

	/**
	 * Backend name to be shown in user management
	 * @return string the name of the backend to be shown
	 * @since 8.0.0
	 */
	public function getBackendName() {
		Util::writeLog(APP_NAME, "getBackendName() called", Util::DEBUG);
		return "Shibboleth";
	}
}

class Hooks {
	static public function pre_login($array) {
		Util::writeLog(APP_NAME, "pre_login() called", Util::DEBUG);

		$backend = (new ShibbolethApacheBackend());
		if (!$backend->isSessionActive())
			return;
		Util::writeLog(APP_NAME, "Shibboleth user!", Util::DEBUG);

		// User
		$uid = $array["uid"];
		$um = \OC::$server->getUserManager();
		if (!($um->userExists($uid))) {
			$um->createUser($uid, \OC::$server->getSecureRandom()->getMediumStrengthGenerator()->generate(32));
			\OC::$server->getUserFolder($uid); // Trigger files_skeleton, otherwise login fails
		}
		$user = $um->get($uid);

		// Group(s)
		$gm = \OC::$server->getGroupManager();
		$gids = [$backend->basegroup, $_SERVER[$backend->group]];
		foreach ($gids as $gid) {
			if (empty($gid)) {
				continue;
			}
			$gid = explode(".", $gid, 2)[0];
			$gid = strlen($gid) <= 4 ? strtoupper($gid) : ucfirst($gid);
			if (!($gm->groupExists($gid))) {
				$gm->createGroup($gid);
			}
			$group = $gm->get($gid);
			if (!$group->inGroup($user)) {
				$group->addUser($user);
			}
		}
	}

	static public function post_login($array) {
		Util::writeLog(APP_NAME, "post_login() called", Util::DEBUG);

		$backend = new ShibbolethApacheBackend();
		if (!$backend->isSessionActive()) {
			return;
		}
		Util::writeLog(APP_NAME, "Shibboleth user!", Util::DEBUG);

		$uid = $array["uid"];
		$um = \OC::$server->getUserManager();
		$user = $um->get($uid);
		$user->setDisplayName($_SERVER[$backend->fn]);
		$user->updateLastLoginTimestamp();
		$config = \OC::$server->getConfig();
		$config->setUserValue($uid, "settings", "email", $_SERVER[$backend->mail]);
		if (empty($backend->quota)) {
			$config->deleteUserValue($uid, "files", "quota");
		} else {
			$config->setUserValue($uid, "files", "quota", $backend->quota);
		}
	}
}
