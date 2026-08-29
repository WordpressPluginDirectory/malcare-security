<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('MCWP2FATimeOTPLogin')) :

class MCWP2FATimeOTPLogin {
	const SECRET_LENGTH = 32;
	const MAX_ATTEMPTS = 5;
	const ATTEMPT_WINDOW = 900;
	const THROTTLED_MESSAGE = 'Too many incorrect codes. Please try again in %s.';

	public static function authenticate($user) {
		$code = MCHelper::getRawParam('POST', 'twofa_code');
		if (!is_string($code) || $code === '') {
			wp_send_json_success(array('twofa_enabled' => true));
			exit;
		}

		$secret = self::loadSecret($user);
		if ($secret === null) {
			MCWP2FA::sendFailure(MCWP2FA::CONFIG_MESSAGE);
		}

		$wait = self::secondsUntilNextAttempt($user->ID);
		if ($wait > 0) {
			MCWP2FA::sendFailure(sprintf(self::THROTTLED_MESSAGE, MCWP2FA::humanWait($wait)));
		}

		# Counted before the code is checked. An attempt we cannot count must not
		# happen, and concurrent attempts must not share one count.
		if (!self::recordAttempt($user->ID)) {
			MCWP2FA::sendFailure(sprintf(self::THROTTLED_MESSAGE, MCWP2FA::humanWait(self::ATTEMPT_WINDOW)));
		}

		$slice = null;
		if (ctype_digit($code)) {
			$slice = MCWP2FATimeOTP::matchingSlice($secret, $code);
		}

		$state = self::loadState($user->ID);

		# RFC 6238: a code from a slice already accepted must not be accepted
		# again. Without this the same code works for its whole 90 second window.
		if ($slice === null || $slice <= $state['last_slice']) {
			return new WP_Error('invalid_2fa_code', esc_html(self::invalidCodeMessage()));
		}

		# Without the slice recorded the code stays replayable for the rest of its
		# window, so a login that cannot be recorded is refused rather than left
		# unaccounted for.
		if (!self::recordSuccess($user->ID, $state, $slice)) {
			MCWP2FA::sendFailure(MCWP2FA::CONFIG_MESSAGE);
		}

		return $user;
	}

	public static function clearState($user_id) {
		delete_user_meta($user_id, MCWP2FA::ATTEMPTS_META_KEY);

		return !metadata_exists('user', $user_id, MCWP2FA::ATTEMPTS_META_KEY);
	}

	private static function secondsUntilNextAttempt($user_id) {
		$state = self::loadState($user_id);
		$now = time();

		if ($now - $state['window_at'] >= self::ATTEMPT_WINDOW) {
			return 0;
		}

		if ($state['failures'] < self::MAX_ATTEMPTS) {
			return 0;
		}

		return $state['window_at'] + self::ATTEMPT_WINDOW - $now;
	}

	private static function recordAttempt($user_id) {
		$now = time();
		$current = self::loadState($user_id);

		$window_at = $current['window_at'];
		$failures = $current['failures'];
		if ($now - $window_at >= self::ATTEMPT_WINDOW) {
			$window_at = $now;
			$failures = 0;
		}

		$next = array(
			'failures' => $failures + 1,
			'window_at' => $window_at,
			'last_slice' => $current['last_slice']
		);

		return update_user_meta($user_id, MCWP2FA::ATTEMPTS_META_KEY, $next, $current) !== false;
	}

	private static function recordSuccess($user_id, $current, $slice) {
		$next = array('failures' => 0, 'window_at' => time(), 'last_slice' => $slice);

		return update_user_meta($user_id, MCWP2FA::ATTEMPTS_META_KEY, $next, $current) !== false;
	}

	private static function loadState($user_id) {
		$blank = array('failures' => 0, 'window_at' => 0, 'last_slice' => 0);
		$state = get_user_meta($user_id, MCWP2FA::ATTEMPTS_META_KEY, true);

		if (!self::validState($state, $blank)) {
			delete_user_meta($user_id, MCWP2FA::ATTEMPTS_META_KEY);
			return $blank;
		}

		return $state;
	}

	private static function validState($state, $blank) {
		if (!is_array($state)) {
			return false;
		}

		foreach ($blank as $field => $default) {
			if (!isset($state[$field]) || !is_int($state[$field]) || $state[$field] < 0) {
				return false;
			}
		}

		return true;
	}

	private static function loadSecret($user) {
		$info = MCWP2FAUtils::getSecretInfo(get_user_meta($user->ID, MCWP2FA::SECRET_META_KEY, true));
		if (is_null($info['secret']) || is_null($info['is_encrypted'])) {
			return null;
		}

		$secret = $info['secret'];
		if ($info['is_encrypted'] === true) {
			# An encrypted secret with no key to open it is a misconfiguration, not a usable secret.
			if (!defined('SECURE_AUTH_KEY')) {
				return null;
			}

			$decrypted = MCHelper::opensslDecrypt($secret, MCWP2FA::$cipher_algo, SECURE_AUTH_KEY);
			if ($decrypted[0] === false) {
				return null;
			}

			$secret = $decrypted[1];
		}

		if (!is_string($secret) || strlen($secret) !== self::SECRET_LENGTH) {
			return null;
		}

		return $secret;
	}

	private static function invalidCodeMessage() {
		return MCWP2FA::whitelabelMessage('2fa_error_message', MCWP2FA::INVALID_CODE_MESSAGE);
	}
}
endif;
