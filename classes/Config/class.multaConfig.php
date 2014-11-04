<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/class.ActiveRecord.php');

/**
 * Class multaConfig
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class multaConfig extends ActiveRecord {

	const TABLE_NAME = 'multa_config';
	const F_ROLES_ADMIN = 'roles';


	/**
	 * @return string
	 * @description Return the Name of your Database Table
	 * @deprecated
	 */
	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function get($id) {
		/**
		 * @var $obj multaConfig
		 */
		$obj = self::findOrGetInstance($id);

		return json_decode($obj->getValue());
	}


	/**
	 * @param $id
	 * @param $value
	 */
	public static function set($id, $value) {
		/**
		 * @var $obj multaConfig
		 */
		$obj = self::find($id);
		$encoded_value = json_encode($value);
		if ($obj instanceof multaConfig) {
			$obj->setValue($encoded_value);
			$obj->update();
		} else {
			$obj = new self();
			$obj->setId($id);
			$obj->setValue($encoded_value);
			$obj->create();
		}
	}


	/**
	 * @var int
	 *
	 * @con_is_primary true
	 * @con_is_unique  true
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $id = '';
	/**
	 * @var string
	 *
	 * @con_has_field true
	 * @con_fieldtype text
	 * @con_length    256
	 */
	protected $value = '';


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
	public function getValue() {
		return $this->value;
	}


	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
}

?>
