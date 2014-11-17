<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');
require_once('class.multaConfig.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Assignment/class.multaSummaryMail.php');

/**
 * Class multaConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class multaConfigFormGUI extends ilPropertyFormGUI {

	/**
	 * @var ilMultiAssignConfigGUI
	 */
	protected $parent_gui;
	/**
	 * @var  ilCtrl
	 */
	protected $ctrl;


	/**
	 * @param ilMultiAssignConfigGUI $parent_gui
	 */
	public function __construct(ilMultiAssignConfigGUI $parent_gui) {
		global $ilCtrl;
		$this->parent_gui = $parent_gui;
		$this->ctrl = $ilCtrl;
		$this->pl = ilMultiAssignPlugin::getInstance();
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
		$this->initForm();
	}


	/**
	 * @param $field
	 *
	 * @return string
	 */
	public function txt($field) {
		return $this->pl->txt('admin_' . $field);
	}


	protected function initForm() {
		$this->setTitle($this->pl->txt('admin_form_title'));

		$global_roles = self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL);
		$se = new ilMultiSelectInputGUI($this->txt(multaConfig::F_ROLES_ADMIN), multaConfig::F_ROLES_ADMIN);
		$se->setWidth(400);
		$se->setOptions($global_roles);
		$this->addItem($se);

		$cb = new ilCheckboxInputGUI($this->txt(multaConfig::F_SEND_MAILS), multaConfig::F_SEND_MAILS);

		$te = new ilTextareaInputGUI($this->txt(multaConfig::F_EMAIL_TEXT_DE), multaConfig::F_EMAIL_TEXT_DE);
		$te->setRows(15);
		$te->setInfo(multaSummaryMail::getAvailablePlaceholdersAsString());
		$cb->addSubItem($te);

		$te = new ilTextareaInputGUI($this->txt(multaConfig::F_EMAIL_TEXT_EN), multaConfig::F_EMAIL_TEXT_EN);
		$te->setRows(15);
		$te->setInfo(multaSummaryMail::getAvailablePlaceholdersAsString());
		$cb->addSubItem($te);

		$this->addItem($cb);

		$this->addCommandButtons();
	}


	public function fillForm() {
		$array = array();
		foreach ($this->getItems() as $item) {
			$this->getValuesForItem($item, $array);
		}
		$this->setValuesByArray($array);
	}


	/**
	 * @param ilFormPropertyGUI $item
	 * @param                   $array
	 *
	 * @internal param $key
	 */
	private function getValuesForItem($item, &$array) {
		if (self::checkItem($item)) {
			$key = $item->getPostVar();
			$array[$key] = multaConfig::get($key);
			if (self::checkForSubItem($item)) {
				foreach ($item->getSubItems() as $subitem) {
					$this->getValuesForItem($subitem, $array);
				}
			}
		}
	}


	/**
	 * @return bool
	 */
	public function saveObject() {
		if (!$this->checkInput()) {
			return false;
		}
		foreach ($this->getItems() as $item) {
			$this->saveValueForItem($item);
		}

		return true;
	}


	/**
	 * @param  ilFormPropertyGUI $item
	 */
	private function saveValueForItem($item) {
		if (self::checkItem($item)) {
			$key = $item->getPostVar();
			multaConfig::set($key, $this->getInput($key));

			if (self::checkForSubItem($item)) {
				foreach ($item->getSubItems() as $subitem) {
					$this->saveValueForItem($subitem);
				}
			}
		}
	}


	/**
	 * @param $item
	 *
	 * @return bool
	 */
	public static function checkForSubItem($item) {
		return !$item instanceof ilFormSectionHeaderGUI AND !$item instanceof ilMultiSelectInputGUI;
	}


	/**
	 * @param $item
	 *
	 * @return bool
	 */
	public static function checkItem($item) {
		return !$item instanceof ilFormSectionHeaderGUI;
	}


	protected function addCommandButtons() {
		$this->addCommandButton('save', $this->pl->txt('admin_form_button_save'));
		$this->addCommandButton('cancel', $this->pl->txt('admin_form_button_cancel'));
	}


	/**
	 * @param int  $filter
	 * @param bool $with_text
	 *
	 * @return array
	 */
	public static function getRoles($filter, $with_text = true) {
		global $rbacreview;
		$opt = array();
		$role_ids = array();
		foreach ($rbacreview->getRolesByFilter($filter) as $role) {
			$opt[$role['obj_id']] = $role['title'] . ' (' . $role['obj_id'] . ')';
			$role_ids[] = $role['obj_id'];
		}
		if ($with_text) {
			return $opt;
		} else {
			return $role_ids;
		}
	}
}