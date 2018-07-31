<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once('class.multaUser.php');
require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once('./Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');
require_once('./Services/Form/classes/class.ilTextInputGUI.php');

/**
 * Class multaUserTableGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.00
 *
 */
class multaUserTableGUI extends ilTable2GUI {

	const TABLE_ID = 'tbl_mutla_users';
	/**
	 * @var ilMultiAssignPlugin
	 */
	protected $pl;
	/**
	 * @var array
	 */
	protected $filter = array();


	/**
	 * @param multaUserGUI $a_parent_obj
	 * @param string       $a_parent_cmd
	 */
	public function __construct(multaUserGUI $a_parent_obj, $a_parent_cmd) {
		/**
		 * @var ilCtrl $ilCtrl
		 */
		global $ilCtrl;
		$this->ctrl = $ilCtrl;
		$this->pl = ilMultiAssignPlugin::getInstance();
		$this->setId(self::TABLE_ID);
		$this->setPrefix(self::TABLE_ID);
		$this->setFormName(self::TABLE_ID);
		$this->ctrl->saveParameter($a_parent_obj, $this->getNavParameter());
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->parent_obj = $a_parent_obj;
		$this->setRowTemplate('tpl.row.html', 'Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/');
		$this->setEnableNumInfo(true);
		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
		$this->addColumns();
		$this->initFilters();
		$this->setDefaultOrderField('title');
		$this->setExternalSorting(true);
		$this->setExternalSegmentation(true);
		$this->setDisableFilterHiding(true);
		$this->parseData();
		$this->addCommandButton(multaUserGUI::CMD_SELECT_USER, $this->pl->txt('button_select_user'));
	}


	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		/**
		 * @var multaUser $multaUser
		 */
		$multaUser = multaUser::find($a_set['usr_id']);
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($k == 'actions') {
				$this->tpl->setCurrentBlock('radio');
				$this->tpl->setVariable('ID', $multaUser->getUsrId());
				$this->tpl->parseCurrentBlock();
				continue;
			}

			if ($this->isColumnSelected($k)) {
				if ($a_set[$k]) {
					$this->tpl->setCurrentBlock('td');
					$this->tpl->setVariable('VALUE', (is_array($a_set[$k]) ? implode(", ", $a_set[$k]) : $a_set[$k]));
					$this->tpl->parseCurrentBlock();
				} else {
					$this->tpl->setCurrentBlock('td');
					$this->tpl->setVariable('VALUE', '&nbsp;');
					$this->tpl->parseCurrentBlock();
				}
			}
		}
	}


	protected function parseData() {
		$this->determineOffsetAndOrder();
		$this->determineLimit();
		$multaUser = multaUser::getCollection();
		$multaUser->orderBy($this->getOrderField(), $this->getOrderDirection());
		$fitered = false;
		foreach ($this->filter as $field => $value) {
			if ($value) {
				$value = str_replace('%', '', $value);
				if (strlen($value) < 3) {
					ilUtil::sendFailure($this->pl->txt('msg_failure_more_characters_needed'));
					continue;
				}
				$fitered = true;
				$multaUser->where(array( $field => '%' . $value . '%' ), 'LIKE');
			}
		}

		if (!$fitered) {
			$multaUser->where(array( 'usr_id' => 4 ));
		}

		$this->setMaxCount($multaUser->count());
		if (!$multaUser->hasSets()) {
			//			ilUtil::sendInfo('Keine Ergebnisse für diesen Filter'); // TODO: Translate
		}
		$multaUser->limit($this->getOffset(), $this->getOffset() + $this->getLimit());
		$multaUser->orderBy('email');
		//		$multaUser->debug();
		$this->setData($multaUser->getArray());
	}


	/**
	 * @return array
	 */
	public function getSelectableColumns() {
		$cols['firstname'] = array( 'txt' => $this->pl->txt('usr_firstname'), 'default' => true, 'width' => 'auto', 'sort_field' => 'firstname' );
		$cols['lastname'] = array( 'txt' => $this->pl->txt('usr_lastname'), 'default' => true, 'width' => 'auto', 'sort_field' => 'lastname' );
		$cols['email'] = array( 'txt' => $this->pl->txt('usr_email'), 'default' => true, 'width' => 'auto', 'sort_field' => 'email' );
		$cols['login'] = array( 'txt' => $this->pl->txt('usr_login'), 'default' => true, 'width' => 'auto', 'sort_field' => 'login' );
		$cols['actions'] = array( 'txt' => $this->pl->txt('common_actions'), 'default' => true, 'width' => '50px', );

		return $cols;
	}


	private function addColumns() {
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($this->isColumnSelected($k)) {
				if ($v['sort_field']) {
					$sort = $v['sort_field'];
				} else {
					$sort = $k;
				}
				$this->addColumn($v['txt'], $sort, $v['width']);
			}
		}
	}


	protected function initFilters() {
		// firstname
		$te = new ilTextInputGUI($this->pl->txt('usr_firstname'), 'firstname');
		$this->addAndReadFilterItem($te);
		// lastname
		$te = new ilTextInputGUI($this->pl->txt('usr_lastname'), 'lastname');
		$this->addAndReadFilterItem($te);
		// email
		$te = new ilTextInputGUI($this->pl->txt('usr_email'), 'email');
		$this->addAndReadFilterItem($te);
		// login
		$te = new ilTextInputGUI($this->pl->txt('usr_login'), 'login');
		$this->addAndReadFilterItem($te);
	}


	/**
	 * @param ilFormPropertyGUI $item
	 */
	protected function addAndReadFilterItem(ilFormPropertyGUI $item) {
		$this->addFilterItem($item);
		$item->readFromSession();
		$this->filter[$item->getPostVar()] = $item->getValue();
	}


	public function resetOffset($a_in_determination = false) {
		parent::resetOffset($a_in_determination);
		$this->ctrl->setParameter($this->parent_obj, $this->getNavParameter(), $this->nav_value);
	}
}
