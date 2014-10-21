<?php
require_once('./Services/Repository/classes/class.ilRepositorySelectorExplorerGUI.php');
require_once('./Services/Repository/classes/class.ilRepositoryExplorerGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CopyUsers/classes/class.cpusrUserTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CopyUsers/classes/class.cpusrAccess.php');
require_once('./Services/Object/classes/class.ilObjectListGUIFactory.php');

/**
 * Class cpusrCourseSelectorGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           1.0.0
 *
 * @ilCtrl_IsCalledBy cpusrCourseSelectorGUI : ilRouterGUI
 * @ilCtrl_IsCalledBy cpusrCourseSelectorGUI : ilRepositorySelectorExplorerGUI
 * @ilCtrl_IsCalledBy cpusrCourseSelectorGUI : ilRepositoryExplorerGUI
 */
class cpusrCourseSelectorGUI {

	const CMD_STD = 'index';
	const CMD_SELECT_USERS = 'selectUsers';
	const CMD_ADD = 'addUsers';
	const POSTVAR_NODE = 'node';


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
		$this->ilCtrl->saveParameter($this, 'origin_ref_id');
		$this->origin_ref_id = $_GET['origin_ref_id'];
		$this->crs = new ilObjCourse($this->origin_ref_id);
		$this->pl = ilCopyUsersPlugin::getInstance();
//		$this->pl->updateLanguageFiles();
		$this->ilCtrl->setParameterByClass('ilObjCourseGUI', 'ref_id', $this->origin_ref_id);
		if (! cpusrAccess::checkAccessForCourseRefId($this->origin_ref_id)) {
			ilUtil::sendFailure('Sie besitzen zu wenig Rechte um Benutzer dieses Workspaces zu verwalten.', true);
			ilUtil::redirect('/');
		}
	}


	protected function initHeader() {
		global $ilLocator;
		/**
		 * @var $ilLocator ilLocatorGUI
		 */
		$list_gui = ilObjectListGUIFactory::_getListGUIByType('crs');
		$this->tpl->setTitle($this->crs->getTitle());
		$this->tpl->setDescription($this->crs->getDescription());
		if ($this->crs->getOfflineStatus()) {
			$this->tpl->setAlertProperties($list_gui->getAlertProperties());
		}
		$this->tpl->setTitleIcon(ilUtil::getTypeIconPath('crs', $this->crs->getId(), 'big'));
		$this->tabs->setBackTarget($this->pl->txt('main_tab_back'), $this->ilCtrl->getLinkTargetByClass(array(
			'ilRepositoryGUI',
			'ilObjCourseGUI'
		), 'members'));
		$ilLocator->addRepositoryItems($this->origin_ref_id);
		$this->tpl->setLocator($ilLocator->getHTML());
	}


	public function executeCommand() {
		$this->initHeader();
		$next_class = $this->ilCtrl->getNextClass();
		switch ($next_class) {
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
		$cmd = $this->ilCtrl->getCmd(self::CMD_STD);
		switch ($cmd) {
			case self::CMD_STD:
			case self::CMD_SELECT_USERS:
			case self::CMD_ADD:
				// ACCESS CHECK
				$this->{$cmd}();
		}
	}


	protected function index() {
		ilUtil::sendInfo($this->pl->txt('msg_select_course'));
		$ilTreeExplorerGUI = new ilRepositorySelectorExplorerGUI($this, self::CMD_STD);
		$ilTreeExplorerGUI->setSkipRootNode(true);
		$ilTreeExplorerGUI->setTypeWhiteList(array( 'root', 'cat', 'crs' ));
		$ilTreeExplorerGUI->setSelectMode(self::POSTVAR_NODE, false);
		$ilTreeExplorerGUI->handleCommand();

		$ilToolbarGUI = new ilToolbarGUI();
		$ilToolbarGUI->setCloseFormTag(false);
		$ilToolbarGUI->setFormAction($this->ilCtrl->getFormAction($this));
		$ilToolbarGUI->addFormButton($this->pl->txt('crs_select_button_choose'), self::CMD_SELECT_USERS);
		$ilToolbarGUI_end = clone($ilToolbarGUI);
		$ilToolbarGUI_end->setOpenFormTag(false);
		$ilToolbarGUI_end->setCloseFormTag(true);
		$this->tpl->setContent($ilToolbarGUI->getHTML() . $ilTreeExplorerGUI->getHTML() . $ilToolbarGUI_end->getHTML());
	}


	protected function selectUsers() {
		ilUtil::sendInfo($this->pl->txt('msg_select_users'));
		$source_ref_id = $_REQUEST[self::POSTVAR_NODE];
		$type = ilObject2::_lookupType($source_ref_id, true);
		if ($type != 'crs') {
			ilUtil::sendFailure($this->pl->txt('msg_alert_crs_only'), true);
			$this->ilCtrl->redirect($this, self::CMD_STD);
		}
		if (! cpusrAccess::checkAccessForCourseRefId($source_ref_id)) {
			ilUtil::sendFailure($this->pl->txt('msg_alert_no_access'), true);
			$this->ilCtrl->redirect($this, self::CMD_STD);
		}
		$this->ilCtrl->setParameter($this, self::POSTVAR_NODE, $_REQUEST[self::POSTVAR_NODE]);
		$cpusrUserTableGUI = new cpusrUserTableGUI($this, self::CMD_STD, $source_ref_id);
		$this->tpl->setContent($cpusrUserTableGUI->getHTML());
	}


	protected function addUsers() {
		$membersObject = ilCourseParticipants::_getInstanceByObjId(ilObject2::_lookupObjId($this->origin_ref_id));
		if (count($_POST['usr_ids']) == 0) {
			ilUtil::sendFailure($this->pl->txt('msg_alert_no_users'), true);
			$this->ilCtrl->setParameter($this, self::POSTVAR_NODE, $_REQUEST[self::POSTVAR_NODE]);
			$this->ilCtrl->redirect($this, self::CMD_SELECT_USERS);
		}
		foreach ($_POST['usr_ids'] as $usr_id) {
			$membersObject->add($usr_id, self::getRole($usr_id));
		}
		ilUtil::sendSuccess($this->pl->txt('msg_success'), true);
		$this->ilCtrl->redirectByClass(array( 'ilRepositoryGUI', 'ilObjCourseGUI' ), 'members');
	}


	/**
	 * @param $usr_id
	 *
	 * @return int
	 */
	protected static function getRole($usr_id) {
		return $_POST[$usr_id];
	}
}

?>
