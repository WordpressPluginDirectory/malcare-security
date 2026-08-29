<?php
if (!defined('ABSPATH')) exit;
?>
<div>
	<iframe title="MalCare dashboard" style="width: 100%; min-height: 100vh;" src="<?php echo esc_url($this->account->authenticatedUrl('/malcare/access'));?>"></iframe>
</div>
