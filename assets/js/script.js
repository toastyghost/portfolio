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

		var $contact_form = $('#contact-form'),
			$contact_failure_message = $('#contact-failure-message');
		
	    $.ajax({
			url: 'contact.php',
			type: 'POST',
			data: $contact_form.serialize(),
			dataType: 'json',
			success: function(data, textStatus, jqXHR) {
				if (data == 1) {
					$contact_form.add($contact_failure_message).hide();
					$('#contact-success-message').show();
				} else {
					$contact_failure_message.show();
				}
			}
		});
	});
});