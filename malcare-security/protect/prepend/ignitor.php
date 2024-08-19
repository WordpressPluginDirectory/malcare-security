<?php
if (!defined('MCDATAPATH')) exit;

if (defined('MCCONFKEY')) {
	require_once dirname( __FILE__ ) . '/../protect.php';

	MCProtect_V567::init(MCProtect_V567::MODE_PREPEND);
}