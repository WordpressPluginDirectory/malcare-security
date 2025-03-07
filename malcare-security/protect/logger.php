<?php
if (!defined('ABSPATH') && !defined('MCDATAPATH')) exit;

if (!class_exists('MCProtectLogger_V592')) :
require_once dirname( __FILE__ ) . '/logger/fs.php';
require_once dirname( __FILE__ ) . '/logger/db.php';

class MCProtectLogger_V592 {
	private $log_destination;

	const TYPE_FS = 0;
	const TYPE_DB = 1;

	function __construct($name, $type = MCProtectLogger_V592::TYPE_DB) {
		if ($type == MCProtectLogger_V592::TYPE_FS) {
			$this->log_destination = new MCProtectLoggerFS_V592($name);
		} else {
			$this->log_destination = new MCProtectLoggerDB_V592($name);
		}
	}

	public function log($data) {
		$this->log_destination->log($data);
	}
}
endif;