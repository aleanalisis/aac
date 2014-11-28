/**
 * Description of modal
 * @author ©-2014 Antonio Lorenzo Esparza 09-jul-2014
 
*/
jQuery(document).ready(function() {
    var jsVar = $("#aleruta").attr('name');
    //alert('jsvar ' + jsVar);
    $("a#eliminar").click(function(){
        var borrar =   $( this ).attr( 'borrar' );
        var pagina =   $( this ).attr( 'pagina' );
        var nombre =   $( this ).attr( 'name' );
        var ruta   =   $( this ).attr( 'ruta' );

        $('#eliminarUsuario').show(function () {
            var hrefCancel = $("#alecancel").attr('href');
            $("#alecancel").attr('href', hrefCancel);
            $("#paraBorrar").attr('href', jsVar + ruta + borrar);
            $("#alesonido").attr('autoplay', true);
            $('<h4>Con nombre: ' + nombre + '</h4>').appendTo(".modal-body");
        });
    });
    
});
