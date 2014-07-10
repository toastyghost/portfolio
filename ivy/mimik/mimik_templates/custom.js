var in_dur=300;
var out_dur=1800;
var easing='swing';
var circ='easeInOutCirc';
var original_color = '#dfdfdf';
var active_color = '#ffffff';
var showcase_is_moving = false;
var is_ie = $.browser.msie;
var url = location.href;
var base_url = url.substring(0,url.indexOf('/',14)+1);
var filters = {'service':'','organization_type':'','industry':''}
var subtitles = {'about':-54,'services':-19,'portfolio':-9,'contact':-32};
var portfolio_is_moving = false;

$(document).ready(function(){
	if($.browser.safari){
		$('#header_inner ul li a').css({'font-size':'18px','font-variant':'normal'}).find('.bg span').each(function(){
			$this = $(this);
			$this.text($this.text().toUpperCase());
			if($this.attr('class')=='link_subtitle') $this.css({'margin-top':'1px','font-size':'10px','word-spacing':'.5px'});
		});
	}
	if(window.name != window.location.href && window.location.href != document.referrer && document.referrer != ''){
		if(get_section(window.location.href) != get_section(document.referrer)) menu_fade(true);
		if(!is_ie) content_fade(true);
		window.name = window.location.href;
	}
	//fix_background_gap();
	for(i in subtitles) $('#'+i+'_link .link_subtitle').css('margin-left',subtitles[i]-25);
	if(is_ie){
		$('.nav_hover').hide();
		if(url==base_url) page = 'home';
		else page = get_section(url);
		$('#mimik img').hide();
		$('#mimik_logo_'+page_bg_colors[page]['name']).show();
	}
	$('#header_inner').css('background-color',$('body').css('background-color'));
	$primary_nav_links = $('#header_inner a[id*="_link"]');
	$primary_nav_list_items = $primary_nav_links.parent();
	$primary_nav_links.hover(function(e){
		$this = $(this);
		$this.stop().animate({'color':'#ffffff'},300,'swing');
		if(is_ie) $('#'+$this.attr('id').replace('link','hover')).show();
		else $('#'+$this.attr('id').replace('link','hover')).stop().animate({opacity:1},in_dur,easing);
		if($this.attr('id')!='home_link')
			$this.find('.link_subtitle').stop().animate({'margin-left':subtitles[$this.attr('id').replace('_link','')]+25,'opacity':.7},1100);
	},function(){
		$this = $(this);
		if($this.parent('li').attr('class')!=undefined){
			var ioa = $this.parent('li').attr('class').indexOf('active');
			if(ioa===-1) $this.stop().animate({'color':'#dfdfdf'},1800,easing);
		}
		if(is_ie) $('#'+$this.attr('id').replace('link','hover')).hide();
		else $('#'+$this.attr('id').replace('link','hover')).stop().animate({opacity:0},out_dur,easing);
		if($this.attr('id')!='home_link'){
			anchor_location = subtitles[$this.attr('id').replace('_link','')];
			$this.find('.link_subtitle').stop().animate({'margin-left':anchor_location+50,'opacity':0},function(){
				$(this).css('margin-left',anchor_location-25);
			});
		}
	});
	$primary_nav_links.click(function(e){
		e.preventDefault();
		$this = $(this);
		$this_href = $this.attr('href');
		if(qualify($this_href) != window.location.href){
			$this.parent().parent().children('li').removeClass('active');
			$this.parent('li').addClass('active');
			color_index = $this.attr('id').replace('_link','');
			$('body,#header_inner').stop().animate({'background-color':page_bg_colors[color_index]['code']},{
				queue:false,
				duration:1200,
				easing:easing,
				complete:function(){window.location.href = $this_href}
			});
			new_mimi_selector = '#mimik_logo_'+page_bg_colors[color_index]['name'];
			$new_mimi = $(new_mimi_selector)
			if(!is_ie){
				$('#mimik img:not('+new_mimi_selector+')').stop().animate({opacity:0},350,easing);
				$new_mimi.stop().animate({opacity:1},350,easing);
			}else $('#mimik img').hide();
			if($this.attr('id').replace('_link','') != get_section(window.location.href)) menu_fade();
			else $highlight.stop().animate({width:0,left:$('.secondary_nav').width()+25});
			if(!is_ie) content_fade();
		}
	});
	var $highlight = $('.secondary_nav #highlight');
	$('.secondary_nav li a').click(function(e){
		e.preventDefault();
		$this = $(this);
		$('.secondary_nav li a').css('color','#dfdfdf');
		$this.css('color','#fff');
		$highlight.stop().animate({
				left:$this.position().left,
				width:$this.width()+25
			},350,easing,function(){if($this.attr('href') != '') window.location.href = $this.attr('href')}
		);
		if(!is_ie) content_fade();
	});
	var $active = $('.secondary_nav li.active a');
	if($active.length!=0){
		$active.unbind('click').click(function(e){e.preventDefault()}).css('cursor','default');
		$highlight.css({
			width:$active.width()+25,
			left:$active.position().left
		});
	}
	if(get_section(window.location.href)=='portfolio') $('#page_body').css('padding-bottom',0);
	$portfolio_filter_visible = $('#portfolio_filter_visible');
	if($portfolio_filter_visible.length>0){
		$portfolio_filter_visible.click(function(e){
			$target = $(e.target);
			$filter_options = $('#filter_options');
			if($target.hasClass('selected')){
				$target.removeClass('selected');
				$filter_options.css('border-bottom','none').children('ul').stop().fadeOut(400,function(){
					$filter_options.stop().animate({'height':0},800,'swing');
				});
			}else{
				$filter_selected = $('#portfolio_filter_visible>li>a.selected');
				if($filter_selected.length>0){
					$filter_selected.removeClass('selected');
					$('#filter_options>ul').stop().fadeOut(400);
				}
				$list_to_show = $('#filter_options>#'+$target.attr('id'));
				if($list_to_show.height()>334){
					height_modifier = -15;
					border_bottom = '15px solid #aba68c';
				}else{
					height_modifier = 0;
					border_bottom = 0;
				}
				new_height = Math.min($list_to_show.height(),334)+height_modifier;
				$filter_options.stop().animate({'height':new_height,'border-bottom':border_bottom},800,'swing',function(){
					$list_to_show.stop().fadeIn(400);
					if(height_modifier!=0) $filter_options[0].style.overflowY = 'scroll';
					else{
						$filter_options[0].style.overflowY = 'hidden';
						$filter_options[0].scrollTop = 0;
					}
				});
				$target.addClass('selected');
			}
		});
	}
	if(is_ie){
		$('#filter_options a').hover(function(){
			$(this).css('background-color','#c2bdaa');
		},function(){
			$(this).css('background-color','transparent');
		});
	}
	$('#filter_options').click(function(e){
		$target = $(e.target);
		target_id = $target.attr('id');
		target_class = $target.attr('class');
		if(target_class.indexOf('filter_link')!==-1){
			idx = target_class.replace(' filter_link','');
			filters[idx] = target_id.replace(idx+'_','');
			position = parseInt($('.multimulti:first').css('left'))/-198;
			$('#portfolio_filter_visible #'+idx).click();
			refresh_thumbnails(filters,position);
			t = setTimeout("$('#filter_options #'+idx+' .filter_link.selected').removeClass('selected');$('#'+target_id).addClass('selected');",350);
		}
	});
	delegate_thumbnail_click();
	$.ajaxSetup({cache:false});
	$('#thumbnail_container').ajaxComplete(function(){delegate_thumbnail_click()});
	$clear_filters_link = $('#portfolio_sidebar #clear_filters');
	$clear_filters_link.click(function(){
		filters['service'] = filters['organization_type'] = filters['industry'] = '';
		$('#thumbnail_container').fadeOut(350,function(){
			$(this).load('/site_includes/portfolio_list.php',function(){$(this).fadeIn(350)});
		});
		$('#portfolio_filter_visible .category_link.selected').click();
		t = setTimeout("$('#filter_options .filter_link.selected').removeClass('selected');",350);
	});
	if(is_ie){
		$shadow_inner = $('#portfolio_sidebar #shadow_inner');
		$clear_filters_link.css({'top':$shadow_inner.height()-$clear_filters_link.height()-35+'px',
								'right':$clear_filters_link.width()+10});
	}
	if($('#portfolio_sidebar').length>0){
		$('#portfolio_sidebar .side_gradient').hover(function(){
			$this = $(this);
			if(direction_allowed($this)){
				this_id_selector = '#'+$(this).attr('id');
				$hovers = $(this_id_selector+'_hover,'+this_id_selector.replace('gradient','hover'));
				if(!is_ie) $hovers.stop().animate({opacity:1});
				else $hovers.show();
			}
		},function(){
			this_id_selector = '#'+$(this).attr('id');
			$hovers = $(this_id_selector+'_hover,'+this_id_selector.replace('gradient','hover'))
			if(!is_ie) $hovers.stop().animate({opacity:0});
			else $hovers.hide();
		}).click(function(e){
			$target = $(e.target);
			if(direction_allowed($target)){
				portfolio_is_moving = true;
				$portfolio_thumbnail_list.css('position','absolute').stop().animate({left:parseInt($portfolio_thumbnail_list.css('left'))+198*dir},600,'swing',function(){portfolio_is_moving = false});
			}
		});
	}
});

