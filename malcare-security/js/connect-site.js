(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		var page = document.querySelector('.mc-connect-page');
		if (!page) {
			return;
		}

		initializeConnectForm(page);
		initializeEmailField(page);
		initializeConnectionKey(page);
		initializeTestimonials(page);
	});

	function initializeConnectForm(page) {
		var form = page.querySelector('.mc-connect-form');
		var submitButton = page.querySelector('#get-started');
		if (!form || !submitButton) {
			return;
		}

		form.addEventListener('submit', function() {
			submitButton.disabled = true;
		});
	}

	function initializeEmailField(page) {
		var emailField = page.querySelector('#email');
		var clearButton = page.querySelector('.mc-connect-clear-email');
		if (!emailField || !clearButton) {
			return;
		}

		var updateClearButton = function() {
			clearButton.hidden = emailField.value.length === 0;
		};

		emailField.addEventListener('input', updateClearButton);
		clearButton.addEventListener('click', function() {
			emailField.value = '';
			updateClearButton();
			emailField.focus();
		});
		updateClearButton();
	}

	function initializeConnectionKey(page) {
		var panel = page.querySelector('#mc-show-connection-key');
		var showButton = page.querySelector('#mc-show-connection-key-link');
		var hideButton = page.querySelector('#mc-hide-connection-key');
		var keyField = page.querySelector('#mc-connection-key');
		var viewButton = page.querySelector('#mc-view-connection-key');
		var copyButton = page.querySelector('#mc-copy-connection-key');
		var status = page.querySelector('#mc-connection-key-status');

		if (!panel || !showButton || !hideButton || !keyField || !viewButton || !copyButton || !status) {
			return;
		}

		showButton.addEventListener('click', function() {
			panel.hidden = false;
			showButton.hidden = true;
			showButton.setAttribute('aria-expanded', 'true');
			keyField.focus();
		});

		hideButton.addEventListener('click', function() {
			panel.hidden = true;
			showButton.hidden = false;
			showButton.setAttribute('aria-expanded', 'false');
			status.textContent = '';
			showButton.focus();
		});

		viewButton.addEventListener('click', function() {
			var revealKey = keyField.type === 'password';
			keyField.type = revealKey ? 'text' : 'password';
			viewButton.textContent = revealKey ? 'Hide Key' : 'View Key';
			viewButton.setAttribute('aria-pressed', revealKey ? 'true' : 'false');
		});

		copyButton.addEventListener('click', function() {
			var previousType = keyField.type;
			keyField.type = 'text';

			var restoreField = function() {
				keyField.type = previousType;
			};
			var reportSuccess = function() {
				status.textContent = 'Connection key copied.';
				copyButton.textContent = 'Copied!';
				window.setTimeout(function() {
					copyButton.textContent = 'Copy Key';
				}, 2000);
			};
			var reportFailure = function() {
				status.textContent = 'Could not copy automatically. Select the key and copy it manually.';
			};

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(keyField.value).then(function() {
					reportSuccess();
					restoreField();
				}, function() {
					reportFailure();
					restoreField();
				});
				return;
			}

			keyField.select();
			try {
				if (document.execCommand('copy')) {
					reportSuccess();
				} else {
					reportFailure();
				}
			} catch (error) {
				reportFailure();
			}
			restoreField();
		});
	}

	function initializeTestimonials(page) {
		var viewport = page.querySelector('.mc-connect-testimonial-viewport');
		var track = page.querySelector('#mc-connect-testimonial-track');
		var previousButton = page.querySelector('#mc-connect-carousel-prev');
		var nextButton = page.querySelector('#mc-connect-carousel-next');
		if (!viewport || !track || !previousButton || !nextButton) {
			return;
		}

		var cards = track.querySelectorAll('.mc-connect-testimonial');
		if (!cards.length) {
			return;
		}

		var index = 0;
		var maximumIndex = 0;

		var updateCarousel = function() {
			var styles = window.getComputedStyle(track);
			var gap = parseFloat(styles.columnGap || styles.gap) || 24;
			var cardWidth = cards[0].getBoundingClientRect().width;
			var visibleCount = Math.max(1, Math.round((viewport.clientWidth + gap) / (cardWidth + gap)));
			maximumIndex = Math.max(0, cards.length - visibleCount);
			index = Math.min(index, maximumIndex);
			track.style.transform = 'translateX(' + (-index * (cardWidth + gap)) + 'px)';
			previousButton.disabled = index === 0;
			nextButton.disabled = index === maximumIndex;
		};

		previousButton.addEventListener('click', function() {
			index = Math.max(0, index - 1);
			updateCarousel();
		});

		nextButton.addEventListener('click', function() {
			index = Math.min(maximumIndex, index + 1);
			updateCarousel();
		});

		window.addEventListener('resize', updateCarousel);
		updateCarousel();
	}
})();
