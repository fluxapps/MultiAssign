<?php

require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Config/class.multaConfig.php');
require_once('./Modules/Course/classes/class.ilObjCourse.php');
require_once('./Services/Mail/classes/class.ilMail.php');

/**
 * Class multaSummaryMail
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class multaSummaryMail {

	const P_USER_FIRSTNAME = 'USER_FIRSTNAME';
	const P_USER_LASTNAME = 'USER_LASTNAME';
	const P_USER_EMAIL = 'USER_EMAIL';
	const P_LIST_ALL = 'LIST_ALL';
	const P_LIST_ADMIN = 'LIST_ADMIN';
	const P_LIST_TUTOR = 'LIST_TUTOR';
	const P_LIST_MEMBER = 'LIST_MEMBER';
	/**
	 * @var array
	 */
	protected static $placeholders = array(
		self::P_LIST_ALL,
		self::P_LIST_ADMIN,
		self::P_LIST_TUTOR,
		self::P_LIST_MEMBER,
		self::P_USER_FIRSTNAME,
		self::P_USER_LASTNAME,
		self::P_USER_EMAIL,
	);
	/**
	 * @var string
	 */
	protected $token = '';
	/**
	 * @var string
	 */
	protected $lng_key = 'de';
	/**
	 * @var string
	 */
	protected $mail_text = '';
	/**
	 * @var ilObjUser
	 */
	protected $usr_obj;
	/**
	 * @var string
	 */
	protected $subject = 'Multi-Assign';
	/**
	 * @var multaSummaryMail[]
	 */
	protected static $instances = array();


	/**
	 * @param $token
	 *
	 * @return multaSummaryMail
	 */
	public static function getInstance($token) {
		if (!isset(self::$instances[$token])) {
			self::$instances[$token] = new self($token);
		}

		return self::$instances[$token];
	}


	/**
	 * @param $token
	 */
	protected function __construct($token) {
		$this->setToken($token);
	}


	/**
	 * @return string
	 */
	public static function getAvailablePlaceholdersAsString() {
		$return = ilMultiAssignPlugin::getInstance()->txt('admin_placeholders');
		$return .= ' [';
		$return .= implode('] [', self::$placeholders);
		$return .= '] ';

		return $return;
	}


	protected function renderEmailText() {
		$text = multaConfig::get(multaConfig::F_EMAIL_TEXT_PREFIX . $this->getLngKey());

		foreach (self::$placeholders as $p) {
			$text = preg_replace("/\\[" . $p . "\\]/uim", $this->renderPlaceholder($p), $text);
		}

		$this->setMailText($text);
	}


	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function renderPlaceholder($key) {
		switch ($key) {
			case self::P_LIST_ALL:
				$string_list = $this->getStringListForRole(NULL);

				return implode("\n", $string_list);
				break;
			case self::P_LIST_ADMIN:
				$string_list = $this->getStringListForRole(multaAssignment::ROLE_ADMIN);

				return implode("\n", $string_list);
				break;
			case self::P_LIST_TUTOR:
				$string_list = $this->getStringListForRole(multaAssignment::ROLE_TUTOR);

				return implode("\n", $string_list);
				break;
			case self::P_LIST_MEMBER:
				$string_list = $this->getStringListForRole(multaAssignment::ROLE_MEMBER);

				return implode("\n", $string_list);
				break;
			case self::P_USER_FIRSTNAME:
				return $this->getUsrObj()->getFirstname();
				break;
			case self::P_USER_LASTNAME:
				return $this->getUsrObj()->getLastname();
				break;
			case self::P_USER_EMAIL:
				return $this->getUsrObj()->getEmail();
				break;
		}

		return '';
	}


	/**
	 * @param ilObjUser $ilObjUser
	 */
	public function sendMail(ilObjUser $ilObjUser) {
		global $ilUser;
		$this->setUsrObj($ilObjUser);
		if (in_array($ilObjUser->getLanguage(), array( 'de', 'en' ))) {
			$this->setLngKey($ilObjUser->getLanguage());
		}
		$this->renderEmailText();

		$ilMail = new ilMail($ilUser->getId());
		$ilMail->sendMail($ilObjUser->getLogin(), '', '', 'Multi-Assign', $this->getMailText(), NULL, array( 'normal' ));
	}


	/**
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}


	/**
	 * @param string $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}


	/**
	 * @return string
	 */
	public function getLngKey() {
		return $this->lng_key;
	}


	/**
	 * @param string $lng_key
	 */
	public function setLngKey($lng_key) {
		$this->lng_key = $lng_key;
	}


	/**
	 * @return string
	 */
	public function getMailText() {
		return $this->mail_text;
	}


	/**
	 * @param string $mail_text
	 */
	public function setMailText($mail_text) {
		$this->mail_text = $mail_text;
	}


	/**
	 * @return array
	 */
	protected function getStringListForRole($role = NULL) {
		/**
		 * @var $list multaAssignment[]
		 */
		$string_list = array();
		$multaAssignmentList = multaAssignment::where(array( 'request_token' => $this->getToken() ));
		if ($role) {
			$multaAssignmentList->where(array( 'role' => $role ));
		}
		$list = $multaAssignmentList->get();
		foreach ($list as $ma) {
			$ilObjCourse = new ilObjCourse($ma->getCrsId());
			$string_list[] = $ilObjCourse->getTitle();
		}

		return $string_list;
	}


	/**
	 * @return ilObjUser
	 */
	public function getUsrObj() {
		return $this->usr_obj;
	}


	/**
	 * @param ilObjUser $usr_obj
	 */
	public function setUsrObj($usr_obj) {
		$this->usr_obj = $usr_obj;
	}


	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}
}

?>
