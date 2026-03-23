<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('MCCallbackHandler')) :

	class MCCallbackHandler {
		public $db;
		public $settings;
		public $siteinfo;
		public $request;
		public $account;
		public $response;
		public $bvinfo;

		public function __construct($db, $settings, $siteinfo, $request, $account, $response) {
			$this->db = $db;
			$this->settings = $settings;
			$this->siteinfo = $siteinfo;
			$this->request = $request;
			$this->account = $account;
			$this->response = $response;
			$this->bvinfo = new MCInfo($this->settings);
		}

		public function bvAdmExecuteWithoutUser() {
			$this->execute(array("bvadmwithoutuser" => true));
		}

		public function bvAdmExecuteWithUser() {
			$this->execute(array("bvadmwithuser" => true));
		}

		public function execute($resp = array()) {
			$params = $this->request->params;
			if (array_key_exists('disable_global_cache', $params)) {
				$GLOBALS['_wp_using_ext_object_cache'] = false;
			}

			$this->routeRequest();
			$resp = array(
				"request_info" => $this->request->info(),
				"site_info" => $this->siteinfo->info(),
				"account_info" => $this->account->info(),
				"bvinfo" => $this->bvinfo->info(),
				"api_pubkey" => substr(MCAccount::getApiPublicKey($this->settings), 0, 8)
			);
			$this->response->terminate($resp);
		}

		public function routeRequest() {
			switch ($this->request->wing) {
			case 'manage':
				require_once dirname( __FILE__ ) . '/wings/manage.php';
				$module = new MCManageCallback($this);
				break;
			case 'fs':
				require_once dirname( __FILE__ ) . '/wings/fs.php';
				$module = new MCFSCallback($this);
				break;
			case 'db':
				require_once dirname( __FILE__ ) . '/wings/db.php';
				$module = new MCDBCallback($this);
				break;
			case 'info':
				require_once dirname( __FILE__ ) . '/wings/info.php';
				$module = new MCInfoCallback($this);
				break;
			case 'dynsync':
				require_once dirname( __FILE__ ) . '/wings/dynsync.php';
				$module = new MCDynSyncCallback($this);
				break;
			case 'ipstr':
				require_once dirname( __FILE__ ) . '/wings/ipstore.php';
				$module = new MCIPStoreCallback($this);
				break;
			case 'wtch':
				require_once dirname( __FILE__ ) . '/wings/watch.php';
				$module = new MCWatchCallback($this);
				break;
			case 'brand':
				require_once dirname( __FILE__ ) . '/wings/brand.php';
				$module = new MCBrandCallback($this);
				break;
			case 'pt':
				require_once dirname( __FILE__ ) . '/wings/protect.php';
				$module = new MCProtectCallback($this);
				break;
			case 'act':
				require_once dirname( __FILE__ ) . '/wings/account.php';
				$module = new MCAccountCallback($this);
				break;
			case 'fswrt':
				require_once dirname( __FILE__ ) . '/wings/fs_write.php';
				$module = new MCFSWriteCallback();
				break;
			case 'actlg':
				require_once dirname( __FILE__ ) . '/wings/actlog.php';
				$module = new MCActLogCallback($this);
				break;
			case 'speed':
				require_once dirname( __FILE__ ) . '/wings/speed.php';
				$module = new MCSpeedCallback($this);
				break;
			case 'scrty':
				require_once dirname( __FILE__ ) . '/wings/security.php';
				$module = new MCSecurityCallback($this);
				break;
			default:
				require_once dirname( __FILE__ ) . '/wings/misc.php';
				$module = new MCMiscCallback($this);
				break;
			}
			$resp = $module->process($this->request);
			if ($resp === false) {
				$resp = array(
					"statusmsg" => "Bad Command",
					"status" => false);
			}
			$resp = array(
				$this->request->wing => array(
					$this->request->method => $resp
				)
			);
			$this->response->addStatus("callbackresponse", $resp);
			return 1;
		}
	}
endif;
