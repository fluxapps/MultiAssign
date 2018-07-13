<?php
require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Config/class.multaConfig.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Config/class.multaConfigFormGUI.php');

/**
 * Class ilMultiAssignConfigGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 *
 */
class ilMultiAssignConfigGUI extends ilPluginConfigGUI {

	const CMD_CONFIGURE = 'configure';
	const CMD_DEFAULT = 'index';
	const CMD_SAVE = 'save';
	const CMD_CANCEL = "cancel";
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;


	public function __construct() {
		global $tpl, $ilCtrl;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
	}


	public function performCommand($cmd) {
		if ($cmd == self::CMD_CONFIGURE) {
			$cmd = self::CMD_DEFAULT;
		}
		switch ($cmd) {
			case self::CMD_DEFAULT:
			case self::CMD_SAVE:
			case self::CMD_CANCEL:
				$this->{$cmd}();
				break;
		}
	}


	public function index() {
		$multaConfigFormGUI = new multaConfigFormGUI($this);
		$multaConfigFormGUI->fillForm();
		$this->tpl->setContent($multaConfigFormGUI->getHTML());
	}


	protected function save() {
		$form = new multaConfigFormGUI($this);
		$form->setValuesByPost();
		if ($form->saveObject()) {
			ilUtil::sendSuccess('Saved', true); // TODO: Translate
			$this->ctrl->redirect($this, self::CMD_DEFAULT);
		}
		$this->tpl->setContent($form->getHTML());
	}


	protected function cancel() {
		$this->index();
	}
}
