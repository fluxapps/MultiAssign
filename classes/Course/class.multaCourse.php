<?php
require_once('./Modules/Course/classes/class.ilCourseConstants.php');

/**
 * Class multaCourse
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 2.0.6
 */
class multaCourse {

	/**
	 * @param array $filter
	 * @param null  $sorting_field
	 * @param null  $order
	 *
	 * @return array
	 */
	public static function getAll($filter = array(), $sorting_field = NULL, $order = NULL) {
		global $ilDB, $ilUser;
		/**
		 * @var ilDB $ilDB
		 */
		$result = array();

		$query = "SELECT c.title, ref.ref_id FROM " . multaObj::TABLE_NAME . " c
                    INNER JOIN object_reference ref ON ref.obj_id = c.obj_id AND isNULL(ref.deleted)
                    INNER JOIN " . multaObj::TABLE_NAME . " r ON r.type = 'role' AND (r.title = CONCAT('il_crs_admin_',ref.ref_id))
                    INNER JOIN rbac_ua ua ON ua.usr_id = " . $ilUser->getId() . " AND ua.rol_id = r.obj_id
                    WHERE c.type = 'crs'";
		if ($sorting_field) {
			$query .= ' ORDER BY ' . $sorting_field . ' ' . ($order ? $order : 'ASC');
		}

		$set = $ilDB->query($query);
		while ($rec = $ilDB->fetchAssoc($set)) {
			$result[] = $rec;
		}

		return $result;
	}
}
