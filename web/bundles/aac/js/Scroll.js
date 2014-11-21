/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//<script type="text/javascript">
$(window).scroll(function(){        
    if ($(this).scrollTop() > 50){        	
        $('#cabecera_1').css('transition','all 300ms');        	
        $('#cabecera_1').css('transition','all 300ms');            
        $("#cabecera_1").css("display","none");            
        $("#cabecera_11").css("display","block");
        $('#cabecera_1 .top-bg').css("border-bottom","2px solid #00B1D7");            
        $('#cabecera_1 .bottom-bg').css("display","none");            
        $('#cabecera_1 #site-title').css("margin-bottom","5px");            
        $('#cabecera_1 #site-title').css("margin-top","-10px");            
        $('#cabecera_1 #site-title').css("font-size","29px");            
        $('#cabecera_1 .social-profile').css("display","none");           
        $('#cabecera_1 #mainmenu').css("margin-top","0px");           
        $('#cabecera_1 .logo-wrap').css("margin-top","20px");           
        $('#cabecera_1 .social-search').css("margin-top","0px");           
        $(".cabecera_3").hide();        }
    else{            
        $('#cabecera_1').css('box-shadow','none');            
        $("#cabecera_11").css("display","none");            
        $("#cabecera_1").css("display","block");			
        $('#cabecera_1 .bottom-bg').css("display","block");			
        $('#cabecera_1 #site-title').css("font-size","39px");			
        $('#cabecera_1 #site-title').css("margin-top","0px");			
        $('#cabecera_1 #site-title').css("margin-bottom","12px");			
        $("#cabecera_1 #site-description").css("display","block");			
        $('#cabecera_1 .social-profile').css("display","block");            
        $('#cabecera_1 #mainmenu').css("margin-top","10px");            
        $('#cabecera_1 .logo-wrap').css("margin-top","25px");            
        $('#cabecera_1 .social-search').css("margin-top","10px");            
        $(".cabecera_3").fadeIn();
    }
});        
$(document).ready(function(){    	
    $("#menu").click(function(){    		
        $("#mainmenu").slideToggle("fast");
    });    
});
        
//<script>


