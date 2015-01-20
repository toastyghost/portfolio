$(function(){
	var $body = $('body'),
		$navlinks = $('.navlink'),
		active_classname = 'active',
		$old_experience = $('.work-experience.old');
	
	$('body').scrollTop('0');
	
	$navlinks.on('click', function(e) {
		var $this = $(this),
			$requested_content = $('#' + this.id.replace('-navlink', ''));
		
		e.preventDefault();
		
		$navlinks.removeClass(active_classname);
		$this.addClass(active_classname);
		
		$body.animate({
			scrollTop: $requested_content.position().top - 100
		}, 1000, 'easeInOutQuad');
	});
	
	$old_experience.filter(':first').before('<span id="collapser">&hellip;</span><br><br>');
	
	$('#collapser').on('click', function(){
		$old_experience.fadeToggle(300);
	});
	
	$('#contact-form').on('submit', function(e) {
		e.preventDefault();
		
		var $form = $(this),
			form_data = $form.serialize(),
			$contact_container = $('#contact-container');
		
	    $.ajax({
	    	url: 'http://khameleon.org/work/contact.php',
	    	type: 'POST',
	    	data: {test: 'test'},
	    	dataType: 'json',
	    	success: function(data) {
	    		alert(data);
	    	}
	    });
	    
	    return;
	    
	    $.ajax({
			url: 'http://khameleon.org/work/contact.php',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			success: function(data, textStatus, jqXHR) {
				
			}
		});
	});
});