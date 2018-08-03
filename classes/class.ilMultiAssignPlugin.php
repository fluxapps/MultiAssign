<?php

require_once('./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php');
require_once __DIR__ . "/Assignment/class.multaAssignment.php";
require_once __DIR__ . "/Config/class.multaConfig.php";

/**
 * ilMultiAssignPlugin
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilMultiAssignPlugin extends ilUserInterfaceHookPlugin {

	const PLUGIN_ID = 'multa';
	const PLUGIN_NAME = 'MultiAssign';
	/**
	 * @var ilMultiAssignPlugin
	 */
	protected static $instance;


	/**
	 * @return ilMultiAssignPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var ilDB
	 */
	protected $db;


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();

		global $DIC;

		$this->db = $DIC->database();
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	//	public function txt($a_var) {
	//		require_once('./Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php');
	//
	//		return sragPluginTranslator::getInstance($this)->active()->write()->txt($a_var);
	//	}

	/**
	 * @return bool
	 */
	protected function beforeUninstall() {
		$this->db->dropTable(multaAssignment::TABLE_NAME, false);
		$this->db->dropTable(multaConfig::TABLE_NAME, false);

		return true;
	}
}
