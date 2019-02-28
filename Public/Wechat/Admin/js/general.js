jQuery(document).ready(function(){
	jQuery('.userinfo').click(function(){
		if(!jQuery(this).hasClass('active')) {
			jQuery('.userinfodrop').show();
			jQuery(this).addClass('active');
		} else {
			jQuery('.userinfodrop').hide();
			jQuery(this).removeClass('active');
		}
		jQuery('.notification').removeClass('active');
		jQuery('.noticontent').remove();
		
		return false;
	});
	jQuery('.notification a').click(function(){
		var t = jQuery(this);
		var url = t.attr('href');
		if(!jQuery('.noticontent').is(':visible')) {
			jQuery.post(url,function(data){
				t.parent().append('<div class="noticontent">'+data+'</div>');
			});
			//this will hide user info drop down when visible
			jQuery('.userinfo').removeClass('active');
			jQuery('.userinfodrop').hide();
		} else {
			t.parent().removeClass('active');
			jQuery('.noticontent').hide();
		}
		return false;
	});
	jQuery(document).click(function(event) {
		var ud = jQuery('.userinfodrop');
		var nb = jQuery('.noticontent');
		
		//hide user drop menu when clicked outside of this element
		if(!jQuery(event.target).is('.userinfodrop') 
			&& !jQuery(event.target).is('.userdata') 
			&& ud.is(':visible')) {
				ud.hide();
				jQuery('.userinfo').removeClass('active');
		}
		
		//hide notification box when clicked outside of this element
		if(!jQuery(event.target).is('.noticontent') && nb.is(':visible')) {
			nb.remove();
			jQuery('.notification').removeClass('active');
		}
	});
	jQuery('.vernav > ul li a, .vernav2 > ul li a').each(function(){
		var url = jQuery(this).attr('href');
		jQuery(this).click(function(){
			if(jQuery(url).length > 0) {
				if(jQuery(url).is(':visible')) {
					if(!jQuery(this).parents('div').hasClass('menucoll') &&
					   !jQuery(this).parents('div').hasClass('menucoll2'))
							jQuery(url).slideUp();
				} else {
					jQuery('.vernav ul ul, .vernav2 ul ul').each(function(){
							jQuery(this).slideUp();
					});
					if(!jQuery(this).parents('div').hasClass('menucoll') &&
					   !jQuery(this).parents('div').hasClass('menucoll2'))
							jQuery(url).slideDown();
				}
				return false;	
			}
		});
	});
	jQuery('.menucoll > ul > li, .menucoll2 > ul > li').live('mouseenter mouseleave',function(e){
		if(e.type == 'mouseenter') {
			jQuery(this).addClass('hover');
			jQuery(this).find('ul').show();	
		} else {
			jQuery(this).removeClass('hover').find('ul').hide();	
		}
	});
	jQuery('.hornav a').click(function(){
		
		//this is only applicable when window size below 450px
		if(jQuery(this).parents('.more').length == 0)
			jQuery('.hornav li.more ul').hide();
		
		//remove current menu
		jQuery('.hornav li').each(function(){
			jQuery(this).removeClass('current');
		});
		
		jQuery(this).parent().addClass('current');	// set as current menu
		
		var url = jQuery(this).attr('href');
		if(jQuery(url).length > 0) {
			jQuery('.contentwrapper .subcontent').hide();
			jQuery(url).show();
		} else {
			jQuery.post(url, function(data){
				jQuery('#contentwrapper').html(data);
				jQuery('.stdtable input:checkbox').uniform();	//restyling checkbox
			});
		}
		return false;
	});
	jQuery('.notibar .close').click(function(){
		jQuery(this).parent().fadeOut(function(){
			jQuery(this).remove();
		});
	});
	
	jQuery('.togglemenu').click(function(){
		if(!jQuery(this).hasClass('togglemenu_collapsed')) {
			
			//if(jQuery('.iconmenu').hasClass('vernav')) {
			if(jQuery('.vernav').length > 0) {
				if(jQuery('.vernav').hasClass('iconmenu')) {
					jQuery('body').addClass('withmenucoll');
					jQuery('.iconmenu').addClass('menucoll');
				} else {
					jQuery('body').addClass('withmenucoll');
					jQuery('.vernav').addClass('menucoll').find('ul').hide();
				}
			} else if(jQuery('.vernav2').length > 0) {
			//} else {
				jQuery('body').addClass('withmenucoll2');
				jQuery('.iconmenu').addClass('menucoll2');
			}
			
			jQuery(this).addClass('togglemenu_collapsed');
			
			jQuery('.iconmenu > ul > li > a').each(function(){
				var label = jQuery(this).text();
				jQuery('<li><span>'+label+'</span></li>')
					.insertBefore(jQuery(this).parent().find('ul li:first-child'));
			});
		} else {
			
			//if(jQuery('.iconmenu').hasClass('vernav')) {
			if(jQuery('.vernav').length > 0) {
				if(jQuery('.vernav').hasClass('iconmenu')) {
					jQuery('body').removeClass('withmenucoll');
					jQuery('.iconmenu').removeClass('menucoll');
				} else {
					jQuery('body').removeClass('withmenucoll');
					jQuery('.vernav').removeClass('menucoll').find('ul').show();
				}
			} else if(jQuery('.vernav2').length > 0) {	
			//} else {
				jQuery('body').removeClass('withmenucoll2');
				jQuery('.iconmenu').removeClass('menucoll2');
			}
			jQuery(this).removeClass('togglemenu_collapsed');	
			
			jQuery('.iconmenu ul ul li:first-child').remove();
		}
	});
	
	
	
	///// menu /////
	if(jQuery(document).width() < 640) {
		jQuery('.togglemenu').addClass('togglemenu_collapsed');
		if(jQuery('.vernav').length > 0) {
			
			jQuery('.iconmenu').addClass('menucoll');
			jQuery('body').addClass('withmenucoll');
			jQuery('.centercontent').css({marginLeft: '56px'});
			if(jQuery('.iconmenu').length == 0) {
				jQuery('.togglemenu').removeClass('togglemenu_collapsed');
			} else {
				jQuery('.iconmenu > ul > li > a').each(function(){
					var label = jQuery(this).text();
					jQuery('<li><span>'+label+'</span></li>')
						.insertBefore(jQuery(this).parent().find('ul li:first-child'));
				});		
			}

		} else {
			
			jQuery('.iconmenu').addClass('menucoll2');
			jQuery('body').addClass('withmenucoll2');
			jQuery('.centercontent').css({marginLeft: '36px'});
			
			jQuery('.iconmenu > ul > li > a').each(function(){
				var label = jQuery(this).text();
				jQuery('<li><span>'+label+'</span></li>')
					.insertBefore(jQuery(this).parent().find('ul li:first-child'));
			});		
		}
	}
	/////更改模板样式 /////
	jQuery('.changetheme a').click(function(){
		var c = jQuery(this).attr('class');
		if(jQuery('#addonstyle').length == 0) {
			if(c != 'default') {
				jQuery('head').append('<link id="addonstyle" rel="stylesheet" href="/Public/Admin/css/style.'+c+'.css" type="text/css" />');
				jQuery.cookie("addonstyle", c, { path: '/' });
			}
		} else {
			if(c != 'default') {
				jQuery('#addonstyle').attr('href','/Public/Admin/css/style.'+c+'.css');
				jQuery.cookie("addonstyle", c, { path: '/' });
			} else {
				jQuery('#addonstyle').remove();	
				jQuery.cookie("addonstyle", null);
			}
		}
	});
	
	///// 更改模板样式 /////
	if(jQuery.cookie('addonstyle')) {
		var c = jQuery.cookie('addonstyle');
		if(c != '') {
			jQuery('head').append('<link id="addonstyle" rel="stylesheet" href="/Public/Admin/css/style.'+c+'.css" type="text/css" />');
			jQuery.cookie("addonstyle", c, { path: '/' });
		}
	}
	//权限设置全选
	jQuery(".module-item").change(function(){
        var parent = jQuery(this).parent().parent();
        if(this.checked)
        {
        	jQuery('.select-all,.action-item',parent).attr({
                'disabled':true,
                'checked':false
            });
        }
        else
        {
        	jQuery('.select-all,.action-item',parent).attr({
                'disabled':false
            });
        }
    });
	//全选
	jQuery(".select-all").change(function(){
        var parent = jQuery(this).parent().parent();
        if(this.checked)
        {
        	jQuery('.action-item',parent).attr({
                'checked':true
            });
        }
        else
        {
        	jQuery('.action-item',parent).attr({
                'checked':false
            });
        }
    });
	//全选
	jQuery(".action-item").change(function(){
        var parent = jQuery(this).parent().parent();
        if(jQuery(".action-item:not([checked])",parent).length == 0)
        {
        	jQuery('.select-all',parent).attr({
                'checked':true
            });
        }
        else
        {
        	jQuery('.select-all',parent).attr({
                'checked':false
            });
        }
    });
	//切换tabs
	jQuery('#tabs').tabs();
});
function openItem(menu){
    var str = menu.split(',');
    var op = str[0];
    try{
        var module = str[1];
        var action = str[2];
        var id = str[3];
    }catch(ex){}
    if (typeof(module)=='undefined'){
        var nav = menu;
    }
    $('.current').removeClass('current');
    $('#nav_'+nav).addClass('current');
    var url = '/index.php/Admin/'+module+'/'+action;
    window.location.href=url;
}
//数据提交处理
$(function () {
	$(".submitadd").click(function(){ 
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//resetForm: true,重置form表单
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				if(data.status == '1'){
		            	layer.msg(data.info,2,1);
		            }else{
		            	layer.msg(data.info,2,8);
		        }
			},
			error:function(xhr){
				layer.msg(xhr,2,8);
			}
		});
		return false;
	});
	$(".submitedit").click(function(){ 
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				if(data.status == '1'){
		            	layer.msg(data.info,2,1);
		            }else{
		            	layer.msg(data.info,2,8);
		        }
			},
			error:function(xhr){
				layer.msg(xhr,2,8);
			}
		});
		return false;
	});
	$(".submitdel").click(function(){ 
		layer.confirm('确定操作当前信息吗？',function(index){
			$(".formvalidate").ajaxSubmit({
				dataType:  'json',
				beforeSend: function() {
					var loadi = layer.load('数据提交中...');//提示框
				},
				beforeSubmit: function() {

				},
				success: function(data) {
					layer.closeAll();
					if(data.status == '1'){
			            	layer.msg(data.info,2,1);
			            }else{
			            	layer.msg(data.info,2,8);
			        }
					location.reload();
				},
				error:function(xhr){
					layer.msg(xhr,2,8);
					location.reload();
				}
			});
			
		});
		return false;
	});
	$('.confirmbutton').click(function(){
		var href=$(this).attr('href');
		layer.confirm('确定操作当前信息吗？',function(index){
			 $.get(href,function(msgObj){
		            if(msgObj.status == '1'){
		            	layer.msg(msgObj.info,2,1);
		            	location.reload();
		            }else{
		            	layer.msg(msgObj.info,2,8);
		            	location.reload();
		            }
		      },"JSON");
		});
		return false;
	});
	$("#checkboxall").click(function(){
		if(this.checked){
			$("#tablelist :checkbox").attr("checked", true);
		}else{
			$("#tablelist :checkbox").attr("checked", false);
		}
	 });
	$("#tablelist :checkbox").click(function(){
		var chknum = $("#tablelist :checkbox").size();//选项总个数
		var chk = 0;
		$("#tablelist :checkbox").each(function () {
			if($(this).attr("checked")){
				chk++;
			}
		});
		if(chknum==chk){
			$("#checkboxall").attr("checked",true);
		}else{
			$("#checkboxall").attr("checked",false);
		}
	});
	
});
//获取地区信息
function loadRegion(sel,type_id,selName,town,url){
	if(town!=""){
		$("#"+town+" option").each(function(){
			jQuery(this).remove();
		});	
		$("<option value=''>请选择</option>").appendTo($("#"+town));
	}
	$("#"+selName+" option").each(function(){
		$(this).remove();
	});
	$("<option value=''>请选择</option>").appendTo($("#"+selName));
	if($("#"+sel).val()==0){
		return;
	}
	$.getJSON(url,{pid:$("#"+sel).val(),type:type_id},
		function(data){
			if(data){
				$.each(data,function(idx,item){
					$("<option value="+item.id+">"+item.name+"</option>").appendTo($("#"+selName));
				});
			}else{
				$("<option value=''>请选择</option>").appendTo($("#"+selName));
			}
		}
	);
}


