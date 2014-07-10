var find_it_text = "FIND IT...";
var nesting_order = {0:'left',1:'right',2:'center'};
function button_hover_state($current,orientation){
	for(i=0;i<3;i++) $current = $current.css('background-position',orientation+' '+nesting_order[i]).children().first();
}
function set_footer(){
	$('#footer').css('width','100%');
	footer_min_height = $(window).height()-$('#content').offset().top-$('#footer').height()-parseInt($('#footer').css('margin-top').replace('px',''))
	$('#content:not(.find)').css('min-height',((footer_min_height<174)?174:footer_min_height)+'px');
}

$(document).ready(function(){
	$('a.utility_link').hover(function(){
		$('#'+$(this).attr('id')+'_hover').css('display','block');},function(){
		$('#'+$(this).attr('id')+'_hover').css('display','none');
	});
	$('#home_search_textbox,#find_it_search_textbox').focus(function(){
		if($(this).val()==find_it_text) $(this).val('');
	}).blur(function(){
		if($(this).val()=='') $(this).val(find_it_text);
	});
	$('#primary_nav .large_button .bookend.left').hover(function(){
		button_hover_state($(this),'bottom')},function(){
		button_hover_state($(this),'top')
	});
	if(!$.support.cssFloat){
		$('label').click(function(){
			$('#'+$(this).attr('htmlFor')).attr('checked',!($('#'+$(this).attr('htmlFor')).attr('checked')));
			$graphic = $(this).children('.graphic');
			if($graphic.css('background-position-y')=='top'){$graphic.css('background-position-y','bottom')}
			else{$graphic.css('background-position-y','top')}
		});
	}else{
		$('#search_filter .custom_checkbox input.checkbox').change(function(){
			$graphic = $(this).next().children();
			if($(this).attr('checked')){$graphic.css('background-position','center bottom')}
			else{$graphic.css('background-position','center top')}
		});
	}
	$('.select_closed').click(function(){
		clicked_id = '#'+$(this).attr('id');
		$(clicked_id.replace('closed','open')+','+clicked_id.replace('select_closed','change')).toggle();
	});
	$('.select_graphical li a').click(function(){
		parent_id = '#'+$(this).parents('.select_open').toggle().attr('id');
		$(parent_id.replace('select_open','change')).toggle();
		$(this).parent().siblings().each(function(){
			$(this).children('.active').removeClass('active');
		});
		$(parent_id.replace('open','closed')).html($(this).addClass('active').html());
		$('#'+$(this).parents('.select_open').attr('id').replace('open_','')).val(parseInt($(this).attr('id').replace('link_','')));
	});
	set_footer();
});
$(window).resize(set_footer);