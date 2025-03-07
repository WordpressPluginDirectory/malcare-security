<?php
if (!defined('ABSPATH') && !defined('MCDATAPATH')) exit;

if (!class_exists('MCProtectLoggerDB_V592')) :
class MCProtectLoggerDB_V592 {
	private $tablename;
	private $bv_tablename;

	const MAXROWCOUNT = 100000;

	function __construct($tablename) {
		$this->tablename = $tablename;
		$this->bv_tablename = MCProtect_V592::$db->getBVTable($tablename);
	}

	public function log($data) {
		if (is_array($data)) {
			if (MCProtect_V592::$db->rowsCount($this->bv_tablename) > MCProtectLoggerDB_V592::MAXROWCOUNT) {
				MCProtect_V592::$db->deleteRowsFromtable($this->tablename, 1);
			}

			MCProtect_V592::$db->replaceIntoBVTable($this->tablename, $data);
		}
	}
}
endif;