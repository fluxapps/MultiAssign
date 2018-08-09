<?php
require_once('./Services/ActiveRecord/class.ActiveRecord.php');
require_once('./Modules/Course/classes/class.ilObjCourse.php');

/**
 * Class multaAssignment
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class multaAssignment extends ActiveRecord {

	const TABLE_NAME = 'multa_assignment';
	const ROLE_ADMIN = ilCourseConstants::CRS_ADMIN;
	const ROLE_TUTOR = ilCourseConstants::CRS_TUTOR;
	const ROLE_MEMBER = ilCourseConstants::CRS_MEMBER;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @con_is_primary true
	 * @con_is_unique  true
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 * @con_sequence   true
	 */
	protected $id = 0;
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $request_token = '';
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $crs_id = 0;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $role = 0;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $usr_id = 0;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $requester_usr_id = 0;
	/**
	 * @var DateTime
	 *
	 * @con_has_field   true
	 * @con_fieldtype   timestamp
	 * @con_declared_as DateTime
	 */
	protected $create_date;


	/**
	 * @param array $id
	 * @param int   $usr_id
	 *
	 * @return string
	 */
	public static function doAssignments(array $id, $usr_id) {
		global $ilUser;
		/**
		 * @var ilObjUser $ilUser
		 */
		do {
			$token = md5(rand(0, 100) * time());
		} while (self::where(array( 'request_token' => $token ))->hasSets());

		foreach ($id as $ref_id) {
			$role = $_POST['role'][$ref_id];
			$obj = new self();
			$obj->setRequestToken($token);
			$obj->setUsrId($usr_id);
			$obj->setCrsId($ref_id);
			$obj->setRole($role);
			$obj->setRequesterUsrId($ilUser->getId());
			$obj->create();
			$obj->assignUser();
		}

		return $token;
	}


	public function assignUser() {
		$ilObjCourse = new ilObjCourse($this->getCrsId());
		$ilObjCourse->getMemberObject()->add($this->getUsrId(), $this->getRole());
	}


	/**
	 * @return int
	 */
	public function getCrsId() {
		return $this->crs_id;
	}


	/**
	 * @param int $crs_id
	 */
	public function setCrsId($crs_id) {
		$this->crs_id = $crs_id;
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getRequestToken() {
		return $this->request_token;
	}


	/**
	 * @param string $request_token
	 */
	public function setRequestToken($request_token) {
		$this->request_token = $request_token;
	}


	/**
	 * @return int
	 */
	public function getRole() {
		return $this->role;
	}


	/**
	 * @param int $role
	 */
	public function setRole($role) {
		$this->role = $role;
	}


	/**
	 * @return int
	 */
	public function getUsrId() {
		return $this->usr_id;
	}


	/**
	 * @param int $usr_id
	 */
	public function setUsrId($usr_id) {
		$this->usr_id = $usr_id;
	}


	/**
	 * @return DateTime
	 */
	public function getCreateDate() {
		return $this->create_date;
	}


	/**
	 * @param DateTime $create_date
	 */
	public function setCreateDate($create_date) {
		$this->create_date = $create_date;
	}


	/**
	 * @return int
	 */
	public function getRequesterUsrId() {
		return $this->requester_usr_id;
	}


	/**
	 * @param int $requester_usr_id
	 */
	public function setRequesterUsrId($requester_usr_id) {
		$this->requester_usr_id = $requester_usr_id;
	}


	/**
	 * @param string $field_name
	 * @param string $field_value
	 *
	 * @return DateTime|mixed
	 */
	public function wakeUp($field_name, $field_value) {
		if ($field_name == 'create_date') {
			return new DateTime($field_value);
		}

		return parent::wakeUp($field_name, $field_value);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|string
	 */
	public function sleep($field_name) {
		if ($field_name == 'create_date') {
			$datetime = $this->getCreateDate();

			return $datetime->format(DATE_ISO8601);
		}

		return parent::sleep($field_name);
	}


	public function create() {
		$this->setCreateDate(new DateTime());
		parent::create();
	}
}
