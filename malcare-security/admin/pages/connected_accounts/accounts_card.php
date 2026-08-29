<?php
if (!defined('ABSPATH')) exit;
$mc_disconnect_nonce = wp_create_nonce('bvnonce');
?>
<section class="mc-accounts-card" aria-labelledby="mc-accounts-card-title">
	<div class="mc-accounts-card-header">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
			<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
			<circle cx="9" cy="7" r="4"/>
			<path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
		</svg>
		<h2 id="mc-accounts-card-title">Accounts associated with this website</h2>
	</div>

	<div class="mc-accounts-table-wrap">
		<?php if (!empty($mc_connected_accounts)) : ?>
			<div class="mc-accounts-table-scroll">
				<table class="mc-accounts-table">
					<thead>
						<tr>
							<th scope="col">Account Email</th>
							<th scope="col">Last Synced</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($mc_connected_accounts as $mc_account_pubkey => $mc_account_details) : ?>
							<?php
							$mc_account_email = isset($mc_account_details['email']) ? $mc_account_details['email'] : 'Account connected';
							$mc_account_sync_time = isset($mc_account_details['lastbackuptime']) ? $this->formatAccountSyncTime($mc_account_details['lastbackuptime']) : 'Not synced yet';
							?>
							<tr>
								<td class="mc-accounts-email"><?php echo esc_html($mc_account_email); ?></td>
								<td class="mc-accounts-synced"><?php echo esc_html($mc_account_sync_time); ?></td>
								<td class="mc-accounts-action">
									<form class="mc-accounts-disconnect-form" action="<?php echo esc_url($this->mainUrl('&account_details=true')); ?>" method="post" data-confirm-message="Disconnect this MalCare account from the website?">
										<input type="hidden" name="bvnonce" value="<?php echo esc_attr($mc_disconnect_nonce); ?>">
										<input type="hidden" name="pubkey" value="<?php echo esc_attr($mc_account_pubkey); ?>">
										<button type="submit" class="mc-accounts-disconnect-button">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
												<path d="M18.84 12.25l1.72-1.71a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
												<path d="M5.17 11.75l-1.71 1.71a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
												<line x1="8" y1="2" x2="8" y2="5"/>
												<line x1="2" y1="8" x2="5" y2="8"/>
												<line x1="16" y1="19" x2="16" y2="22"/>
												<line x1="19" y1="16" x2="22" y2="16"/>
											</svg>
											<span>Disconnect</span>
										</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else : ?>
			<div class="mc-accounts-empty" role="status">
				<strong>No MalCare accounts are connected.</strong>
				<span>Connect an account to manage this website with MalCare.</span>
			</div>
		<?php endif; ?>

		<div class="mc-accounts-buttons">
			<?php if (!empty($mc_connected_accounts)) : ?>
				<a class="mc-accounts-dashboard-button" href="<?php echo esc_url($mc_accounts_dashboard_url); ?>" target="_blank" rel="noopener noreferrer">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
						<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
						<rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
					</svg>
					Visit Dashboard
				</a>
			<?php endif; ?>
			<a class="mc-accounts-connect-button" href="<?php echo esc_url($mc_accounts_connect_url); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
				Connect New Account
			</a>
		</div>
	</div>
</section>
