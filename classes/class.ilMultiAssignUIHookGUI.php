<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Hub/classes/Sync/class.hubSyncHistory.php');

/**
 * Class ilMultiAssignUIHookGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilMultiAssignUIHookGUI extends ilUIHookPluginGUI {

	/**
	 * @var array
	 */
	protected static $loaded = array();


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	protected static function isLoaded($key) {
		return self::$loaded[$key] == 1;
	}


	/**
	 * @param $key
	 */
	protected static function setLoaded($key) {
		self::$loaded[$key] = 1;
	}


	/**
	 * @var int
	 */
	protected static $num = 0;


	/**
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 *
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = array()) {
		/**
		 * @var $ilCtrl       ilCtrl
		 * @var $tpl          ilTemplate
		 * @var $ilToolbar    ilToolbarGUI
		 */
		global $ilCtrl;

		if (!self::isLoaded('copy_users')) {
			if ($_GET['cmdClass'] == 'ilobjcoursegui' AND $_GET['cmd'] == 'members') {
				global $ilToolbar;
				if ($ilToolbar instanceof ilToolbarGUI) {
					//					var_dump($ilToolbar->getId()); // FSX
					self::$num ++;
					if (self::$num == 10) {
						$save = $ilToolbar->items[6];
						unset($ilToolbar->items[6]);

						$ilCtrl->setParameterByClass('cpusrCourseSelectorGUI', 'origin_ref_id', $_GET['ref_id']);
						$link = $ilCtrl->getLinkTargetByClass(array( 'ilRouterGUI', 'cpusrCourseSelectorGUI' ));
						$ilToolbar->addButton('Benutzer aus and. WS übernehmen', $link, '', '', 'copy_users');
						$ilToolbar->addSeparator();
						$ilToolbar->items[] = $save;

						self::setLoaded('copy_users');
					}
				}
			}
		}

		return array( 'mode' => ilUIHookPluginGUI::KEEP, 'html' => '' );
	}
}

?>
