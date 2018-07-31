<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once('class.multaCourse.php');
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
class multaCourseTableGUI extends ilTable2GUI {

	const TBL_XDGL_REQUEST_OVERVIEWS = 'tbl_mutla_courses';
	const GLUE = ' > ';
	/**
	 * @var ilMultiAssignPlugin
	 */
	protected $pl;
	/**
	 * @var array
	 */
	protected $filter = array();


	/**
	 * @param multaCourseGUI $a_parent_obj
	 * @param string         $a_parent_cmd
	 */
	public function __construct(multaCourseGUI $a_parent_obj, $a_parent_cmd) {
		/**
		 * @var ilCtrl $ilCtrl
		 */
		global $ilCtrl;
		$this->ctrl = $ilCtrl;
		$this->pl = ilMultiAssignPlugin::getInstance();
		$this->setId(self::TBL_XDGL_REQUEST_OVERVIEWS);
		$this->setPrefix(self::TBL_XDGL_REQUEST_OVERVIEWS);
		$this->setFormName(self::TBL_XDGL_REQUEST_OVERVIEWS);
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
		$this->parseData();
		$this->addCommandButton(multaCourseGUI::CMD_CANCEL, $this->pl->txt('crs_cancel'));
		$this->addCommandButton(multaCourseGUI::CMD_DO_ASSIGNMENTS, $this->pl->txt('crs_do_assignments'));
		//		$this->addMultiItemSelectionButton('id', null, 'select_all', $this->pl->txt('crs_select_all'));
		//		$this->setSe
		$this->setSelectAllCheckbox('id');
	}


	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($k == 'selection') {
				$this->tpl->setCurrentBlock('checkbox_front');
				$this->tpl->setVariable('ID', $a_set['ref_id']);
				$this->tpl->parseCurrentBlock();
				continue;
			}
			if ($k == 'role') {
				$this->tpl->setCurrentBlock('td');
				$this->tpl->setVariable('VALUE', $this->getRoleSelector($a_set['ref_id'], multaAssignment::ROLE_ADMIN));
				$this->tpl->parseCurrentBlock();
				continue;
			}
			if ($k == 'path') {
				$this->tpl->setCurrentBlock('td');
				$this->tpl->setVariable('VALUE', $this->getFullPath($a_set['ref_id']));
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
		$data = multaCourse::getAll($this->filter, $this->getOrderField(), $this->getOrderDirection());
		$this->setData($data);
	}


	/**
	 * @return array
	 */
	public function getSelectableColumns() {
		$cols['selection'] = array( 'txt' => $this->pl->txt('crs_selection'), 'default' => true, 'width' => '60px', 'sort_field' => NULL );
		$cols['role'] = array( 'txt' => $this->pl->txt('crs_role'), 'default' => true, 'width' => '100px', 'sort_field' => NULL );
		$cols['title'] = array( 'txt' => $this->pl->txt('crs_title'), 'default' => true, 'width' => '30%', 'sort_field' => 'title' );
		$cols['path'] = array( 'txt' => $this->pl->txt('crs_path'), 'default' => true, 'width' => 'auto', 'sort_field' => NULL );

		return $cols;
	}


	private function addColumns() {
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($this->isColumnSelected($k)) {
				if ($v['sort_field']) {
					$sort = $v['sort_field'];
				} else {
					$sort = NULL;
				}
				$this->addColumn($v['txt'], $sort, $v['width']);
			}
		}
	}


	/**
	 * @param int $selected
	 *
	 * @return string
	 */
	protected function getRoleSelector($id, $selected) {
		$roles = array(
			multaAssignment::ROLE_MEMBER => $this->pl->txt('main_role_' . multaAssignment::ROLE_MEMBER),
			multaAssignment::ROLE_TUTOR => $this->pl->txt('main_role_' . multaAssignment::ROLE_TUTOR),
			multaAssignment::ROLE_ADMIN => $this->pl->txt('main_role_' . multaAssignment::ROLE_ADMIN),
		);
		$selection_menu = '<select name=\'role[' . $id . ']\'>';
		foreach ($roles as $value => $role) {
			$sel = ($selected == $value ? 'selected' : '');
			$selection_menu .= '<option value=\'' . $value . '\' ' . $sel . '>' . $role . '</option>';
		}

		$selection_menu .= '</select>';

		return $selection_menu;
	}


	protected function initFilters() {
		// firstname
		//		$te = new ilTextInputGUI($this->pl->txt('usr_firstname'), 'firstname');
		//		$this->addAndReadFilterItem($te);
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


	/**
	 * @param int $ref_id
	 *
	 * @return string
	 */
	protected function getFullPath($ref_id) {
		global $tree;
		/**
		 * @var ilTree $tree
		 */
		$full_path = array();
		foreach ($tree->getPathFull($ref_id) as $path) {
			$full_path[] = $path['title'];
		}
		if (count($full_path) < 6) {
			$full_path_string = implode(self::GLUE, $full_path);

			return $full_path_string;
		} else {
			$full_path_limited = array();
			for ($x = 0; $x < 3; $x ++) {
				$full_path_limited[] = $full_path[$x];
			}
			$full_path_limited[] = '...';
			$full_path_limited[] = $full_path[count($full_path) - 2];
			$full_path_limited[] = $full_path[count($full_path) - 1];
			$full_path_string = implode(self::GLUE, $full_path_limited);

			return $full_path_string;
		}
	}
}
