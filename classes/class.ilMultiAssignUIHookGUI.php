<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Config/class.multaConfig.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Block/class.multaPDBlock.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/class.multa.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/class.multaAccess.php');

/**
 * Class ilMultiAssignUIHookGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilMultiAssignUIHookGUI extends ilUIHookPluginGUI {

	/**
	 * Get html for a user interface area
	 *
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 *
	 * @internal param $
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = array()) {
		if (multaConfig::getValueById(multaConfig::F_SHOW_PD_BUTTON) && multaAccess::hasAccess()) {
			if ($a_comp == 'Services/PersonalDesktop' AND $a_part == 'right_column') {
				global $ilCtrl;
				if (multa::is50()) {
					$path = array( 'ilUIPluginRouterGUI', 'multaMainGUI' );
				} else {
					$path = array( 'ilRouterGUI', 'multaMainGUI' );
				}
				if ($ilCtrl->checkTargetClass($path)) {
					return array(
						'mode' => ilUIHookPluginGUI::PREPEND,
						'html' => $this->getBlockHTML()
					);
				}
			}
		}

		return array( 'mode' => ilUIHookPluginGUI::KEEP, 'html' => '' );
	}


	/**
	 * @return string
	 */
	protected function getBlockHTML() {
		$block = new multaPDBlock();

		return $block->getHTML();
	}
}

?>