$(window).load(function(){
	header_inner_left = $('#header_inner').offset().left;
	$('.nav_hover').each(function(){
		$this = $(this);
		$corresponding_link = $('#'+$this.attr('id').replace('hover','link'));
		link_width_offset = $corresponding_link.width()/2;
		glow_width_offset = $this.width()/2;
		if(link_width_offset == 0 || $this.attr('id')=='home_hover') new_left = $this.parent().width()/2;
		else new_left = $corresponding_link.offset().left-header_inner_left+link_width_offset-glow_width_offset;
		$this.css('left',new_left);
	});
	$showcase = $('#showcase');
	if($showcase.length!=0){
		$('#photos').width(550*$('#photos').children().length);
		$highlight_bracket = $('#showcase #navigation #highlight_bracket');
		$first_thumb = $('#thumbs > li:first > a > img').offset();
		$nav = $('#showcase #navigation').offset();
		$highlight_bracket.css({'top':$first_thumb.top-$nav.top-4,
								'left':$first_thumb.left-$nav.left-4})
						   .stop().animate({opacity:1},700,easing);
	}
	if($('#showcase')!==undefined){
		$('#showcase #navigation .arrow').hover(function(){
			$hovered_arrow = $(this);
			if(is_ie) $hovered_arrow.children('img').show();
			else{
				$current_thumb = $('#'+$('#photo_frame #photos li div.current').attr('id').replace('photo_','thumb_'));
				if(($hovered_arrow.attr('id')=='left_arrow' && ($highlight_bracket.position().left!=45 || $thumbs.position().left<0)) || ($hovered_arrow.attr('id')=='right_arrow' && ($current_thumb.offset().left-$('#thumbnail_frame').offset().left!==384 || parseInt($current_thumb.attr('id').replace('thumb_','')>=$('#thumbs').children('li').length)))) // TODO: fix broken right arrow hover highlight logic
					$hovered_arrow.children('img').stop().animate({'opacity':1},150,easing);
			}
		},function(){
			if(is_ie) $(this).children('img').hide();
			else $(this).children('img').stop().animate({'opacity':0},750,easing);
		});
		$('#showcase #navigation .arrow').click(function(){
			if(!showcase_is_moving){
				showcase_is_moving = true;
				direction = ($(this).attr('id')=='left_arrow')?1:-1;
				$thumbs = $('#thumbs');
				$photo_list = $('#photo_frame ul');
				$current = $('#photo_frame #photos li div.current');
				current_id = $current.attr('id');
				$current_thumb = $('#'+current_id.replace('photo','thumb'));
				if($(this).attr('id')=='left_arrow'){
					index_change = -1;
					if($highlight_bracket.position().left==45){
						if($thumbs.position().left<0){
							$element_to_move = $thumbs;
							nav_direction = 1;
							view_direction = 1;
						}else{
							$element_to_move = false;
							nav_direction = null;
							view_direction = null;
						}
					}else{
						$element_to_move = $highlight_bracket;
						nav_direction = -1;
						view_direction = 1;
					}
				}else{
					if($(this).attr('id')=='right_arrow'){
						index_change = 1;
						if($current_thumb.offset().left-$('#thumbnail_frame').offset().left===384){
							if(parseInt(current_id.replace('photo_',''))<$thumbs.children('li').length){
								$element_to_move = $thumbs;
								nav_direction = -1;
								view_direction = -1;
							}else{
								$element_to_move = false;
								nav_direction = null;
								view_direction = null;
							}
						}else{
							$element_to_move = $highlight_bracket;
							nav_direction = 1;
							view_direction = -1;
						}
					}
				}
				if($element_to_move !== false && nav_direction !== null && view_direction !== null){
					$element_to_move.stop().animate({'left':$element_to_move.position().left+nav_direction*64},500,circ);
					$photo_list.stop().animate({'margin-left':parseInt($photo_list.css('margin-left').replace('px',''))+(view_direction*550)},650,circ,function(){
						$current.removeClass('current');
						$new_current = $('#photo_'+(parseInt(current_id.replace('photo_',''))+index_change));
						$new_current.addClass('current');
						showcase_is_moving = false;
					});
				}else showcase_is_moving = false;
			}
		});
		/*$('#showcase #navigation .arrow').click(function(){
			if (!showcase_is_moving) {
				showcase_is_moving = true;
				direction = ($(this).attr('id')=='left_arrow') ? 1 : -1;
				$thumbs = $('#thumbs');
				$photo_list = $('#photo_frame ul');
				$current_id = $('#photo_frame #photos li div.current').attr('id');
				$current_num = parseInt($current_id.replace('photo_', ''));
				$clicked_num = $current_num;
				if(direction == 1 && $current_num != 1)
					$clicked_num = $current_num - 1;
				else{
					if(direction == -1 && $current_num != $thumbs.children().length)
						$clicked_num = $current_num + 1;
				}
				if($clicked_num != $current_num){
					//new_margin = (parseInt($thumbs.css('margin-left').replace('px',''))+direction*64)+'px';
					//$thumbs.stop().animate({'margin-left':new_margin},500,circ);
					
					//$thumbs.stop().animate({'left':$thumbs.position().left+direction*64},500,circ);
					
					$clicked_thumb = $('#thumb_'+$clicked_num).offset();
					$('#highlight_bracket').stop().animate({'left':$clicked_thumb.left-$nav.left-4},500,circ);
					$photo_list.stop().animate({'margin-left':parseInt($photo_list.css('margin-left').replace('px',''))+direction*550},650,circ,function(){
						$('#photo_' + $clicked_num).addClass('current');
						$('#photo_' + $current_num).removeClass('current');
						showcase_is_moving = false;
					});
				}else showcase_is_moving = false;
			}
		});*/
		$('#thumbs>li>a').click(function(e){
			if(!showcase_is_moving){
				$this = $(this);
				showcase_is_moving = true;
				$clicked_thumb = $this.children('img').offset();
				$highlight_bracket.stop().animate({
					'top':$clicked_thumb.top-$nav.top-4,
					'left':$clicked_thumb.left-$nav.left-4
				},500,circ);
				$clicked = $('#'+$this.attr('id').replace('thumb_','photo_'));
				$current = $('#photo_frame #photos li div.current');
				clicked_num = parseInt($this.attr('id').replace('thumb_',''));
				current_num = parseInt($current.attr('id').replace('photo_',''));
				direction = (clicked_num > current_num) ? -1 : 1;
				$photo_list = $('#photo_frame ul');
				$photo_list.stop().animate({'margin-left':parseInt($photo_list.css('margin-left').replace('px',''))+(direction*550*Math.abs(current_num-clicked_num))},650,circ,function(){
					$current.removeClass('current');
					$clicked.addClass('current');
					showcase_is_moving = false;
				});
			}
		});
		var i = 0;
		$('.portfolio_item_image').each(function(){
			$this = $(this);
			//if($this.parent().css('display')=='none') $this.children('.portfolio_item_image').attr('src',$this.next('.lazy_loader').text());
			if($this.parent().css('display')=='none') i+=1;
		});
		alert(i);
	}
});

