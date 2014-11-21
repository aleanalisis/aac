/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(window).scroll(function(){
    if ($(this).scrollTop() > 50){
        $('#header').css('transition','all 300ms');
        $('#header *').css('transition','all 300ms');
        $('#header').css('box-shadow','0 3px 5px gray');
        $("#header #site-description").css("display","none");
        $('#header .top-bg').css("border-bottom","2px solid #00B1D7");
        $('#header .bottom-bg').css("display","none");
        $('#header #site-title').css("margin-bottom","5px");
        $('#header #site-title').css("margin-top","-10px");
        $('#header #site-title').css("font-size","29px");
        $('#header .social-profile').css("display","none");
        $('#header #mainmenu').css("margin-top","0px");
        $('#header .logo-wrap').css("margin-top","20px");
        $('#header .social-search').css("margin-top","0px");
        $("#header .searchform").hide();
    }else{            
        $('#header').css('box-shadow','none');
        $('#header .top-bg').css("border-bottom","10px solid #00B1D7");
        $('#header .bottom-bg').css("display","block");
        $('#header #site-title').css("font-size","39px");
        $('#header #site-title').css("margin-top","0px");
        $('#header #site-title').css("margin-bottom","12px");
        $("#header #site-description").css("display","block");
        $('#header .social-profile').css("display","block");
        $('#header #mainmenu').css("margin-top","10px");
        $('#header .logo-wrap').css("margin-top","25px");
        $('#header .social-search').css("margin-top","10px");
        $("#header .searchform").fadeIn();
    }    });
$(document).ready(function(){
    $("#menu").click(function(){
        $("#mainmenu").slideToggle("fast");
    });
});

