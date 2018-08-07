<?php

//use SRAG\Plugins\Hub2\Origin\ubPeriodList; // TODO: Hub2

require_once('./Modules/Course/classes/class.ilCourseConstants.php');
require_once('./Customizing/global/origins/hubCourse/unibasSLCM/class.ubPeriods.php');

/**
 * Class multaCourse
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 2.0.6
 */
class multaCourse {

	/**
	 * @param array $filters
	 * @param null  $sorting_field
	 * @param null  $order
	 *
	 * @return array
	 */
	public static function getAll($filters = array(), $sorting_field = NULL, $order = NULL) {
		global $ilDB, $ilUser;
		/**
		 * @var ilDB $ilDB
		 */
		$result = array();

		$user_id = $ilUser->getId();
		$query = "SELECT c.title, ref.ref_id, sync.ext_id, hub.period FROM " . multaObj::TABLE_NAME . " c
                    INNER JOIN object_reference ref ON ref.obj_id = c.obj_id AND isNULL(ref.deleted)
                    INNER JOIN " . multaObj::TABLE_NAME . " r ON r.type = 'role' AND (r.title = CONCAT('il_crs_admin_',ref.ref_id))
                    INNER JOIN rbac_ua ua ON ua.usr_id = {$user_id} AND ua.rol_id = r.obj_id
                    LEFT JOIN sr_hub_sync_history AS sync ON sync.ilias_id = ref.ref_id AND sync.sr_hub_origin_id = 1
                    LEFT JOIN sr_hub_course AS hub ON hub.ext_id = sync.ext_id

                    WHERE c.type = 'crs'";

		if (count($filters)) {
			foreach ($filters as $key => $value) {
				if ($value) {
					$query .= " AND {$key} LIKE '%{$value}%'";
				}
			}
		}

		if ($sorting_field) {
			$query .= ' ORDER BY ' . $sorting_field . ' ' . ($order ? $order : 'ASC');
		}

		$set = $ilDB->query($query);
		while ($rec = $ilDB->fetchAssoc($set)) {
			$result[] = $rec;
		}

		return $result;
	}


	/**
	 * @return array
	 */
	public static function getAllPeriods() {
		$select = array( NULL => ilMultiAssignPlugin::getInstance()->txt('crs_period_null') );
		foreach (ubPeriodList::getInstance()->getPeriods() as $period) {
			$select[$period->getId()] = $period->getYear() . " " . $period->getSemesterString();
		}

		return $select;
	}
}
