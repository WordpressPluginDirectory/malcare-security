<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('MCWP2FAEmailOTPLogin')) :

/**
 * Wires email sign-in codes into the login POST.
 *
 * Core has already accepted the username and password by the time this runs, and
 * it does so again on the submission that carries the code, so every step is
 * behind valid credentials.
 */
class MCWP2FAEmailOTPLogin {
	const REJECTED_MESSAGE = 'That sign-in code is not valid. Request a new code and try again.';
	const UNAVAILABLE_MESSAGE = 'A sign-in code could not be prepared. Please try again later.';
	const DELIVERY_MESSAGE = 'A sign-in code could not be sent. Please try again later.';
	const THROTTLED_MESSAGE = 'Please wait %s before requesting another code.';

	public static function authenticate($user) {
		$resend = MCHelper::getRawParam('POST', 'twofa_resend') === '1';
		$code = MCHelper::getRawParam('POST', 'twofa_code');

		if (!$resend && is_string($code) && trim($code) !== '') {
			if (MCWP2FAEmailOTP::redeem($user, $code)) {
				return $user;
			}

			return new WP_Error('invalid_2fa_code', self::REJECTED_MESSAGE);
		}

		return self::challenge($user);
	}

	private static function challenge($user) {
		if (!is_string($user->user_email) || !is_email($user->user_email)) {
			MCWP2FA::sendFailure(MCWP2FA::CONFIG_MESSAGE);
		}

		$wait = MCWP2FAEmailOTP::secondsUntilNextSend($user->ID);
		if ($wait > 0) {
			// A code they can still use beats a new one they are not allowed yet.
			if (MCWP2FAEmailOTP::hasLiveCode($user->ID)) {
				return self::askForCode($user, $wait, false);
			}

			// Not a WP_Error: our own send limit must not register as a failed
			// login with the firewall's lockout counter.
			MCWP2FA::sendFailure(sprintf(self::THROTTLED_MESSAGE, MCWP2FA::humanWait($wait)), array('resend_after' => $wait));
		}

		$code = MCWP2FAEmailOTP::issue($user);
		if ($code === null) {
			MCWP2FA::sendFailure(self::UNAVAILABLE_MESSAGE);
		}

		if (!MCWP2FAEmailOTPSender::send($user, $code, MCWP2FAEmailOTP::LIFETIME)) {
			// Nothing was delivered, so leave no code behind that could be guessed.
			MCWP2FAEmailOTP::discard($user->ID);
			MCWP2FA::sendFailure(self::DELIVERY_MESSAGE);
		}

		return self::askForCode($user, MCWP2FAEmailOTP::secondsUntilNextSend($user->ID), true);
	}

	private static function askForCode($user, $resend_after, $code_sent) {
		wp_send_json_success(array(
			'twofa_enabled' => true,
			'twofa_method' => 'email_otp',
			'masked_destination' => self::maskEmail($user->user_email),
			'resend_after' => $resend_after,
			'code_sent' => $code_sent
		));
		exit;
	}

	private static function maskEmail($email) {
		$parts = explode('@', $email, 2);
		if (count($parts) !== 2) {
			return '';
		}

		$labels = explode('.', $parts[1]);
		$host = array_shift($labels);
		$suffix = count($labels) > 0 ? '.' . implode('.', $labels) : '';

		return substr($parts[0], 0, 1) . '***@' . substr($host, 0, 1) . '***' . $suffix;
	}
}
endif;
