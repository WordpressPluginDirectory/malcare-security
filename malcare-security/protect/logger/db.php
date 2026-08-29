<?php
if (!defined('ABSPATH') && !defined('MCDATAPATH')) exit;

if (!class_exists('MCProtectLoggerDB_V672')) :
class MCProtectLoggerDB_V672 {
	private $tablename;
	private $bv_tablename;

	const MAXROWCOUNT = 100000;

	function __construct($tablename) {
		$this->tablename = $tablename;
		$this->bv_tablename = MCProtect_V672::$db->getBVTable($tablename);
	}

	public function log($data) {
		if (is_array($data)) {
			if (MCProtect_V672::$db->rowsCount($this->bv_tablename) > MCProtectLoggerDB_V672::MAXROWCOUNT) {
				MCProtect_V672::$db->deleteRowsFromtable($this->tablename, 1);
			}

			MCProtect_V672::$db->replaceIntoBVTable($this->tablename, $data);
		}
	}
}
endif;