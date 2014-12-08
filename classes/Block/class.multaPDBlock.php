<?php
/* Copyright (c) 1998-2012 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once('Services/Block/classes/class.ilBlockGUI.php');

/**
 * BlockGUI class for Personal Desktop jQuery Serach Block
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           $Id$
 * @ilCtrl_IsCalledBy ilPDSearchBlockGUI: ilColumnGUI
 */
class multaPDBlock extends ilBlockGUI {

	/**
	 * @var string
	 */
	protected static $block_type = 'multa';
	/**
	 * @var bool
	 */
	protected $allow_moving = false;


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->pl = ilMultiAssignPlugin::getInstance();
//		$this->pl->updateLanguageFiles();
		$this->setTitle($this->pl->txt('block_title'));
	}


	/**
	 * @return string
	 */
	static function getBlockType() {
		return self::$block_type;
	}


	/**
	 * @return bool
	 */
	public static function isRepositoryObject() {
		return false;
	}


	public function fillDataSection() {
		global $ilCtrl;
		/**
		 * @var $ilCtrl ilCtrl
		 */
		$path = array( 'ilRouterGUI', 'multaMainGUI' );

		$link = $ilCtrl->getLinkTargetByClass($path);
		$button = "<a href='" . $link . "' class='submit'>" . $this->pl->txt('block_button') . '</a>';

		$this->setDataSection($button);
	}
}