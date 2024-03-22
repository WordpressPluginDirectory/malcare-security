<?php
if (!defined('MCDATAPATH')) exit;

if (defined('MCCONFKEY')) {
	require_once dirname( __FILE__ ) . '/../protect.php';

	MCProtect_V553::init(MCProtect_V553::MODE_PREPEND);
}