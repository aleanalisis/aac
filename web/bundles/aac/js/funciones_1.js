/**
 * Description of modal
 * @author ©-2014 Antonio Lorenzo Esparza 09-jul-2014
 
*/
jQuery(document).ready(function() {
// Funcion Modal    
    var jsVar = $("#aleruta").attr('name');
   
    $("a#eliminar").click(function(){
        var borrar =   $( this ).attr( 'borrar' );
        var pagina =   $( this ).attr( 'pagina' );
        var nombre =   $( this ).attr( 'name' );
        var ruta   =   $( this ).attr( 'ruta' );
        
        $('#eliminarRegistro').show(function () {
            var hrefCancel = $("#alecancel").attr('href');
            $("#alecancel").attr('href', hrefCancel);
            $("#paraBorrar").attr('href', jsVar + ruta + borrar);
            $("#alesonido").attr('autoplay', true);
            $('<h4>Con nombre: ' + nombre + '</h4>').appendTo(".modal-body");
        });
    });
// Funcion de selección    
    $('#idseleccionar').click(function(){
        if ($('#seleccionado').is (':hidden')){
            $( "#seleccionadoInsertado" ).attr({
                'class' : 'glyphicon glyphicon-folder-close text-left'
            });
            $('#seleccionado').show();
        }else {
            $( "#seleccionadoInsertado" ).attr({
                'class' : 'glyphicon glyphicon-folder-open text-left'
            });
            $('#seleccionado').hide();
        }
    });
// Rotar el logo 
    
    setTimeout(iniciar, 8000);
    
    function iniciar(){
        var animationEvent = whichAnimationEvent();
        $('#rotar').addClass("iniciorotar");
        $('#rotar').on(animationEvent,
                        function(event) {
                $('#rotar').removeClass("iniciorotar");
                $('#rotar').off(animationEvent);
                //window.location.reload(true);
                setTimeout(iniciar, 8000);
        });
    }
    function whichAnimationEvent(){
        var t,
            el = document.createElement("fakeelement");

        var animations = {
          "animation"      : "animationend",
          "OAnimation"     : "oAnimationEnd",
          "MozAnimation"   : "animationend",
          "WebkitAnimation": "webkitAnimationEnd"
        }

        for (t in animations){
          if (el.style[t] !== undefined){
            return animations[t];
          }
        }
    }

//Funcion datetimepicker
    $('.form_datetime').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
        showMeridian: 1
    });
        $('.form_date').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
    });
        $('.form_time').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0
    });    
});


