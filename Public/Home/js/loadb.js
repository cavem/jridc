$(function () {
		 $("#subpost").click(function(){
				if(isuser==0){
					layer.msg("请选登录",2,8);
					setTimeout(function(){
					 location.href =Loginurl;
					},2000);
					return false;
				}
				$("#cloudname").val($("#Cloudnames").val());
				if($("#cloudname").val()==""){
					layer.msg("负载均衡名不能为空",2,8);
					$("#Cloudnames").focus();
					return false;
				}
				var Cloudnameshow=$("#Cloudnameshow").html();
				if(Cloudnameshow.indexOf("检查通过") > 0 )
				{
				   
				}else{
					layer.msg("负载均衡名输入错误",2,8);
					$("#Cloudnames").focus();
					return false;
				}
				$("#cloudpassword").val($("#passwords").val());
				if($("#cloudpassword").val()==""){
					layer.msg("管理密码不能为空",2,8);
					$("#passwords").focus();
					return false;
				}
				var passwordshow=$("#passwordshow").html();
				if(passwordshow.indexOf("检查通过") > 0 )
				{
				}else{
					layer.msg("云主机密码输入错误",2,8);
					$("#passwords").focus();
					return false;
				}
	
				layer.confirm('确定提交当前云主机订单',function(index){
					$("#cloud_form").ajaxSubmit({		
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
		productlist(defaulttype);//默认线路
		//线路选择
		 $("#iptype").find("li").each(function(i){
			 $(this).click(function(){
				 $(this).siblings().removeClass("hover");
				 $(this).addClass("hover");
			 	 productlist($(this).attr("data"));
				 
			 })
		 });		
		 $( "#years" ).change(function() {
			if($(this).val()==999){
				
			}else{
				
			}
			$("#year").val($(this).val());
			cloudprice();
		 });
		 $( "#qos_slider" ).slider({
		 range: "max",
		 min:1,
		 max: 100,
		 value: 1,
		 step: 1,
		 slide: function( event, ui ) {
		 $( "#qos_slider_info" ).val(ui.value);
		 $( "#qosnum" ).val(ui.value);
		 cloudprice();
		 }
     });
	$("#qos_slider_info" ).change(function() {
		var maxqosend=parseInt($("#maxqos").val());
		var dqosend=parseInt($("#dqos").val());
		if (regIscount($( "#qos_slider_info" ).val())){
		if($( "#qos_slider_info" ).val()>maxqosend)
		{
		$( "#qos_slider_info" ).val(maxqosend);
		$( "#qos_slider" ).slider( "value",maxqosend);
		}else if($( "#qos_slider_info" ).val()< dqosend){
		$( "#qos_slider_info" ).val(dqosend);
		$( "#qos_slider" ).slider( "value",dqosend);
		}
		else{
		$( "#qos_slider" ).slider( "value",$( "#qos_slider_info" ).val());
		}
		}
		else{
		$( "#qos_slider_info" ).val(dqosend);
		$( "#qos_slider" ).slider( "value",dqosend);
		}
		$( "#qosnum" ).val($( "#qos_slider_info" ).val());
		cloudprice();
	});
	$('input[name="conradio"]').click(function () { 
		cloudinfo();
	}); 
}); 
//查询当前线路类型下的产品配置
function productlist(iptype){
		$.getJSON(Ajaxproducturl,{iptype:iptype},
					function(data){
						var producthtml="";
						if(data!=null){
							$.each(data,function(idx,item){
								if(idx==0){
									producthtml='<h2 class="select" onclick="selectproduct('+item.id+')" data="'+item.id+'">'+item.jfname+'</h2>';
									$("#id").val(item.id);
								}else{
									producthtml=producthtml+'<h2 onclick="selectproduct('+item.id+')"  data="'+item.id+'">'+item.jfname+'</h2>';
								}
							});
							$("#productlist").html(producthtml); 
							$(".isrebatediv" ).html("");//清理是否优惠的选择信息
							cloudinfo(); //获取基本信息后在获取价格第一次加载
						}
		});
}

//订单详情
function cloudorder(){
	$conradioid=$('input[name="conradio"]:checked').val();
	$("#con_show_info").html($conradioid);
	$("#qos_show_info").html($("#qosnum").val());
	$("#year_show_info").html($("#years").find("option:selected").text());	
	$("#jf_show_info").html($("#jfname").val());
}
//云主机价格
function cloudprice(){
			 $id=$("#id").val();
			 $qos =$("#qosnum").val();
			 $year=$("#year").val();
			 $ip=$("#ipnum").val();
			 $conradioid=$('input[name="conradio"]:checked').val();
			 $.getJSON(Ajaxcloudpriceurl,{id:$id,qos:$qos,year:$year,ip:$ip,conradioid:$conradioid,rtime:new Date().getTime()},function(data){
					$("#cloudprice").html(data.Price);
					$("#cloudpriceold").html(data.Priceold);
					if (data.Priceold==data.Price)
					{
						$("#isrebate").val("n");
						$(".isrebatediv" ).html("");
						$("#cloudpriceolddiv").css("display","none");
					}else{		
						if (typeof($("#isrebatecheck").val())=="undefined"){
							$(".isrebatediv" ).html("<input id='isrebatecheck' value='y' name='isrebatecheck' onclick='checkrebate()' type='checkbox' checked>是否使用"+data.YName+"<span>（使用折扣优惠期间将无法使用减配功能）！</span>");
							$("#isrebate").val("y");
							$("#cloudpriceolddiv").css("display","");
						}else if(!$("#isrebatecheck").prop("checked")){
							$(".isrebatediv" ).html("<input id='isrebatecheck' value='y' name='isrebatecheck' onclick='checkrebate()' type='checkbox' >是否使用"+data.YName+"<span>（使用折扣优惠期间将无法使用减配功能）！</span>");
							$("#cloudprice").html(data.Priceold);
							$("#cloudpriceold").html(data.cloudprice);
							$("#cloudpriceolddiv").css("display","none");
							$("#isrebate").val("n");
						}else if($("#isrebatecheck").prop("checked")){
							$(".isrebatediv" ).html("<input id='isrebatecheck' value='y' name='isrebatecheck' onclick='checkrebate()' type='checkbox' checked>是否使用"+data.YName+"<span>（使用折扣优惠期间将无法使用减配功能）！</span>");
							$("#cloudpriceolddiv").css("display","");
							$("#isrebate").val("y");
						}
						$("#yzhekou").html(data.YZheKou);
					}
						cloudorder();//订单信息
				}); 
}

//云主机产品基本配置信息
function cloudinfo(){
			$.getJSON(Ajaxcloudinfourl,{id:$("#id").val()},function(data){
						$('#qos_slider').slider('option','max',parseInt(data.mqos)); 
						$('#qos_slider').slider('option', 'min',parseInt(data.dqos));
						$('#qos_slider').slider('option', 'value',parseInt(data.dqos));   
						$("#qos_slider_info").val(data.dqos);
						$("#qosnum").val(data.dqos);
						$("#dqos").val(data.dqos);
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
						$("#jfname").val(data.jfname);
						$("#conid").val($('input[name="conradio"]:checked').val());
						cloudprice();//获取价格
			});
}
//产品选中方法
function selectproduct(pid){
	 $("#productlist").find("h2").each(function(i){
			if(pid==$(this).attr("data")){
				 $(this).siblings().removeClass("select");
				 $(this).addClass("select");
				 $("#id").val($(this).attr("data"));//默认ID
				 cloudinfo();
			}
	  });		 
}
function regIscount(fData){
	var reg = new RegExp("^(0|[1-9][0-9]*)$");return reg.test(fData);
}

function Iupassword()
{
	$("#passwordshow").html("操作系统初始密码,建议6位以上");
}
function IuCloudname()
{
	$("#Cloudnameshow").html("4-14位字母或数字，必须以字母开头");
}
function checkCloudname(name)
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
	        	$.getJSON(Ajaxcloudname,{cloudname:name,rtime:new Date().getTime()},
						function(data){
								if(data.status==1){
									$("#Cloudnameshow").html("<font color=greed>√</font>检查通过");
								}else{
									 $("#Cloudnameshow").html("<font color=red>X</font>这个云主机名已经存在");
								}
				});
	        }else{
		  	 	 $("#Cloudnameshow").html("<font color=red>X</font>不能以数字开头");
			}
		}else
		{
			$("#Cloudnameshow").html("<font color=red>X</font>云主机名称只能数字和字母");
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
function checkpassword(name)
{
	if (name.length<6)
	{
		$("#passwordshow").html("<font color=red>X</font>长度建议6位以上");
	}
	else
	{   
	    if(name.indexOf("&")>=0) {
			 $("#passwordshow").html("<font color=red>X</font>密码只不能包含&");
		}else if(name.indexOf("%")>=0){
			$("#passwordshow").html("<font color=red>X</font>密码只不能包含%");
		}else if(name.indexOf("#")>=0){
			$("#passwordshow").html("<font color=red>X</font>密码只不能包含#");
		}
		else{
			$("#passwordshow").html("<font color=greed>√</font>密码检查通过");
		}
	}
}
