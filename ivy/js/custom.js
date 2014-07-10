var in_dur=300,
	out_dur=1800,
	fade_dur = 450,
	easing='swing',
	circ='easeInOutCirc',
	original_color = '#dfdfdf',
	active_color = '#ffffff',
	showcase_is_moving = false,
	content_is_moving = false,
	portfolio_is_moving = false,
	is_ie = ($.browser.msie && $.browser.version!=='9.0'),
	url = location.href,
	base_url = url.substring(0,url.indexOf('/',14)+1),
	filters = {'service':'','organization_type':'','industry':''},
	subtitles = {'about':-54,'services':-19,'portfolio':-9,'contact':-32},
	section = get_section(window.location.href);

$(function(){
	$.ajaxSetup({cache:false});
	$("html").addClass("js");
	/*$('#utility_nav').remove();*/
	if($.browser.safari){
		$('#header_inner ul li a').css({'font-size':'18px','font-variant':'normal'}).find('.bg span').each(function(){
			var $this = $(this);
			$this.text($this.text().toUpperCase());
			if($this.attr('class')==='link_subtitle'){$this.css({'margin-top':'1px','font-size':'10px','word-spacing':'.5px'});}
		});
	}
	if(window.name !== window.location.href && window.location.href !== document.referrer && document.referrer !== ''){
		if(section !== get_section(document.referrer)){menu_fade(true);}
		if(!is_ie){content_fade(true);}
		window.name = window.location.href;
	}
	var i;
	for(i in subtitles) $('#'+i+'_link .link_subtitle').css('margin-left',subtitles[i]-25);
	if(is_ie){
		$('.nav_hover').hide();
		if(url===base_url) page = 'home';
		else page = get_section(url);
		$('#mimik img').hide();
		$('#mimik_logo_'+page_bg_colors[page]['name']).show();
		if($.browser.version==='7.0'){
			$('#primary_nav_bottom_shadow').remove();
			var $sidebar_box_wrapper = $('.sidebar_box_wrapper');
			if($sidebar_box_wrapper.html()==='') $sidebar_box_wrapper.remove();
		}
	}
	$('#header_inner').css('background-color',$('body').css('background-color'));
	var $primary_nav_links = $('#header_inner a[id*="_link"]'),
		$active_link = $primary_nav_links.parent('.active').children('a');
	if($active_link.length===0) $active_link = $('#'+section+'_link');
	$active_link.css('color','#fff');
	var $active_hover = $('#'+$active_link.attr('id').replace('link','hover')),
		$active_link_subtitle = $active_link.find('.link_subtitle');
	$active_link_subtitle.css({opacity:1,color:'#fff',left:parseInt($active_link_subtitle.css('left'))+50});
	if(is_ie) $active_hover.show();
	else $active_hover.css('opacity',1);
	var $highlight = $('.secondary_nav #highlight');
	$primary_nav_links.click(function(e){
		e.preventDefault();
		$('.secondary_nav a').css('color','#dfdfdf');
		var $this = $(this),
			$this_href = $this.attr('href'),
			$this_href_qual = qualify($this_href),
			$corresponding_hover = $('#'+$this.attr('id').replace('link','hover')),
			$new_active_hover = $('#'+$this.attr('id').replace('link','hover')),
			url_array = window.location.href.split('/'),
			fade_out_active = false;
		if(section === $new_active_hover.attr('id').replace('_hover','')){
			if($highlight.width()>0 && section === url_array[url_array.length-1]) fade_out_active = true;
		}else fade_out_active = true;
		if(fade_out_active){
			$active_link.stop().animate({color:'#dfdfdf'},350,easing);
			$active_hover.stop().animate({opacity:0},350,easing);
			$active_link_subtitle.stop().animate({opacity:0,left:parseInt($active_link_subtitle.css('left'))+50},350,easing);
			$this.find('.link_subtitle').stop().animate({'margin-left':subtitles[$this.attr('id').replace('_link','')]+25,color:'#ffffff',opacity:1},350,easing);
		}
		$this.unbind('mouseenter mouseleave');
		if(is_ie) $corresponding_hover.show();
		else $corresponding_hover.css('opacity',1);
		if($this_href_qual != window.location.href){
			if(get_section($this_href_qual)==='portfolio' && section==='portfolio') return false;
			else{
				$this.parent().parent().children('li').removeClass('active');
				$this.parent('li').addClass('active');
				var color_index = $this.attr('id').replace('_link','');
				$('body,#header_inner').stop().animate({'background-color':page_bg_colors[color_index]['code']},{
					queue:false,
					duration:1200,
					easing:easing,
					complete:function(){window.location.href = $this_href}
				});
				var new_mimi_selector = '#mimik_logo_'+page_bg_colors[color_index]['name'];
				var $new_mimi = $(new_mimi_selector)
				if(!is_ie){
					$('#mimik img:not('+new_mimi_selector+')').stop().animate({opacity:0},350,easing);
					$new_mimi.stop().animate({opacity:1},350,easing);
				}else $('#mimik img').hide();
				if($this.attr('id').replace('_link','') !== section) menu_fade();
				else $highlight.stop().animate({width:0,left:$('.secondary_nav').width()+25});
				if(!is_ie) content_fade();
			}
		}
	}).not($active_link).hover(function(){
		var $this = $(this);
		$this.stop().animate({'color':'#ffffff'},300,'swing');
		if(is_ie) $('#'+$this.attr('id').replace('link','hover')).show();
		else $('#'+$this.attr('id').replace('link','hover')).stop().animate({opacity:1},in_dur,easing);
		if($this.attr('id')!=='home_link')
			$this.find('.link_subtitle').stop().animate({'margin-left':subtitles[$this.attr('id').replace('_link','')]+25+'px','opacity':.7},1100);
	},function(){
		var $this = $(this);
		if(!$this.parent().hasClass('active')) $this.stop().animate({'color':'#dfdfdf'},1800,easing);
		if(is_ie) $('#'+$this.attr('id').replace('link','hover')).hide();
		else $('#'+$this.attr('id').replace('link','hover')).stop().animate({opacity:0},out_dur,easing);
		if($this.attr('id')!='home_link'){
			var anchor_location = subtitles[$this.attr('id').replace('_link','')];
			$this.find('.link_subtitle').stop().animate({'margin-left':anchor_location+50+'px','opacity':0},function(){
				$(this).css('margin-left',anchor_location-25);
			});
		}
	});
	$('.secondary_nav,.tertiary_nav').find('a').click(function(e){
		e.preventDefault();
		var animate_highlight = true,
			$this = $(this),
			$corresponding_secondary_item = $('.secondary_nav>li>a[href="/'+$(this).attr('href')+'"]');
		if($this.closest('ul').hasClass('tertiary_nav')){
			if($corresponding_secondary_item.length>0) $this = $corresponding_secondary_item;
			else animate_highlight = false;
		}
		if(animate_highlight){
			$('.secondary_nav li a').css('color','#dfdfdf');
			$this.css('color','#fff');
			$highlight.stop().animate({
					left:$this.position().left,
					width:$this.width()+25
				},350,easing,function(){if($this.attr('href') != '') window.location.href = $this.attr('href')
			});
		}else window.location.href = $this.attr('href');
		if(!is_ie) content_fade();
	});
	if($('.tertiary_nav').length>0){
		var $tertiary_modules = $('.tertiary_nav>.module');
		if($tertiary_modules.length>4){
			$tertiary_modules.each(function(){
				var $module = $(this);
				if(($module.index()+1)%4 === 0) $module.css('background','none');
			});
		}
		$('.module img').css('cursor','pointer').click(function(){$(this).siblings('h3').children('a').click()});
	}
	$('#photos').find('.more').click(function(e){
		e.preventDefault();
		if(!is_ie) content_fade();
		var link_target = $(this).attr('href');
		$('body,#header_inner').stop().animate({'background-color':page_bg_colors['portfolio']['code']},{
			queue:false,
			duration:1200,
			easing:easing,
			complete:function(){window.location.href = link_target}
		});
	});
	// TODO: secondary menu hard-coded garbage because we need to launch. fix someday.
	var $active,
		tertiary_menu = ['web','communications'];
	for(idx in tertiary_menu)
		if(window.location.href.indexOf('services/'+tertiary_menu[idx]+'/')!==-1)
			$('.secondary_nav a[href*="/services/'+tertiary_menu[idx]+'"]').closest('li').addClass('active');
	/*if(window.location.href.indexOf('services/web/')!==-1) $('.secondary_nav a[href*="/services/web"]').closest('li').addClass('active');
	if(window.location.href.indexOf('services/communications/')!==-1) $('.secondary_nav a[href*="/services/communications"]').closest('li').addClass('active');*/
	var $active = $('.secondary_nav li.active a');
	if($active.length!=0){
		if(($active.text()==='WEB' && window.location.href.slice(-4).replace('/','') !== 'web') || ($active.text()==='COMMUNICATIONS' && window.location.href.slice(-14).replace('/','') !== 'communications'))
			$highlight.css('cursor','pointer').click(function(){$active.click()});
		else $active.unbind('click').click(function(e){e.preventDefault()}).css('cursor','default');
		$highlight.css({
			width:$active.width()+25,
			left:$active.position().left
		});
	}
	$('.random_portfolio_link,.item_details a,.content #showcase a').click(function(e){
		e.preventDefault();
		var $this_href = $(this).attr('href');
			$active_subtitle = $active_link.find('.link_subtitle'),
			$portfolio_subtitle = $('#portfolio_link').find('.link_subtitle');
		if($active_subtitle.length>0) $active_subtitle.stop().animate({opacity:0,left:$active_subtitle.position().left+50},fade_dur,easing);
		$portfolio_subtitle.stop().animate({opacity:1,left:parseInt($portfolio_subtitle.css('left'))+50},fade_dur,easing);
		$('body,#header_inner').stop().animate({'background-color':page_bg_colors['portfolio']['code']},{
			queue:false,
			duration:1200,
			easing:easing,
			complete:function(){window.location.href = $this_href}
		}).find('.primary.nav_hover').animate({opacity:0});
		$('#portfolio_hover').animate({opacity:1});
		$('#portfolio_link').animate({color:'#fff'});
		$('#services_link').animate({color:'#dfdfdf'});
		menu_fade();
		content_fade();
	});
	if(section==='portfolio'){
		var $portfolio_filter_visible = $('#portfolio_filter_visible');
		if($portfolio_filter_visible.length>0){
			$portfolio_filter_visible.click(function(e){
				var $target = $(e.target),
					$filter_options = $('#filter_options'),
					height_modifier,
					border_bottom;
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
					$list_to_show = $filter_options.find('#'+$target.attr('id'));
					if($list_to_show.height()>334){
						height_modifier = -15;
						if(is_ie) border_bottom = 15;
						else border_bottom = '15px solid #968f6e';
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
			if($.browser.version === '7.0'){
				$('.arrow_container').remove();
				$('#clear_filters').css('right',240);
				$('#thumbnail_frame').css('height','auto');
				$('#portfolio_sidebar').find('#shadow_inner').height($('.portfolio_thumbnail_list').height()+100);
				if(section==='portfolio') $('#page_body').height($('#page_body').height()+257);
				$('#thumbnail_container').ajaxComplete(function(){$(this).children('#thumbnail_frame').css('height','auto')});
			}
		}
		refresh_current_filters();
		var $filter_options = $('#filter_options');
		if($filter_options.length>0){
			$filter_options.click(function(e){
				var $target = $(e.target),
					target_id = $target.attr('id'),
					target_class = $target.attr('class');
				if(target_class.indexOf('filter_link')!==-1){
					var idx = target_class.replace(' filter_link','');
					filters[idx] = target_id.replace(idx+'_','');
					var position = parseInt($('.multimulti:first').css('left'))/-198;
					$('#portfolio_filter_visible #'+idx).click();
					refresh_thumbnails(filters,position);
					t = setTimeout(function(){
						$('#filter_options #'+idx+' .filter_link.selected').removeClass('selected');
						$('#'+target_id).addClass('selected');
					},350);
					$('#current_'+idx+'_filter').text($target.text());
					refresh_current_filters();
				}
			});
		}
		delegate_thumbnail_click();
		$('#awards_list>li a').click(function(e){
			e.preventDefault();
			var $thumbnail = $($(this).attr('href').replace('/portfolio#portfolio_item','#showcase_thumbnail'));
			$('.showcase_thumbnail_item').stop().animate({opacity:1},fade_dur,easing,function(){
				$thumbnail.stop().animate({opacity:.4},fade_dur,easing);
			});
			$thumbnail.children('a').click();
		});
		$('#thumbnail_container').ajaxComplete(function(){delegate_thumbnail_click()});
		var $clear_filters_link = $('#portfolio_sidebar #clear_filters');
		$clear_filters_link.click(function(){
			filters['service'] = filters['organization_type'] = filters['industry'] = '';
			$('.current_filter').empty();
			refresh_current_filters();
			if($('#thumbnail_container .showcase_thumbnail_item').length!=window.portfolio_thumbnail_count){
				$('#thumbnail_container').fadeOut(350,function(){
					$(this).load('/site_includes/portfolio_list.php',function(){$(this).fadeIn(350)});
				});
				$('#portfolio_filter_visible .category_link.selected').click();
				t = setTimeout("$('#filter_options .filter_link.selected').removeClass('selected');",350);
			}
		});
		var target_is_first_thumbnail = false;
		$('#page_body').css('padding-bottom',0);
		$('#portfolio_sidebar .side_gradient').hover(function(){
			var $this = $(this);
			if(direction_allowed($this)){
				var this_id_selector = '#'+$(this).attr('id'),
					$hovers = $(this_id_selector+'_hover,'+this_id_selector.replace('gradient','hover'));
				if(!is_ie) $hovers.stop().animate({opacity:1});
				else $hovers.show();
			}
		},function(){
			var this_id_selector = '#'+$(this).attr('id'),
				$hovers = $(this_id_selector+'_hover,'+this_id_selector.replace('gradient','hover'))
			if(!is_ie) $hovers.stop().animate({opacity:0});
			else $hovers.hide();
		}).click(function(e){
			var $target = $(e.target);
			if(direction_allowed($target)){
				portfolio_is_moving = true;
				var $portfolio_thumbnail_list = $('.portfolio_thumbnail_list');
				if($target.attr('id').indexOf('left')!==-1) dir = 1;
				else dir = -1;
				$portfolio_thumbnail_list.css('position','absolute').stop().animate({'left':parseInt($portfolio_thumbnail_list.css('left'))+198*dir},600,'swing',function(){portfolio_is_moving = false;});
			}
		});
		window.portfolio_thumbnail_count = $('#thumbnail_container .showcase_thumbnail_item').length;
		if(window.location.hash.substr(0,16)==='#portfolio_item_'){
			var first_thumbnail_id = $('#mm0 .showcase_thumbnail_item:first-child').attr('id'),
				$hash_target = $(window.location.hash.replace('portfolio_item','showcase_thumbnail'));
			if(first_thumbnail_id !== $hash_target.attr('id')) $hash_target.find('a').click();
			else target_is_first_thumbnail = true;
		}else target_is_first_thumbnail = true;
		if(target_is_first_thumbnail) $('#mm0 .showcase_thumbnail_item:first-child').css('opacity',.4);
		else{
			if(!is_ie) $('#portfolio_items_container').css('opacity',0);
			$('.portfolio_thumbnail_list').css('left',-198*(Math.ceil(($hash_target.index('.showcase_thumbnail_item')+1)/12)-1));
		}
		if(is_ie && $.browser.version === '7.0') $('.multimulti').children().unwrap();
	}
	if(section==='contact'){
		$('.standalone_page h1').next('.main_content').css({width:280,float:'left'});
		$('.list_item').css('overflow','hidden');
		$('.list_item_double_wide_column').attr('class','list_item_column').siblings('br,div.clear').remove();
		$('li:contains("Required field")').remove();
		$admin_control_box = $('.admin_control_box');
		$admin_control_box.html($admin_control_box.html().replace(' | ','')).children('a:contains("Cancel")').remove();
		$('#contact_form_wrapper').css('min-height',$('#contact_form_inner').height()+10);
		if($.browser.safari) $('.list_item_column').css('line-height','20px').children('textarea').css({'margin-top':'-19px','margin-left':'146px'});
	}
	if($('.staff_container').length>0) initialize_readmore('bio','p','js_hidden','bio_more','bio_less');
	if($('.content>.feature_box').length>0) initialize_readmore('clients','p','js_hidden','clients_more','clients_less');
	if($('#showcase').length>0){
		$('#navigation>.arrow').hover(function(){
			if(is_ie) $(this).children('img').show();
			else $(this).children('img').stop().animate({'opacity':1},150,easing);
		},function(){
			if(is_ie) $(this).children('img').hide();
			else $(this).children('img').stop().animate({'opacity':0},750,easing);
		});
		function carousel_callback(carousel){
			$('.thumbnail_link').click(function(){
				carousel.scroll($(this).index('.thumbnail_link')+1);
			});
		}
		var number_of_thumbnails_visible;
		var number_of_thumbnails_to_scroll;
		if($('#photos').length>0) {
			number_of_thumbnails_visible = 7;
			number_of_thumbnails_to_scroll = 3;
			$('#photos').jcarousel({
				auto:8,
				scroll:1,
				animation:850,
				initCallback:carousel_callback
			});
		} else {
			number_of_thumbnails_visible = 4;
			number_of_thumbnails_to_scroll = 3;
		}
		function thumbs_callback(carousel){
			$('#left_arrow').click(function(){carousel.prev()});
			$('#right_arrow').click(function(){carousel.next()});
		}
		$('#thumbs').jcarousel({
			animation:850,
			visible:number_of_thumbnails_visible,
			scroll:number_of_thumbnails_to_scroll,
			initCallback:thumbs_callback
		});

	}
});

$(window).load(function(){
	var $showcase = $('#showcase'),
		header_inner_left = $('#header_inner').offset().left;
	$('.nav_hover').each(function(){
		var $this = $(this),
			$corresponding_link = $('#'+$this.attr('id').replace('hover','link')),
			link_width_offset = $corresponding_link.width()/2,
			glow_width_offset = $this.width()/2,
			new_left;
		if(link_width_offset === 0 || $this.attr('id')==='home_hover') new_left = $this.parent().width()/2;
		else new_left = $corresponding_link.offset().left-header_inner_left+link_width_offset-glow_width_offset;
		$this.css('left',new_left);
	});
	if(section==='portfolio') fix_portfolio_height();
});

function fix_portfolio_height(){
	/*var portfolio_sidebar_height = $('#portfolio_sidebar').height(),
		height_modifier = 492;
	if(is_ie){
		if($.browser.version === '7.0') return false;
		height_modifier -= 82;
		portfolio_sidebar_height += $('#awards_sidebar_outer').height();
	}
	$('.portfolio_item_information').height(Math.ceil(portfolio_sidebar_height,$('.inner_wrapper').height())-height_modifier);*/
	
	//$('.portfolio_item_information').height($('#page_body').height()-581);
	
	/*var $portfolio_item_information = $('.portfolio_item_information');
	$portfolio_item_information.height($('#page_body').height() - $portfolio_item_information.css('margin-top') - 450);*/
}
function menu_fade(show){
	if(typeof(show)==='undefined') show = false;
	var $secondary_nav = $('.secondary_nav'),
		finish_pos,new_opacity;
	if(show){
		finish_pos = $secondary_nav.css('margin-left');
		new_opacity = 1;
		$secondary_nav.css({'margin-left':-100,opacity:0});
	}else{
		finish_pos = '100px';
		new_opacity = 0;
	}
	$secondary_nav.stop().animate({'margin-left':finish_pos,opacity:new_opacity},350,circ);
}
function content_fade(show){
	if(typeof(show)==='undefined') show = false;
	var $content_shadow_left = $('#content_shadow_left'),
		finish_pos,new_opacity;
	if(show){
		finish_pos = parseInt($content_shadow_left.css('margin-left'))+14+'px';
		new_opacity = 1;
		$content_shadow_left.css({'margin-left':80,opacity:0});
	}else{
		finish_pos = '-80px';
		new_opacity = 0;
	}
	$content_shadow_left.css({'position':'relative','z-index':-1})
	.stop().animate({'margin-left':finish_pos,opacity:new_opacity},fade_dur,easing,function(){
		$content_shadow_left.css('position','static');
	});
}
function get_section(url){
	var path_array = url.split('/'),
		section = path_array[3],
		hash_pos = section.indexOf('#');
	if(hash_pos!=-1) section = section.substring(0,hash_pos);
	var question_pos = section.indexOf('?');
	if(question_pos!=-1) section = section.substring(0,question_pos);
	return section;
}
function qualify(url){
	var img = document.createElement('img');
	img.src = url;
	url = img.src;
	img.src = null;
	return url;
}
function remove_whitespace(str){return str.replace(/^\s*|\s*$/g,'')}
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
		var $portfolio_thumbnail_list = $('.portfolio_thumbnail_list'),
			list_relative_left = $portfolio_thumbnail_list.position().left,
			dir;
		if($obj.attr('id').indexOf('left')!==-1) dir = 1;
		else dir = -1;
		if((($obj.attr('id')==='arrow_left_gradient' || $obj.attr('id')==='arrow_left_link') && list_relative_left<0)
		|| (($obj.attr('id')==='arrow_right_gradient' || $obj.attr('id')==='arrow_right_link') && list_relative_left>dir*198*($('.portfolio_thumbnail_list>.multimulti').length-1)))
			return true;
		else return false;
	}
}
function delegate_thumbnail_click(){
	$('.showcase_thumbnail_item').undelegate('a','click').delegate('a','click',function(){
		var $parent = $(this).parent(),
			clicked_thumb_id = $parent.attr('id').substr(19);
		if($('.showcase_item.first').attr('id').replace('showcase_item_','')!=clicked_thumb_id){
			$('.showcase_thumbnail_item').animate({opacity:1},fade_dur,'linear');
			$parent.stop().animate({opacity:.4},fade_dur,'linear');
			var $portfolio_items_container = $('#portfolio_items_container');
			if(is_ie) $portfolio_items_container.load('/site_includes/portfolio_item.php',{record_id:clicked_thumb_id});
			else{
				$.post('/site_includes/portfolio_item.php',{record_id:clicked_thumb_id},function(data){
					$portfolio_items_container.stop().animate({opacity:0},fade_dur,'linear',function(){
						$portfolio_items_container.html(data).find('.portfolio_item_image').load(function(){
							fix_portfolio_height();
							$portfolio_items_container.stop().animate({opacity:1},fade_dur,'linear');
						});
					});
				});
			}
		}
	});
}
function toggle_readmore($clicked,parent_class,hidden_class,less_class,more_class){
	var $parent = $clicked.closest('.'+parent_class),
		$hidden = $parent.find('.'+hidden_class),
		current_scroll_pos = document.body.scrollTop;
		new_scroll_pos = $parent.offset().top-200;
	if($clicked.hasClass(less_class)) $parent.find('.'+more_class).fadeIn();
	else $clicked.fadeOut();
	$hidden.fadeToggle();
	if($clicked.hasClass(less_class)){
		if($.browser.webkit) $hidden.hide();
		if(current_scroll_pos>new_scroll_pos) window.scroll(0,new_scroll_pos);
	}
}
function initialize_readmore(content_class,container_type,hidden_class,more_class,less_class,more_text,less_text){
	if(typeof(more_text)==='undefined') more_text = 'more&hellip;';
	if(typeof(less_text)==='undefined') less_text = 'less&hellip;';
	$('.'+content_class).each(function(){
		var $children = $(this).children();
		$children.filter(container_type+':first').show().append(' <span class="'+more_class+'"><a href="javascript://">'+more_text+'</a></span>');
		$children.filter('.'+hidden_class).append(' <span class="'+less_class+'"><a href="javascript://">'+less_text+'</a></span>');
	});
	$('.'+more_class+',.'+less_class).click(function(){
		toggle_readmore($(this),content_class,hidden_class,less_class,more_class);
	});
}
function refresh_current_filters(){
	$('.current_filter:empty').closest('.current_filter_label').hide();
	$('.current_filter:not(:empty)').closest('.current_filter_label').show();
}