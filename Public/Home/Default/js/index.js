$(function(){
	var banner = '5';
	
	
	
	showbanner();
	var st = setInterval(showbanner,15000);
	
	function showbanner(){
			if(banner == '1'){
				}
			else{
				
			$("#bd li").css({display:'none'});
			$("#banner"+banner).fadeIn("slow");
				
				}
		selseton('#hd .li'+banner);
			showbannerfont(banner);
			if( banner == '7'){
				banner ='1';
				}else{
					
					banner++;
					}
		
		}
	
	//上下翻页
	$('#hd .prev').click(function(){
			if( banner == '2'){
					banner  = '7'
				}
			else if( banner == '1'){
					banner  = '6'
				}else{
					banner = banner-2;
					
					}
				
			showbanner();
			clearInterval(st);
			setTimeout( st = setInterval(showbanner,15000),15000);
		})
		
	$('#hd .next').click(function(){
			if( banner == '7'){
					banner  = '7'
				}
			
			showbanner();
			clearInterval(st);
			setTimeout( st = setInterval(showbanner,15000),15000);
		})
	

	//第一个显示
	function showbannerfont(num){
			if(num == '1'){
				$('.bannermain1 h1').css({top:'-100px',opacity: '0'})
				$('.bannermain1 p').css({top:'-100px',opacity: '0'})
				$('.bannermain1 a').css({top:'-100px',opacity: '0'})
				$('.bannermain1 h1').animate({top:'0px',opacity: '1'},500);
				$('.bannermain1 p').animate({top:'60px',opacity: '1'},600);
				$('.bannermain1 a').animate({top:'180px',opacity: '1'},700);
			}
			if(num == '2'){
				$('.bannermain2 h1').css({left:'-100px',opacity: '0'})
				$('.bannermain2 p').css({left:'-100px',opacity: '0'})
				$('.bannermain2 a').css({left:'-100px',opacity: '0'})
				$('.bannermain2 h1').animate({left:'0px',opacity: '1'},500);
				$('.bannermain2 p').animate({left:'0px',opacity: '1'},700);
				$('.bannermain2 a').animate({left:'0px',opacity: '1'},900);
			}
			if(num == '3'){
				$('.bannermain3 h1').css({top:'250px',opacity: '0'})
				$('.bannermain3 p').css({top:'250px',opacity: '0'})
				$('.bannermain3 a').css({top:'250px',opacity: '0'})
				$('.bannermain3 h1').animate({top:'0px',opacity: '1'},500);
				$('.bannermain3 p').animate({top:'60px',opacity: '1'},600);
				$('.bannermain3 a').animate({top:'180px',opacity: '1'},700);
			}
			if(num == '4'){
				$('.bannermain4 h1').css({right:'100px',opacity: '0'})
				$('.bannermain4 p').css({right:'100px',opacity: '0'})
				$('.bannermain4 a').css({right:'100px',opacity: '0'})
				$('.bannermain4 h1').animate({right:'0px',opacity: '1'},500);
				$('.bannermain4 p').animate({right:'0px',opacity: '1'},700);
				$('.bannermain4 a').animate({right:'0px',opacity: '1'},900);
			}
			if(num == '5'){
				$('.bannermain5 h1').css({left:'100px',opacity: '0'})
				$('.bannermain5 p').css({left:'100px',opacity: '0'})
				$('.bannermain5 a').css({left:'100px',opacity: '0'})
				$('.bannermain5 h1').animate({left:'0px',opacity: '1'},500);
				$('.bannermain5 p').animate({left:'0px',opacity: '1'},700);
				$('.bannermain5 a').animate({left:'0px',opacity: '1'},900);
			}
			if(num == '6'){
				$('.bannermain6 h1').css({top:'-100px',opacity: '0'})
				$('.bannermain6 p').css({top:'-100px',opacity: '0'})
				$('.bannermain6 a').css({top:'-100px',opacity: '0'})
				$('.bannermain6 h1').animate({top:'0px',opacity: '1'},500);
				$('.bannermain6 p').animate({top:'60px',opacity: '1'},600);
				$('.bannermain6 a').animate({top:'180px',opacity: '1'},700);
			}
			if(num == '7'){
				$('.bannermain7 h1').css({right:'100px',opacity: '0'})
				$('.bannermain7 p').css({right:'100px',opacity: '0'})
				$('.bannermain7 a').css({right:'100px',opacity: '0'})
				$('.bannermain7 h1').animate({right:'0px',opacity: '1'},500);
				$('.bannermain7 p').animate({right:'0px',opacity: '1'},700);
				$('.bannermain7 a').animate({right:'0px',opacity: '1'},900);
			}
			
		
		}
	
	//点击选择类名
		$("#hd li").click(function(){
				selseton(this);
			});
			
		$("#hd .li1").click(function(){
			banner = 1;
			showbanner();
		});
		$("#hd .li2").click(function(){
			banner = 2;
			showbanner();
		});
		$("#hd .li3").click(function(){
			banner = 3;
			showbanner();
		});
		$("#hd .li4").click(function(){
			banner = 4;
			showbanner();
		});
		$("#hd .li5").click(function(){
			banner = 5;
			showbanner();
		});
		$("#hd .li6").click(function(){
			banner = 6;
			showbanner();
		});
		$("#hd .li7").click(function(){
			banner = 7;
			showbanner();
		});
	
	//头部
		$('.header-nav li').mousemove(function(){
				$('.header-nav li').removeClass("selected");
				$(this).addClass("selected");
			});
		$('.header-nav').mouseleave(function(){
				$('.header-nav li').removeClass("selected");
			
			});
	//选择ON
		function selseton(dom){
			$("#hd li").removeClass("on");
			$(dom).addClass("on");
			}
	})