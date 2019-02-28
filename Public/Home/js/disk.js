$(function () {
		 $("#subpost").click(function(){
				if(isuser==0){
					layer.msg("请选登录",2,8);
					setTimeout(function(){
					 location.href =Loginurl;
					},2000);
					return false;
				}
				$("#diskname").val($("#disknames").val());
				if($("#diskname").val()==""){
					layer.msg("云硬盘名不能为空",2,8);
					$("#disknames").focus();
					return false;
				}
				var Cloudnameshow=$("#Cloudnameshow").html();
				if(Cloudnameshow.indexOf("检查通过") > 0 )
				{
				   
				}else{
					layer.msg("云硬盘名输入错误",2,8);
					$("#disknames").focus();
					return false;
				}
				if($("#disknum").val()==0){
					layer.msg("云硬盘大小不能为0",2,8);
					return false;
				}
				if($("#disknum").val()==""){
					layer.msg("云硬盘大小不能为0",2,8);
					return false;
				}
	
				layer.confirm('确定提交当前云硬盘订单',function(index){
					$("#disk_form").ajaxSubmit({		
						dataType:  'json',
						resetForm: true,
						success: function(data) {
							if(data.status == '1'){
								layer.msg(data.info,2,1);
								setTimeout(function(){
								 location.href =data.url;
								},2000);
							}else{
								layer.msg(data.info,2,8);
							}
						}
					});
				});
			return false;
		  }); 
		 diskinfo();
		 $( "#years" ).change(function() {
			if($(this).val()==999){
				
			}else{
				
			}
			$("#year").val($(this).val());
			diskprice();
		 });
		 $( "#disk_slider" ).slider({
		 range: "max",
		 min:0,
		 max: 2000,
		 value: 0,
		 step: 10,
		 slide: function( event, ui ) {
			 $( "#disk_slider_info" ).val(ui.value);
			 $( "#disknum" ).val(ui.value);
			 diskprice();
		 }
		 });
		 $( "#disk_slider_info" ).change(function() {
				var maxdiskend=parseInt($("#maxdisk").val());
				var ddiskend=0;
				if (regIscount($( "#disk_slider_info" ).val())){	
				if($( "#disk_slider_info" ).val()>maxdiskend)
				{
					$( "#disk_slider_info" ).val(maxdiskend);
					$( "#disk_slider" ).slider( "value",maxdiskend);
				}else if($( "#disk_slider_info" ).val()<ddiskend){
					$( "#disk_slider_info" ).val(ddiskend);
					$( "#disk_slider" ).slider( "value",ddiskend);
				}
				else{
				var val;
				val=$( "#disk_slider_info" ).val();
				var rem=val%10;
				if (rem!=0)
				{
				$( "#disk_slider_info" ).val(Math.round(val/10)*10);
				}
				$( "#disk_slider" ).slider( "value",$( "#disk_slider_info" ).val());
				}
				}else{
				  $( "#disk_slider_info" ).val(ddiskend);
				  $( "#disk_slider" ).slider( "value",ddiskend);
				}
				$( "#disknum" ).val($( "#disk_slider_info" ).val());
				diskprice();
			}); 
}); 
//订单详情
function diskorder(){
	$("#jf_show_info").html($("#jfname").val());
	$("#disk_show_info").html($("#disknum").val());
	$("#year_show_info").html($("#years").find("option:selected").text());	
}
//云主机价格
function diskprice(){
			 $id=$("#id").val();
			 $disk =$("#disknum").val();
			 $year=$("#year").val();
			 $.getJSON(Ajaxdiskpriceurl,{id:$id,year:$year,disk:$disk,rtime:new Date().getTime()},function(data){
					
				 $("#diskprice").html(data.Price);
					diskorder();
				}); 
}

//云主机产品基本配置信息
function diskinfo(){
		$.getJSON(Ajaxdiskinfourl,{id:$("#id").val()},function(data){
						$('#disk_slider').slider('option','max',parseInt(data.mdisk)); 
						$('#disk_slider').slider('option', 'min',0);
						$('#disk_slider').slider('option', 'value',0);   
						$("#disk_slider_info").val(0);
						$("#disknum").val(0);
						$("#mdisk").val(data.mdisk);
						$("#jfname").val(data.jfname);
						$("#years").children().remove();
						$.each(data.year,function(idx,item){
							if(item.yearname=="月付"){
								$("#year").val(item.value);
								var year_option = $("<option selected value='"+item.value+"'>"+item.yearname+"</option>");
							}else{
								var year_option = $("<option value='"+item.value+"'>"+item.yearname+"</option>");
							}
							$("#years").append(year_option);
						});
						diskprice();
			});
}
//产品选中方法
function selectproduct(pid){
	 $("#productlist").find("h2").each(function(i){
			if(pid==$(this).attr("data")){
				 $(this).siblings().removeClass("select");
				 $(this).addClass("select");
				 $("#id").val($(this).attr("data"));//默认ID
				 diskinfo();
			}
	  });		 
}
function regIscount(fData){
	var reg = new RegExp("^(0|[1-9][0-9]*)$");return reg.test(fData);
}
function Iudiskname()
{
	$("#Cloudnameshow").html("4-14位字母或数字，必须以字母开头");
}
function checkdiskname(name)
{
	if (name.length>14 |name.length<4)
	{
		$("#Cloudnameshow").html("<font color=red>X</font>长度需在4-14之间");
	}
	else
	{
		if(checkabc(name))
		{
      	 	var ok = /^[a-zA-Z][a-zA-Z0-9]*$/.test(name);
	        if(ok)
	        {
	        	$.getJSON(Ajaxdiskname,{diskname:name,rtime:new Date().getTime()},
						function(data){
								if(data.status==1){
									$("#Cloudnameshow").html("<font color=greed>√</font>检查通过");
								}else{
									 $("#Cloudnameshow").html("<font color=red>X</font>这个云硬盘名已经存在");
								}
				});
	        }else{
		  	 	 $("#Cloudnameshow").html("<font color=red>X</font>不能以数字开头");
			}
		}else
		{
			$("#Cloudnameshow").html("<font color=red>X</font>云硬盘名称只能数字和字母");
		}
	}
}
function checkabc(tempstr)
{
	ptempstr=new String("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
	for(i=0;i<tempstr.length;i++)
	{
		if(ptempstr.indexOf(tempstr.charAt(i))==-1)
		{
			return false;
		}
	}
	return true;
}
