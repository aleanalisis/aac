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

// Funcion datetimepicker
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


