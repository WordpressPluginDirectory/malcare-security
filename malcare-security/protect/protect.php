<?php
if (!defined('ABSPATH') && !defined('MCDATAPATH')) exit;

if (!class_exists('MCProtect_V644')) :
require_once dirname( __FILE__ ) . '/logger.php';
require_once dirname( __FILE__ ) . '/ipstore.php';
require_once dirname( __FILE__ ) . '/request.php';
require_once dirname( __FILE__ ) . '/wp_user.php';
require_once dirname( __FILE__ ) . '/lib.php';
require_once dirname( __FILE__ ) . '/fw.php';
require_once dirname( __FILE__ ) . '/lp.php';
require_once dirname( __FILE__ ) . '/../helper.php';

class MCProtect_V644 {
	public static $settings;
	public static $db;
	public static $info;

	const MODE_PREPEND = 0;
	const MODE_WP      = 1;

	const CONF_VERSION = '2';

	public static function init($mode) {
		if (defined('WP_CLI') && WP_CLI) {
			return false;
		}

		if ($mode == MCProtect_V644::MODE_PREPEND) {
			$config_file = MCDATAPATH .  MCCONFKEY . '-' . 'mc.conf';
			$config = MCProtectUtils_V644::parseFile($config_file);

			if (empty($config['time']) || !($config['time'] > time() - (48*3600)) ||
					!isset($config['mc_conf_version']) ||
					(MCProtect_V644::CONF_VERSION !== $config['mc_conf_version'])) {
				return false;

			}

			$brand_name = array_key_exists('brandname', $config) ? $config['brandname'] : 'Protect';
			$request_ip_header = array_key_exists('ipheader', $config) ? $config['ipheader'] : null;
			$req_config = array_key_exists('reqconfig', $config) ? $config['reqconfig'] : array();
			$request = new MCProtectRequest_V644($request_ip_header, $req_config);
			$fw_config = array_key_exists('fw', $config) ? $config['fw'] : array();

			MCProtectFW_V644::getInstance($mode, $request, $fw_config, $brand_name)->init();
		} else {
			$plug_config = self::$settings->getOption(self::$info->services_option_name);
			$config = array_key_exists('protect', $plug_config) ? $plug_config['protect'] : array();
			if (!is_array($config) || !array_key_exists('mc_conf_version', $config) ||
					(MCProtect_V644::CONF_VERSION !== $config['mc_conf_version'])) {

				return false;
			}

			$brand_name = self::$info->getBrandName();
			$request_ip_header = array_key_exists('ipheader', $config) ? $config['ipheader'] : null;
			$req_config = array_key_exists('reqconfig', $config) ? $config['reqconfig'] : array();
			$request = new MCProtectRequest_V644($request_ip_header, $req_config);
			$fw_config = array_key_exists('fw', $config) ? $config['fw'] : array();
			$lp_config = array_key_exists('lp', $config) ? $config['lp'] : array();

			MCProtectFW_V644::getInstance($mode, $request, $fw_config, $brand_name)->init();
			MCProtectLP_V644::getInstance($request, $lp_config, $brand_name)->init();
		}
	}

	public static function uninstall() {
		self::$settings->deleteOption('bvptconf');
		self::$settings->deleteOption('bvptplug');
		MCProtectIpstore_V644::uninstall();
		MCProtectFW_V644::uninstall();
		MCProtectLP_V644::uninstall();

		MCProtect_V644::removeWPPrepend();
		MCProtect_V644::removePHPPrepend();
		MCProtect_V644::removeMCData();

		return true;
	}

	private static function removeWPPrepend() {
		$wp_conf_paths = array(
			rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/wp-config.php",
			rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/../wp-config.php"
		);

		if (file_exists($wp_conf_paths[0])) {
			$fname = $wp_conf_paths[0];
		} elseif (file_exists($wp_conf_paths[1])) {
			$fname = $wp_conf_paths[1];
		} else {
			return;
		}

		$pattern = "@include '" . rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/malcare-waf.php" . "';";
		
		MCProtectUtils_V644::fileRemovePattern($fname, $pattern);
	}

	private static function removePHPPrepend() {
		MCProtect_V644::removeHtaccessPrepend();
		MCProtect_V644::removeUseriniPrepend();
	}

	private static function removeHtaccessPrepend() {
		$pattern = "/# MalCare WAF(.|\n)*# END MalCare WAF/i";

		MCProtectUtils_V644::fileRemovePattern(rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/.htaccess", $pattern, true);
	}

	private static function removeUseriniPrepend() {
		$pattern = "/; MalCare WAF(.|\n)*; END MalCare WAF/i";

		MCProtectUtils_V644::fileRemovePattern(rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/.user.ini", $pattern, true);
	}

	private static function removeMCData() {
		$content_dir = defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR : rtrim(ABSPATH, DIRECTORY_SEPARATOR) . "/wp-content";
		$mc_data_dir = $content_dir . "/mc_data";

		MCProtectUtils_V644::rrmdir($mc_data_dir);
	}
}
endif;