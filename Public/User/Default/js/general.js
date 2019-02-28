$(function () {
	//通用提示操作
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
	//通用提交 刷新表单
	$(".btnsubmitpost").click(function(){ 
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//重置表单
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
			}
		});
		return false;
	});
	//通用提交 刷新表单
	$(".btnsubmitpostrule").click(function(){ 
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//重置表单
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
            	location.reload();
			}
		});
		return false;
	});
	//通用编辑
	$(".btnsubmitedit").click(function(){ 
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: false,
			beforeSubmit: function() {
			   return $(".formvalidate").valid();
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
			}
		});
		return false;
	});
	//用户联系人信息
	$(".btnsubmitcontact").click(function(){ 
	 	if($("#names").val()==""){
	 		layer.msg("联系人不能为空",2,8);
	   		$("#names").focus();
	   		return false;
	   	}
	 	if($("#emails").val()==""){
	 		layer.msg("邮箱地址不能为空",2,8);
	   		$("#emails").focus();
	   		return false;
	   	}
	 	if(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test($("#emails").val())){ 

		}else{
			    layer.msg("邮件格式错误",2,8);
		   		$("#emails").focus();
		   		return false;
		}
	 	if($("#mobis").val()==""){
	 		layer.msg("联系手机不能为空",2,8);
	   		$("#mobis").focus();
	   		return false;
	   	}
	 	var length = $("#mobis").val().length;
	 	if(length == 11 && /^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test($("#mobis").val())){ }else{
			    layer.msg("手机格式错误",2,8);
		   		$("#mobi").focus();
		   		return false;
		}
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: false,//重置表单
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            	setTimeout(function(){
	            		location.reload();
                    },2000);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
			}
		});
		return false;
	});
	//充值卡充值
	$(".btnsubmitpostcart").click(function(){ 
		if($("#cid").val()==""){
	 		layer.msg("卡号不能为空",2,8);
	   		$("#cid").focus();
	   		return false;
	   	}
		if($("#cpass").val()==""){
	 		layer.msg("卡密不能为空",2,8);
	   		$("#cpass").focus();
	   		return false;
	   	}
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//重置表单
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            	setTimeout(function(){
	            		location.reload();
	            	},2000);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
			}
		});
		return false;
	});
	//优惠券
	$(".btnsubmitpostcoupon").click(function(){ 
		if($("#couponnum").val()==""){
	 		layer.msg("优惠券不能为空",2,8);
	   		$("#couponnum").focus();
	   		return false;
	   	}
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//重置表单
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				if(data.status == '1'){
	            	layer.msg(data.info,2,1);
	            	setTimeout(function(){
	            		location.reload();
	            	},2000);
	            }else{
	            	layer.msg(data.info,2,8);
	     	    }
			}
		});
		return false;
	});
}); 
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