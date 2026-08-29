<?php
if (!defined('ABSPATH')) exit;
?>
<header class="mc-connect-header">
	<a class="mc-connect-logo-link" href="<?php echo esc_url($mc_admin_home_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Visit MalCare">
		<img class="mc-connect-logo" src="<?php echo esc_url($mc_admin_assets_url . '/malcare-black.svg'); ?>" alt="MalCare">
	</a>
	<nav class="mc-connect-header-links" aria-label="MalCare help">
		<a class="mc-connect-link" href="<?php echo esc_url($mc_admin_support_url); ?>" target="_blank" rel="noopener noreferrer">Contact Support</a>
		<a class="mc-connect-link" href="<?php echo esc_url($mc_admin_help_url); ?>" target="_blank" rel="noopener noreferrer">Need Help?</a>
	</nav>
</header>
