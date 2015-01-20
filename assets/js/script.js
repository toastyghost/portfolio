$(function(){
	var $body = $('body'),
		$navlinks = $('.navlink'),
		active_classname = 'active',
		$old_experience = $('.work-experience.old');
	
	$navlinks.on('click', function(e) {
		var $this = $(this),
			$requested_content = $('#' + this.id.replace('-navlink', ''));
		
		e.preventDefault();
		
		$navlinks.removeClass(active_classname);
		$this.addClass(active_classname);
		
		$body.animate({
			scrollTop: $requested_content.position().top
		}, 1000, 'easeInOutQuad');
	});
	
	$old_experience.filter(':first').before('<span id="collapser">&hellip;</span><br><br>');
	
	$('#collapser').on('click', function(){
		$old_experience.fadeToggle(300);
	});
	
	$('#contact-form').on('submit', function(e) {
		e.preventDefault();

		var error_messages = [],
			form_data = $('#contact-form').serializeArray(),
			$contact_failure_message = $('#contact-failure-message'),
			$contact_text = $('#contact-text');

		if (form_data.length > 0) {
			if (form_data[0].value == '') {
				error_messages.push('You must enter your name.');
			}

			if (form_data[1].value == '' || !isValidEmail(form_data[1].value)) {
				error_messages.push('You must enter a valid email address.');
			}

			if (form_data[5].value == '') {
				error_messages.push('You must enter some details about your project.');
			}
		} else {
			error_messages.push('You must fill out the form before submitting.');
		}
		
		if (error_messages.length === 0) {
		    $.ajax({
				url: 'contact.php',
				type: 'POST',
				data: form_data,
				dataType: 'json',
				success: function(data, textStatus, jqXHR) {
					if (data == 1) {
						$contact_form.add($(
							$contact_text,
							$contact_failure_message
						)).fadeOut();
						$('#contact-success-message').show();
					} else {
						error_messages.push('Inquiry could not be submitted. If this problem persists, please <a href="mailto:toastyghost@live.com">email</a> me your project details.');
					}
				}
			});
		}

		if (error_messages.length > 0) {
			$contact_failure_message[0].innerHTML = '';

			var key = 0;
			while (key < error_messages.length) {
				$contact_failure_message[0].innerHTML += error_messages[key] + '<br>';
				++key;
			}

			$contact_failure_message.show();
		}
	});
});

function isValidEmail(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};