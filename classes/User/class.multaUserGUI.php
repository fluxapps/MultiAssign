<?php
require_once('class.multaUser.php');
require_once('class.multaUserTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/class.multaAccess.php');

/**
 * Class multaUserGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           1.0.0
 *
 */
class multaUserGUI {

	const CMD_INDEX = 'index';
	const CMD_APPLY_FILTER = 'applyFilter';
	const CMD_RESET_FILTER = 'resetFilter';
	const CMD_SELECT_USER = 'selectUser';
	const IDENTIFIER = 'usr_id';
	const SESSION_ID = 'multi_assign_user_id';


	public function __construct() {
		global $ilCtrl, $tpl, $lng, $ilTabs;
		/**
		 * @var $ilCtrl    ilCtrl
		 * @var $tpl       ilTemplate
		 * @var $lng       ilLanguage
		 * @var $ilTabs    ilTabsGUI
		 */
		$this->ilCtrl = $ilCtrl;
		$this->tpl = $tpl;
		$this->lng = $lng;
		$this->tabs = $ilTabs;
		$this->pl = ilMultiAssignPlugin::getInstance();
		ilSession::set(self::SESSION_ID, NULL);
	}


	public function executeCommand() {
		if (!multaAccess::hasAccess()) {
			return false;
		}
		$cmd = $this->ilCtrl->getCmd(self::CMD_INDEX);
		switch ($cmd) {
			case self::CMD_INDEX:
			case self::CMD_APPLY_FILTER:
			case self::CMD_RESET_FILTER:
			case self::CMD_SELECT_USER:
				// ACCESS CHECK
				$this->{$cmd}();
		}
	}


	protected function index() {
		$multaUserTableGUI = new multaUserTableGUI($this, 'index');
		$this->tpl->setContent($multaUserTableGUI->getHTML());
	}


	protected function applyFilter() {
		$multaUserTableGUI = new multaUserTableGUI($this, 'index');
		$multaUserTableGUI->resetOffset();
		$multaUserTableGUI->writeFilterToSession();
		$this->ilCtrl->redirect($this, self::CMD_INDEX);
	}


	protected function resetFilter() {
		$multaUserTableGUI = new multaUserTableGUI($this, 'index');
		$multaUserTableGUI->resetFilter();
		$multaUserTableGUI->resetOffset();
		$this->ilCtrl->redirect($this, self::CMD_INDEX);
	}


	protected function selectUser() {
		global $ilUser;
		$usr_id = $_POST['id'];
		if ($usr_id == $ilUser->getId()) {
			ilUtil::sendFailure($this->pl->txt('msg_failure_own_usr_id'), true);
			$this->ilCtrl->redirect($this, self::CMD_INDEX);
		}
		ilSession::set(self::SESSION_ID, $usr_id);
		$this->ilCtrl->redirectByClass(array( 'multaCourseGUI' ));
	}
}

?>
