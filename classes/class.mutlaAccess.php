<?php

/**
 * Class multaAccess
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class multaAccess {

	/**
	 * @param $crs_ref_id
	 *
	 * @return bool
	 */
	public static function checkAccessForCourseRefId($crs_ref_id) {
		/**
		 * @var $ilAccess ilAccessHandler
		 */
		global $ilAccess;

		return $ilAccess->checkAccess('write', '', $crs_ref_id);
	}
}

?>
