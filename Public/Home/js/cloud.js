$(function () {
		 $("#subpost").click(function(){
				if(isuser==0){
					layer.msg("请选登录",2,8);
					setTimeout(function(){
					 location.href =Loginurl;
					},2000);
					return false;
				}
				if($("#imageuuid").val()=="no"){
					layer.msg("请选择操作系统",2,8);
					return false;
				}
				$("#cloudname").val($("#Cloudnames").val());
				if($("#cloudname").val()==""){
					layer.msg("云主机名不能为空",2,8);
					$("#Cloudnames").focus();
					return false;
				}
				var Cloudnameshow=$("#Cloudnameshow").html();
				if(Cloudnameshow.indexOf("检查通过") > 0 )
				{
				   
				}else{
					layer.msg("云主机名输入错误",2,8);
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
				/*
				//试用最大值判断
				if($("#year").val()==999){
					//当前CPU大小	
					if($("#cpunum").val()>1){
						layer.msg("试用主机CPU不能大于1核",2,8);
						return false;
					}
					if($("#memnum").val()>512){
						layer.msg("试用主机内存不能大于0.5G",2,8);
						return false;
					}
					if($("#disknum").val()>$("#ddisk").val()){
						layer.msg("试用主机存储不能大于默认值"+$("#ddisk").val(),2,8);
						return false;
					}
					if($("#qosnum").val()>$("#dqos").val()){
						layer.msg("试用主机带宽不能大于默认值"+$("#dqos").val(),2,8);
						return false;
					}
				}*/
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
		//选择操作系统
		$('#image_type').change(function(){
			$.getJSON(Ajaxosurl,{id:$("#id").val(),ostypeid:$("#image_type").val(),rtime:new Date().getTime()},
						function(data){
								$("#image_uuid").children().remove();
								var year_option_xz = $("<option value='no'>"+"-请选择操作系统-"+"</option>");
								$("#image_uuid").append(year_option_xz);
								$.each(data,function(idx,item){
									var template_option = $("<option value='"+item.image_uuid+"'>"+item.osname+"</option>");
									$("#image_uuid").append(template_option);
								});
								$("#imageuuid").val("no");
								
			});
		});
		//选择系统
		$('#image_uuid').change(function(){
			$("#imageuuid").val($(this).val());
			cloudorder();
		});
		//设置年份
		$( "#years" ).change(function() {
			$("#year").val($(this).val());
			cantestfun();
			cloudprice();
		});
		$( "#cpu_slider" ).slider({
			range: "max",
			min:1,
			max: 16,
			value: 1,
			step: 1,
			slide: function( event, ui ) {
				 $( "#cpu_slider_info" ).val(ui.value);
				 $( "#cpunum" ).val(ui.value);
			},
			stop: function( event, ui ) {
				 cloudprice();
			}
	});
		//手动修改CPU
		$("#cpu_slider_info" ).change(function() {
			var maxcpuend=parseInt($("#maxcpu").val());
			var dcpuend=1;
			if (regIscount($( "#cpu_slider_info" ).val())){
				if(parseInt($("#cpu_slider_info").val()) > maxcpuend ){
					$( "#cpu_slider_info" ).val(maxcpuend);
					$( "#cpu_slider" ).slider( "value",maxcpuend);
				}else if( parseInt($("#cpu_slider_info").val()) < dcpuend){
					$( "#cpu_slider_info" ).val(dcpuend);
					$( "#cpu_slider" ).slider( "value",dcpuend);
				}else{
					$( "#cpu_slider" ).slider( "value",$( "#cpu_slider_info" ).val());
				}
			}else{
				$( "#cpu_slider_info" ).val(dcpuend);
				$( "#cpu_slider" ).slider( "value",dcpuend);
			}
			$( "#cpunum" ).val($( "#cpu_slider_info" ).val());
			cloudprice();
		});
		//手动修改内存
		$("#mem_slider_info" ).change(function() {
			var maxmemend=parseInt($("#maxmem").val());
			var dmemend=0.5;
			if (regIscount($( "#mem_slider_info" ).val())){
				if($( "#mem_slider_info" ).val()>maxmemend){
					$( "#mem_slider_info" ).val(maxmemend);
					$( "#mem_slider" ).slider( "value",maxmemend);
				}else if($( "#mem_slider_info" ).val()< dmemend){
					$( "#mem_slider_info" ).val(dmemend);
					$( "#mem_slider" ).slider( "value",dmemend);
				}else{
					$( "#mem_slider" ).slider( "value",$( "#mem_slider_info" ).val());
				}
			}else{
				$( "#mem_slider_info" ).val(dmemend);
				$( "#mem_slider" ).slider( "value",dmemend);
			}
			$( "#memnum" ).val($( "#mem_slider_info" ).val()*1024);
			cloudprice();
		});		
	$( "#mem_slider" ).slider({
		 range: "max",
		 min:1,
		 max: 16,
		 value: 1,
		 step: 1,
		 slide: function( event, ui ) {
				if(ui.value>1){
					ui.value = ui.value;
				}
				$( "#mem_slider_info" ).val(ui.value);
				$( "#memnum" ).val(ui.value*1024);
		 },
		stop: function( event, ui ) {
			 cloudprice();
		}
    });
	$( "#disk_slider" ).slider({
		 range: "max",
		 min:10,
		 max: 2000,
		 value: 10,
		 step: 10,
		 slide: function( event, ui ) {
			 $( "#disk_slider_info" ).val(ui.value);
			 $( "#disknum" ).val(ui.value);
		 },
		stop: function( event, ui ) {
			 cloudprice();
		}
		 
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
		 },
		stop: function( event, ui ) {
			 cloudprice();
		}
     });
	 $( "#disk_slider_info" ).change(function() {
			var maxdiskend=parseInt($("#maxdisk").val());
			var ddiskend=parseInt($("#ddisk").val());
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
			cloudprice();
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
//选择IP
function clickip(t){
	$("#ipnum").val(t);
	cloudprice();
}
//是否启动优惠
function checkrebate(){
	cloudprice();
}
//订单详情
function cloudorder(){
	if($("#imageuuid").val()=="no"){
		$("#os_show_info").html("无系统");
	}else{
		$("#os_show_info").html($("#image_uuid").find("option:selected").text());
	}
	$("#cpu_show_info").html($("#cpunum").val());
	$("#mem_show_info").html($("#memnum").val());
	$("#jf_show_info").html($("#jfname").val());
	$("#disk_show_info").html($("#disknum").val());
	$("#qos_show_info").html($("#qosnum").val());
	$("#year_show_info").html($("#years").find("option:selected").text());
	var dlip=$('input[name="dlip"]:checked').val();
	if (typeof(dlip) == "undefined"){dlip=1;}
	var ipinfo=""
	if(dlip==1){
	ipinfo="独立IP"
	}else{
	ipinfo="共享IP"
	}
	$("#ip_show_info").html(ipinfo);
}
//试用处理最大值
function cantestfun(){
	if($("#year").val()==999){
		var msg = '';
		var cpuinfo=$("#cpunum").val();
		var cantestcpu=$("#cantestcpu").val();
		$('#cpu_slider').slider('option','max',parseInt(cantestcpu)); 
		if(cpuinfo>cantestcpu){
			$('#cpu_slider').slider('option', 'value',cantestcpu);
			$("#cpu_slider_info").val(cantestcpu);
			$("#cpunum").val(cantestcpu);
			msg += "CPU最大不能超过"+cantestcpu+ '核<br>';
		}
		var meminfo=$("#memnum").val();
		var cantestmem=$("#cantestmem").val();
		var cantestmemm=cantestmem*1024;
		$('#mem_slider').slider('option','max',parseInt(cantestmem)+0.5); 
		if(meminfo>cantestmemm){
			$('#mem_slider').slider('option', 'value',cantestmem);
			$("#mem_slider_info").val(cantestmem);
			$("#memnum").val(cantestmemm);
			msg += "内存最大不能超过"+cantestmem+ 'G<br>';
		}
		var diskinfo=$("#disknum").val();
		var cantestdisk=$("#cantestdisk").val();
		$('#disk_slider').slider('option','max',parseInt(cantestdisk)); 
		if(diskinfo>cantestdisk){
			$('#disk_slider').slider('option', 'value',cantestdisk);
			$("#disk_slider_info").val(cantestdisk);
			$("#disknum").val(cantestdisk);
			msg += "硬盘最大不能超过"+cantestdisk+ 'G<br>';
		}
		var qosinfo=$("#qosnum").val();
		var cantestqos=$("#cantestqos").val();
		$('#qos_slider').slider('option','max',parseInt(cantestqos)); 
		if(qosinfo>cantestqos){
			$('#qos_slider').slider('option', 'value',cantestqos);
			$("#qos_slider_info").val(cantestqos);
			$("#qosnum").val(cantestqos);
			msg += "带宽最大不能超过"+cantestqos+ 'M<br>';
		}
		if (msg.length > 0)
		{
			layer.msg(msg,5,8);
		}
	}else{
		$('#cpu_slider').slider('option','max',parseInt($("#maxcpu").val())); 
		$('#mem_slider').slider('option','max',parseInt($("#maxmem").val())); 
		$('#disk_slider').slider('option','max',parseInt($("#maxdisk").val())); 
		$('#qos_slider').slider('option','max',parseInt($("#maxqos").val())); 
		
	}	
}
//云主机价格
function cloudprice(){
			 $id=$("#id").val();
			 $cpu=$("#cpunum").val();
			 $mem=$("#memnum").val();
			 $disk=$("#disknum").val();
			 $qos =$("#qosnum").val();
			 $year=$("#year").val();
			 $ip=$("#ipnum").val();
			 $.getJSON(Ajaxcloudpriceurl,{id:$id,cpu:$cpu,mem:$mem,disk:$disk,qos:$qos,year:$year,ip:$ip,rtime:new Date().getTime()},function(data){
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
						$("#syssize").html(data.dsystem);
						//试用 
						$("#cantestcpu").val(data.cantestcpu);
						$("#cantestmem").val(data.cantestmem);
						$("#cantestdisk").val(data.cantestdisk);
						$("#cantestqos").val(data.cantestqos);
						//CPU
						$("#maxcpu").val(data.mcpu);
						$('#cpu_slider').slider('option','max',parseInt(data.mcpu));//CPU设置
						$('#cpu_slider').slider('option', 'min',parseInt(parseInt(data.mincpu)));
						$('#cpu_slider').slider('option', 'value',parseInt(parseInt(data.mincpu)));
						$("#cpu_slider_info").val(parseInt(parseInt(data.mincpu)));
						$("#cpunum").val(parseInt(parseInt(data.mincpu)));
						$("#maxcpu").val(parseInt(data.mcpu));
		
						//内存
						var mamMem = parseInt(data.mmem);
						var mmem = parseInt(data.mmem);
						$("#maxmem").val(mmem);
						$('#mem_slider').slider('option','max',parseFloat(mmem));//内存设置
						$('#mem_slider').slider('option', 'min',parseFloat(data.minmem));
						$('#mem_slider').slider('option', 'value',parseFloat(data.minmem));
						$("#mem_slider_info").val(parseFloat(data.minmem));
						$("#memnum").val(parseInt(parseInt(data.mincpu))*1024);
						$("#maxmem").val(mmem);
						//硬盘
						$("#ddisk").html(data.ddisk);
						$('#disk_slider').slider('option','max',parseInt(data.mdisk)); 
						$('#disk_slider').slider('option', 'min',parseInt(data.ddisk));
						$('#disk_slider').slider('option', 'value',parseInt(data.ddisk));   
						$("#disk_slider_info").val(data.ddisk);
						$("#disknum").val(data.ddisk);
						$("#ddisk").val(data.ddisk);
						$("#maxdisk").val(parseInt(data.mdisk));
						//带宽处理
						$('#qos_slider').slider('option','max',parseInt(data.mqos)); 
						$('#qos_slider').slider('option', 'min',parseInt(data.dqos));
						$('#qos_slider').slider('option', 'value',parseInt(data.dqos));   
						$("#qos_slider_info").val(data.dqos);
						$("#qosnum").val(data.dqos);
						$("#dqos").val(data.dqos);
						$("#maxqos").val(parseInt(data.mqos));
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
						//处理IP信息
						$("#dlip_p").html("");
						if(data.iptypeid0!=0 && data.iptypeid1!=0){
							$("#dlip_p").html('<input type="radio" name="dlip" id="dlip1"  value="1" onclick="clickip(this.value)" checked="checked" />独立IP                   <input type="radio" name="dlip" id="dlip2" onclick="clickip(this.value)" value="2" />共享IP');
							$("#ipnum").val(1);
						}
						if(data.iptypeid0!=0 && data.iptypeid1==0){
							$("#dlip_p").html('<input type="radio" name="dlip" id="dlip1" onclick="clickip(this.value)"  value="1" checked="checked" />独立IP');
							$("#ipnum").val(1);

						}
						if(data.iptypeid0==0 && data.iptypeid1!=0){
							$("#dlip_p").html('<input type="radio" name="dlip" id="dlip2" onclick="clickip(this.value)"  value="2" checked="checked" />共享IP');
							$("#ipnum").val(2);
						}
						$("#imageuuid").val("no");
						$("#jfname").val(data.jfname);
						$("#image_uuid").children().remove();
						var year_option_xz = $("<option value='no'>"+"-请选择操作系统-"+"</option>");
						$("#image_uuid").append(year_option_xz);
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
