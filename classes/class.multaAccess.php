<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Config/class.multaConfig.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/class.ilMultiAssignPlugin.php');

/**
 * Class multaAccess
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class multaAccess {

	/**
	 * @var array
	 */
	protected static $access_cache = array();


	/**
	 * @return bool
	 */
	public static function hasAccess() {
		if (!ilMultiAssignPlugin::getInstance()->isActive()) {
			return false;
		}
		global $ilUser, $rbacreview;
		/**
		 * @var $ilUser     ilObjUser
		 * @var $rbacreview ilRbacReview
		 */
		$usr_id = $ilUser->getId();
		if (!isset($access_cache[$usr_id])) {
			$assigned = $rbacreview->isAssignedToAtLeastOneGivenRole($ilUser->getId(), multaConfig::get(multaConfig::F_ROLES_ADMIN));
			$access_cache[$usr_id] = $assigned;
		}

		return $access_cache[$usr_id];
	}
}

?>
