<?php
if (!defined('ABSPATH')) exit;
?>
<section id="mc-show-connection-key" class="mc-connect-key-panel" aria-labelledby="mc-connect-key-title" hidden>
	<div class="mc-connect-key-heading">
		<div>
			<h3 id="mc-connect-key-title">Connection Key</h3>
			<p>Use this key on your MalCare dashboard to connect this site.</p>
		</div>
		<button type="button" id="mc-hide-connection-key" class="mc-connect-icon-button" aria-label="Close connection key">
			<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path d="m9 1-8 8M1 1l8 8"/></svg>
		</button>
	</div>
	<div class="mc-connect-key-controls">
		<input type="password" id="mc-connection-key" name="connection_key" value="<?php echo esc_attr($this->bvinfo->getConnectionKey()); ?>" readonly aria-label="Connection key">
		<button type="button" id="mc-view-connection-key" class="mc-connect-secondary-button">View Key</button>
		<button type="button" id="mc-copy-connection-key" class="mc-connect-secondary-button">Copy Key</button>
	</div>
	<p id="mc-connection-key-status" class="mc-connect-key-status" aria-live="polite"></p>
</section>
