(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		var page = document.querySelector('.mc-accounts-page');
		if (!page) {
			return;
		}

		var disconnectForms = page.querySelectorAll('.mc-accounts-disconnect-form');
		for (var index = 0; index < disconnectForms.length; index += 1) {
			disconnectForms[index].addEventListener('submit', confirmDisconnect);
		}
	});

	function confirmDisconnect(event) {
		var form = event.currentTarget;
		var message = form.getAttribute('data-confirm-message') || 'Disconnect this MalCare account?';
		if (!window.confirm(message)) {
			event.preventDefault();
			return;
		}

		var button = form.querySelector('button[type="submit"]');
		if (button) {
			button.disabled = true;
			var label = button.querySelector('span');
			if (label) {
				label.textContent = 'Disconnecting…';
			}
		}
	}
})();
