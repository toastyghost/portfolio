in_dur=300;
out_dur=1800;
easing='swing';
circ='easeInOutCirc';
primary_nav_original_color = '#dfdfdf';
primary_nav_highlight_color = '#fff';
is_ie = $.browser.msie;

$(document).ready(function(){
	$primary_nav_links = $('#header_inner a[id*="_link"]');
	$primary_nav_list_items = $primary_nav_links.parent();
	$primary_nav_links.data('current_color',primary_nav_original_color);
	$primary_nav_links.hover(function(){
		if($(this).css('color')!=primary_nav_highlight_color)
			$(this).animate({color:primary_nav_highlight_color},{queue:false,duration:in_dur,easing:easing});
		$('#'+$(this).attr('id').replace('link','hover')).stop().animate({opacity:1},{queue:false,duration:in_dur,easing:easing});
	},function(){
		if($(this).css('color')!=primary_nav_original_color && $(this).attr('class').indexOf('active')===-1)
			$(this).animate({color:$(this).data('current_color')},{queue:false,duration:out_dur,easing:easing});
		$('#'+$(this).attr('id').replace('link','hover')).stop().animate({opacity:0},{queue:false,duration:out_dur,easing:easing});
	});
	$primary_nav_links.click(function(){
		$('#primary_nav li a:not(#'+$(this).attr('id')+')').removeClass('active').data('current_color',primary_nav_original_color);
		$(this).addClass('active').data('current_color',primary_nav_highlight_color);
		$('body,#header_inner').stop().animate({'background-color':page_bg_colors[$(this).attr('id').replace('_link','')]},{queue:false,duration:3000,easing:easing});
		$content_shadow_left = $('#content_shadow_left');
		$content_shadow_left.stop().animate({'margin-left':'-80px','opacity':0},250,easing,function(){
			$(this).css('margin-left','96px');
			$('#content_wrapper').load('test.html',function(){
				$content_shadow_left.animate({'margin-left':'12px','opacity':1},350,easing,fix_background_gap());
			});
		});
	});
	$('#showcase #navigation .arrow').hover(function(){
		$(this).children('img').stop().animate({'opacity':1},150,easing);
	},function(){
		$(this).children('img').stop().animate({'opacity':0},750,easing);
	});
	$('#showcase #navigation .arrow').click(function(){
		direction = ($(this).attr('id')=='left_arrow') ? 1 : -1;
		$thumbs = $('#thumbs');
		new_margin = (parseInt($thumbs.css('margin-left').replace('px',''))+(direction*64))+'px';
		$thumbs.animate({'margin-left':new_margin},500,circ);
		$photo_list = $('#photo_frame ul');
		$photo_list.stop().animate({'margin-left':parseInt($photo_list.css('margin-left').replace('px',''))+direction*550},650,circ,function(){
			$current = $('#photo_frame #photos li div.current');
			$current.attr('class',$current.attr('class').replace(' current',''));
			$clicked.attr('class',$clicked.attr('class')+' current');
		});
	});
	$('#thumbs > li > a').click(function(){
		$clicked_thumb = $(this).children('img').offset();
		$('#highlight_bracket').stop().animate({'top':$clicked_thumb.top-$nav.top-4,
										 		'left':$clicked_thumb.left-$nav.left-4},500,circ);
		$clicked = $('#'+$(this).attr('id').replace('thumb','photo'));
		$current = $('#photo_frame #photos li div.current');
		clicked_num = parseInt($(this).attr('id').replace('thumb',''));
		current_num = parseInt($current.attr('id').replace('photo',''));
		direction = (clicked_num > current_num) ? -1 : 1;
		$photo_list = $('#photo_frame ul');
		$photo_list.stop().animate({'margin-left':parseInt($photo_list.css('margin-left').replace('px',''))+(direction*550*Math.abs(current_num-clicked_num))},650,circ,function(){
			$current.removeClass('current');
			$clicked.addClass('current');
		});
	});
});

$(window).load(function(){
	header_inner_left = $('#header_inner').offset().left;
	$('.nav_hover').each(function(){
		$corresponding_link = $('#'+$(this).attr('id').replace('hover','link'));
		link_width_offset = parseInt($corresponding_link.css('width').replace('px',''))/2;
		glow_width_offset = parseInt($(this).css('width').replace('px',''))/2;
		new_left = $corresponding_link.offset().left-header_inner_left+link_width_offset-glow_width_offset;
		$(this).css('left',new_left);
	});
	$bracket = $('#showcase #navigation #highlight_bracket');
	$first_thumb = $('#thumbs > li:first > a > img').offset();
	$nav = $('#showcase #navigation').offset();
	$('#highlight_bracket').css({'top':$first_thumb.top-$nav.top-4,
								 'left':$first_thumb.left-$nav.left-4})
						   .animate({opacity:1},700,easing);
	fix_background_gap();
});

function fix_background_gap(){
	$gradient = $('#gradient');
	$footer = $('#footer');
	$('#gradient_back').css('height',($footer.offset().top+$footer.height())-($gradient.offset().top+$gradient.height()));
}