
function setTab(m,n){
var menu=document.getElementById("tabT"+m).getElementsByTagName("li");
var div=document.getElementById("tabB"+m).getElementsByTagName("div");
var showdiv=[];
for (i=0; j=div[i]; i++){
if ((" "+div[i].className+" ").indexOf(" tabBlock ")!=-1){	
	showdiv.push(div[i]);
}
}
for(i=0;i<menu.length;i++)
{
menu[i].className=i==n?"now":"";
showdiv[i].style.display=i==n?"Block":"none";
}
}

$(document).ready(function(){
	$(".topUser dt").mouseover(function(){
		$(this).addClass("now");
		$(this).next("dd").show();
	 });
	$(".topUser dl").mouseleave(function(){
		$(this).find("dt").removeClass("now");
		$(this).find("dd").hide();
	 });
	
	var titletext = $(".mainT b").text();
	$(".nav a").each(function(){
		var aTitle = $(this).text();
		if( aTitle == titletext){
			$(this).addClass("now");
		}
	});
	
	$(".table").each(function(){
		$("tr:even").addClass("tdColor");
	});
	$(".paymentBox input").each(function(){
		if( $(this).is(':checked')){
			$(this).parents("label").addClass("now")
		}
	});
	$(".paymentBox label").click(function(){
		if( $(this).find("input").is(':checked')){
			$(this).addClass("now")
			$(this).siblings().removeClass("now")
		}
	 });
	$(".forms .item").each(function(){
		if ( $(this).is(':has(textarea)') ) {$(this).addClass("itemText")}
	});
	$(".invoiceMb").each(function(){
		$(this).find("label").click(function(){
			$(this).siblings().removeClass("now")
			$(this).addClass("now")
		});
	});
	$("#rebates").each(function(){
		var abtn = 	$(this).find(".textBtn");	
		var text = 	$(this).find(".rebatesText");
		var textBtn = 	$(this).find(".rebatesTextT").find("span");	
		abtn.click(function(){
			if ( text.is(":hidden")) { text.show()}else{ text.hide()}
		});
		textBtn.click(function(){ text.hide()});
	});
});





//selectЧ��
function pstyle(){
	if($('#select_'+$(this).attr('id')).find('span').length>=7){
		$('#select_'+$(this).attr('id')).find('p').css({"height":"210px","overflow":"auto"});
	}
}
jQuery.fn.extend({
	initSelect:function(param){
		$(this).each(function(){
			var selectObj = $(this);
			$(selectObj).hide();
			$(this).fill(param);
			//�����¼�
			$('#select_'+$(this).attr('id')).find('strong').live('click',function(){
				$(this).siblings('p').show();
			});
			$('#select_'+$(this).attr('id')+' span').live('click',function(){
				$(this).parent().hide();
				$(this).parent().siblings('strong').text($(this).text());
				$(selectObj).find('option[value='+$(this).attr('setvalue')+']').attr('selected',true);
				$(selectObj).change();
			});
			$(document).mouseup(function(event){ if($(event.target).parents('#select_'+$(selectObj).attr('id')).length==0){ $('#select_'+$(selectObj).attr('id')).find('p').hide(); } }) ;	//����֮����������
		});
	},
	refresh:function(){
		$('#select_'+$(this).attr('id')).html($(this).getSelect());
		pstyle()
		$('#select_'+$(this).attr('id')).click(function(){
			$(".selectBox").removeClass("now");
			$(this).addClass("now");
		});
	},
	fill:function(param){
		$(this).after('<div class="'+param+'" id="select_'+$(this).attr('id')+'">'+$(this).getSelect()+'</div>');
		if($('#select_'+$(this).attr('id')).find('span').length>=7){
			$('#select_'+$(this).attr('id')).find('p').css({"height":"210px","overflow":"auto"});
		}
		$('#select_'+$(this).attr('id')).click(function(){
			$(".selectBox").removeClass("now");
			$(this).addClass("now");
		});
	},
	getSelect:function(){
		var selectObj = $(this);
		var optionStr = '<strong>'+$(selectObj).find('option:selected').text()+'</strong><p>';
		var i =0;
		$(selectObj).find('option').each(function(){
			optionStr += '<span setvalue="'+$(this).attr('value')+'">'+$(this).text()+'</span>';
			i++;
		});
		optionStr += '</p>';
		return optionStr;
	}
});

