<?php
require_once('class.multaCourse.php');
require_once('class.multaCourseTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/User/class.multaUser.php');
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Modules/Course/classes/class.ilObjCourse.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Assignment/class.multaAssignment.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Assignment/class.multaSummaryMail.php');

/**
 * Class multaCourseGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           1.0.0
 *
 */
class multaCourseGUI {

	const CMD_INDEX = 'index';
	const CMD_CANCEL = 'cancel';
	const CMD_APPLY_FILTER = 'applyFilter';
	const CMD_RESET_FILTER = 'resetFilter';
	const IDENTIFIER = 'usr_id';
	const CMD_DO_ASSIGNMENTS = 'doAssignments';
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilCtrl
	 */
	protected $ilCtrl;
	/**
	 * @var ilLanguage
	 */
	protected $lng;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var int
	 */
	protected $usr_id;
	/**
	 * @var multaUser
	 */
	protected $multaUser;
	/**
	 * @var ilPropertyFormGUI
	 */
	protected $usr_pres;


	public function __construct() {
		global $ilCtrl, $tpl, $lng, $ilTabs;
		/**
		 * @var ilCtrl     $ilCtrl
		 * @var ilTemplate $tpl
		 * @var ilLanguage $lng
		 * @var ilTabsGUI  $ilTabs
		 */
		$this->ilCtrl = $ilCtrl;
		$this->tpl = $tpl;
		$this->lng = $lng;
		$this->tabs = $ilTabs;
		$this->pl = ilMultiAssignPlugin::getInstance();
		$this->usr_id = ilSession::get(multaUserGUI::SESSION_ID);
	}


	public function executeCommand() {
		$this->initUser();
		$cmd = $this->ilCtrl->getCmd(self::CMD_INDEX);
		switch ($cmd) {
			case self::CMD_INDEX:
			case self::CMD_CANCEL:
			case self::CMD_APPLY_FILTER:
			case self::CMD_RESET_FILTER:
			case self::CMD_DO_ASSIGNMENTS:
				// ACCESS CHECK
				$this->{$cmd}();
		}
	}


	protected function initUserPresentation() {
		$this->usr_pres = new ilPropertyFormGUI();

		$title = new ilFormSectionHeaderGUI();
		$title->setTitle($this->pl->txt('usr_selected_data'));
		$this->usr_pres->addItem($title);

		$login = new ilNonEditableValueGUI($this->pl->txt('usr_login'));
		$login->setValue($this->multaUser->getLogin());
		$this->usr_pres->addItem($login);

		$firstname = new ilNonEditableValueGUI($this->pl->txt('usr_firstname'));
		$firstname->setValue($this->multaUser->getFirstname());
		$this->usr_pres->addItem($firstname);

		$lastname = new ilNonEditableValueGUI($this->pl->txt('usr_lastname'));
		$lastname->setValue($this->multaUser->getLastname());
		$this->usr_pres->addItem($lastname);

		$email = new ilNonEditableValueGUI($this->pl->txt('usr_email'));
		$email->setValue($this->multaUser->getEmail());
		$this->usr_pres->addItem($email);
	}


	protected function index() {
		$this->initUserPresentation();
		$multaCourseTableGUI = new multaCourseTableGUI($this, self::CMD_INDEX);
		$this->tpl->setContent($this->usr_pres->getHTML() . $multaCourseTableGUI->getHTML());
	}


	public function applyFilter() {
		$multaUserTableGUI = new multaCourseTableGUI($this, self::CMD_INDEX);
		$multaUserTableGUI->resetOffset();
		$multaUserTableGUI->writeFilterToSession();
		$this->ilCtrl->redirect($this, self::CMD_INDEX);
	}


	public function resetFilter() {
		$multaUserTableGUI = new multaCourseTableGUI($this, self::CMD_INDEX);
		$multaUserTableGUI->resetFilter();
		$multaUserTableGUI->resetOffset();
		$this->ilCtrl->redirect($this, self::CMD_INDEX);
	}


	protected function doAssignments() {
		$token = multaAssignment::doAssignments($_POST, $this->usr_id);
		if (multaConfig::getValueById(multaConfig::F_SEND_MAILS)) {
			$sum = multaSummaryMail::getInstance($token);
			$sum->sendMail(new ilObjUser($this->usr_id));
		}

		ilUtil::sendSuccess($this->pl->txt('msg_success_user_assigned'), true);
		$this->cancel();
	}


	protected function initUser() {
		if ($this->usr_id) {
			$this->multaUser = multaUser::find($this->usr_id);
		}
		if (!$this->multaUser instanceof multaUser) {
			ilUtil::sendFailure($this->pl->txt('msg_failure_no_user_selected'), true);
			$this->cancel();
		}
	}


	protected function cancel() {
		ilSession::set(multaUserGUI::SESSION_ID, NULL);
		$this->ilCtrl->redirectByClass(multaUserGUI::class);
	}
}
