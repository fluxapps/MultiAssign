<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/User/class.multaUserGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Course/class.multaCourseGUI.php');
require_once('class.ilMultiAssignPlugin.php');

/**
 * Class multaMainGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           1.0.0
 *
 * @ilCtrl_Calls      multaMainGUI : multaUserGUI
 * @ilCtrl_Calls      multaMainGUI : multaCourseGUI
 * @ilCtrl_IsCalledBy multaMainGUI : ilRouterGUI
 */
class multaMainGUI {

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
		//		$this->pl->updateLanguageFiles();
	}


	protected function initHeader() {
		$this->tpl->setTitle($this->pl->txt('header_title'));
		$this->tpl->setDescription($this->pl->txt('header_description'));
		$this->tpl->setTitleIcon(ilUtil::getImagePath('icon_usr_b.png'));
	}


	public function executeCommand() {
		$this->initHeader();
		$next_class = $this->ilCtrl->getNextClass();
		switch ($next_class) {
			case '':
			case 'multausergui':
				$gui = new multaUserGUI();
				$this->ilCtrl->forwardCommand($gui);
				break;
			case 'multacoursegui':
				$gui = new multaCourseGUI();
				$this->ilCtrl->forwardCommand($gui);
				break;
		}
	}
}

?>
