<?php
if (!defined('ABSPATH')) exit;

$mc_admin_assets_url = plugins_url('/../../../img/connect-site', __FILE__);
$mc_admin_home_url = 'https://www.malcare.com/?utm_source=mc_plugin_accounts&utm_medium=plugin&utm_campaign=connected_accounts';
$mc_admin_support_url = rtrim($this->bvinfo->appUrl(), '/') . '/contact/?src=plugin-accounts';
$mc_admin_help_url = 'https://wordpress.org/support/plugin/malcare-security/';
$mc_admin_title_id = 'mc-accounts-title';
$mc_admin_hero_subtitle = 'Manage the MalCare accounts connected to this website, or connect another one to keep every site protected.';
$mc_connected_accounts = MCAccount::accountsByPlugname($this->settings);
$mc_accounts_dashboard_url = $this->bvinfo->appUrl();
$mc_accounts_connect_url = $this->mainUrl('&add_account=true');
?>
<div class="mc-connect-page mc-accounts-page">
	<?php require dirname( __FILE__ ) . '/../shared/header.php'; ?>

	<main class="mc-connect-main">
		<section class="mc-connect-hero" aria-labelledby="<?php echo esc_attr($mc_admin_title_id); ?>">
			<?php require dirname( __FILE__ ) . '/../shared/hero.php'; ?>
			<?php require dirname( __FILE__ ) . '/accounts_card.php'; ?>
		</section>

		<?php require dirname( __FILE__ ) . '/../shared/trusted_brands.php'; ?>
		<?php require dirname( __FILE__ ) . '/../shared/testimonials.php'; ?>
	</main>

	<?php require dirname( __FILE__ ) . '/../shared/footer.php'; ?>
</div>
