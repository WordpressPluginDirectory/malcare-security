<?php
if (!defined('ABSPATH')) exit;
?>
<section class="mc-connect-card" aria-labelledby="mc-connect-form-title">
	<div class="mc-connect-card-heading">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
			<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
		</svg>
		<h2 id="mc-connect-form-title">Connect your site to MalCare</h2>
	</div>

	<form class="mc-connect-form" action="<?php echo esc_url($this->bvinfo->appUrl() . '/plugin/signup'); ?>" method="post" name="signup">
		<input type="hidden" name="bvsrc" value="wpplugin">
		<input type="hidden" name="origin" value="protect">
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Each site-information value is escaped by siteInfoTags().
			echo $this->siteInfoTags();
		?>

		<div class="mc-connect-field">
			<label for="email">Email address <span aria-hidden="true">*</span></label>
			<div class="mc-connect-input-shell">
				<svg viewBox="0 0 16 14" fill="none" stroke="currentColor" aria-hidden="true">
					<path d="m15 3-6.15 3.9a1.6 1.6 0 0 1-1.7 0L1 3M2 1h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z"/>
				</svg>
				<input type="email" placeholder="you@example.com" id="email" name="email" autocomplete="email" required aria-describedby="mc-connect-email-help">
				<button type="button" class="mc-connect-clear-email" aria-label="Clear email address" hidden>
					<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path d="m9 1-8 8M1 1l8 8"/></svg>
				</button>
			</div>
			<p id="mc-connect-email-help" class="mc-connect-helper">Enter your email to create your MalCare account, or connect this site to your existing one.</p>
		</div>

		<div class="mc-connect-consent">
			<input type="checkbox" id="mc-connect-consent" name="consent" value="1" required>
			<label for="mc-connect-consent">
				I agree to MalCare's
				<a class="mc-connect-link" href="<?php echo esc_url($mc_connect_terms_url); ?>" target="_blank" rel="noopener noreferrer">Terms of Service</a>
				and
				<a class="mc-connect-link" href="<?php echo esc_url($mc_connect_privacy_url); ?>" target="_blank" rel="noopener noreferrer">Privacy Policy</a>.
			</label>
		</div>

		<div class="mc-connect-actions">
			<button id="get-started" type="submit" class="mc-connect-primary-button">
				<svg viewBox="0 0 16 14" fill="none" stroke="currentColor" aria-hidden="true"><path d="m9.25 13 6.25-6.25L9.25.5M15.5 6.75H.5"/></svg>
				<span>Connect and Scan Site</span>
			</button>
			<button type="button" id="mc-show-connection-key-link" class="mc-connect-text-button" aria-expanded="false" aria-controls="mc-show-connection-key">Use Connection Key instead</button>
		</div>
	</form>

	<?php require dirname( __FILE__ ) . '/connection_key.php'; ?>
</section>
