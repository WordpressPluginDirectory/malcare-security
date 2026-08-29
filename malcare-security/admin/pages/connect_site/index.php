<?php
if (!defined('ABSPATH')) exit;

$mc_admin_assets_url = plugins_url('/../../../img/connect-site', __FILE__);
$mc_admin_home_url = 'https://www.malcare.com/?utm_source=mc_plugin_connect&utm_medium=plugin&utm_campaign=connect_site';
$mc_admin_support_url = rtrim($this->bvinfo->appUrl(), '/') . '/contact/?src=plugin-connect';
$mc_admin_help_url = 'https://wordpress.org/support/plugin/malcare-security/';
$mc_admin_title_id = 'mc-connect-title';
$mc_admin_hero_subtitle = 'Connect to MalCare to get deep malware detection, one-click cleanup, and a firewall—all in one place.';
$mc_connect_terms_url = 'https://www.malcare.com/tos';
$mc_connect_privacy_url = 'https://www.malcare.com/privacy';
$mc_connect_attack_story_url = 'https://www.malcare.com/blog/tagdiv-4-1-xss-vulnerability/?utm_source=mc_plugin_connect&utm_medium=plugin&utm_campaign=connect_site';
?>
<div class="mc-connect-page">
	<?php require dirname( __FILE__ ) . '/../shared/header.php'; ?>

	<main class="mc-connect-main">
		<section class="mc-connect-hero" aria-labelledby="<?php echo esc_attr($mc_admin_title_id); ?>">
			<?php require dirname( __FILE__ ) . '/../shared/hero.php'; ?>
			<?php require dirname( __FILE__ ) . '/connect_form.php'; ?>
			<?php require dirname( __FILE__ ) . '/feature_cards.php'; ?>
			<?php require dirname( __FILE__ ) . '/network_proof.php'; ?>
		</section>

		<?php require dirname( __FILE__ ) . '/../shared/trusted_brands.php'; ?>
		<?php require dirname( __FILE__ ) . '/../shared/testimonials.php'; ?>
	</main>

	<?php require dirname( __FILE__ ) . '/../shared/footer.php'; ?>
</div>