/*	TODO: determine if we can get rid of this (and its plethora of issues) by changing
	the background gradient to a solid color or using a full-body background gradient
	that goes between the main bg color and a shade lighter (using colorschemer to
	make sure the fade colors are uniform). this is an art decision, but would make
	coding a lot easier, and we're tweaking the colors anyway. */
function fix_background_gap(){
	/*$gradient = $('#gradient');
	$footer = $('#footer');
	if ($footer.offset() !== null) {
		$('#gradient_back').css('height',($footer.offset().top+$footer.outerHeight())-($gradient.offset().top+$gradient.outerHeight())+45);
	}*/
	return null;
}
function menu_fade(show){
	if(show==null) show = false;
	$secondary_nav = $('.secondary_nav');
	if(show){
		finish_pos = $secondary_nav.css('margin-left');
		new_opacity = 1;
		$secondary_nav.css({'margin-left':-100,opacity:0});
	}else{
		finish_pos = 100;
		new_opacity = 0;
	}
	$secondary_nav.stop().animate({'margin-left':finish_pos,opacity:new_opacity},350,circ);
}
function content_fade(show){
	if(show==null) show = false;
	$content_shadow_left = $('#content_shadow_left');
	if(show){
		finish_pos = parseInt($content_shadow_left.css('margin-left'))+14+'px';
		new_opacity = 1;
		$content_shadow_left.css({'margin-left':80,opacity:0});
	}else{
		finish_pos = -80;
		new_opacity = 0;
	}
	$content_shadow_left.css({'position':'relative','z-index':-1})
	.stop().animate({'margin-left':finish_pos,opacity:new_opacity},450,easing,function(){
		$content_shadow_left.css('position','static');
	});
}
function get_section(url){
	path_array = url.split('/');
	section = path_array[3];
	if(section.indexOf('#')!=-1) section = section.substring(0,section.indexOf('#'));
	if(section.indexOf('?')!=-1) section = section.substring(0,section.indexOf('?'));
	return section;
}
function qualify(url){
	var img = document.createElement('img');
	img.src = url;
	url = img.src;
	img.src = null;
	return url;
}
function remove_whitespace(str){
	return str.replace(/^\s*|\s*$/g,'');
}
function refresh_thumbnails(local_filters,local_position){
	$('#thumbnail_container').fadeOut(350,function(){
		$(this).load('/site_includes/portfolio_list.php',{filters:local_filters,position:local_position,random:Math.floor(Math.random()*101)},function(){
			$(this).fadeIn(350);
		});
	});
}
function direction_allowed($obj){
	if(portfolio_is_moving) return false;
	else{
		$portfolio_thumbnail_list = $('.portfolio_thumbnail_list');
		list_relative_left = $portfolio_thumbnail_list.position().left;
		if($this.attr('id').indexOf('left')!==-1) dir = 1;
		else dir = -1;
		if((($obj.attr('id')=='arrow_left_gradient' || $obj.attr('id')=='arrow_left_link') && list_relative_left<0)
		|| (($obj.attr('id')=='arrow_right_gradient' || $obj.attr('id')=='arrow_right_link') && list_relative_left>dir*198*($('.multimulti').length-1)))
			return true;
		else return false;
	}
}
function delegate_thumbnail_click(){
	$('.showcase_thumbnail_item').delegate('a','click',function(){
		var id = $(this).parent().attr('id').substr(19);
		$('.showcase_item:not(#showcase_item_' + id + ')').fadeOut(450,function(){$('#showcase_item_' + id).fadeIn(450)});
	})/*.delegate('a','mouseover mouseout',function(e){
		if(e.type=='mouseover'){
			$(this).next('em').stop(true,true).animate({opacity:'show'},'slow');
		}else{
			$(this).next('em').stop(true,true).animate({opacity:'hide'},'fast');
		}
	})*/;
}