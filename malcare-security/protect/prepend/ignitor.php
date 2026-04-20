<?php
if (!defined('MCDATAPATH')) exit;

if (defined('MCCONFKEY')) {
	require_once dirname( __FILE__ ) . '/../protect.php';

	MCProtect_V644::init(MCProtect_V644::MODE_PREPEND);
}