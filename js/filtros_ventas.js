$().ready(function() {
    $('#productos').multiselect({multiple: true, height: 140, minWidth: 185, noneSelectedText: '--Producto--'});
    $('#regionales').multiselect({multiple: true, height: 120, minWidth: 185, noneSelectedText: '--Regional--'});
    $('#plazas').multiselect({multiple: true, height: 120, minWidth: 185, noneSelectedText: '--Plaza--'});
    $('#sub_productos').multiselect({height: 140, minWidth: 185, noneSelectedText: '--Sub Producto--'});
    $('#uen').multiselect({height: 140, minWidth: 185, noneSelectedText: '--UEN--'});
    $('#cbo_periodo').multiselect({multiple: false, header: false, height: 140, minWidth: 20, selectedList: 0, noneSelectedText: '--Periodo--'});
    $('#meses').multiselect({multiple: false, header: false, height: 140, minWidth: 20, selectedList: 0, noneSelectedText: '--Mes--'});
    $('#anios').multiselect({multiple: false, header: false, height: 140, minWidth: 20, selectedList: 0, noneSelectedText: '--Año--'});
    $('#tipo_canal').multiselect({multiple: true, header: false, height: 140, minWidth: 185, selectedList: 0, noneSelectedText: '--Tipo Canal--'});

    $('#productos').live('change', function() {
        actualizarSelectProductos("productos", "sub_productos");
    });

    $('#regionales').live('change', function() {
        actualizarSelectRegionales("regionales", "plazas");
    });

    $("body").on({
        ajaxStart: function() {
            $(this).addClass("loading");
        },
        ajaxStop: function() {
            $(this).removeClass("loading");
        }
    });
    $("select").multiselect();
})

/// Abre el detalle por  plaza
function detallePlaza(plaza, cumplimiento, uen, producto, consultaProducto, fechaConsulta)
{
    // Esta fecha es construida desde el actionIndex del SiteController.
    var fecha = new Date(fechaConsulta);
    mes = (fecha.getMonth() + 1);

    // para ie
    if (isNaN(mes)) {
        str = fechaConsulta;
        str = str.split('-');
        var date = new Date();
        mes = date.getMonth(str[0], str[1] - 1, str[2]);
    }

    jQuery.ajax({'type': 'POST', 'url': "/vcp_nuevosmercados/index.php?r=Site/DetallesPlaza", 'data': {'plaza': plaza, 'cumplimiento': cumplimiento, 'uen': uen, 'producto': producto, 'consultaProducto': consultaProducto, 'fechaConsulta': fechaConsulta, 'mes': mes}, 'success': function(data) {
        $('#modalDetallesPlaza').html(data);
    }, 'cache': false});
}

// Actualiza el cbo de sub productos segun el subproducto seleccionado
function actualizarSelectProductos(idSelectOrigen, idSelecDetino)
{
    $.get("/vcp_nuevosmercados/index.php?r=Site/cargarSubProductos", {producto: $('#productos').val(), ajax: 'true'}, function(j) {
        $('#' + idSelecDetino).multiselect("destroy");
        $("select#" + idSelecDetino).empty();
        $("select#" + idSelecDetino).append(j);
        $('#' + idSelecDetino).multiselect({height: 140, minWidth: 185, noneSelectedText: '--Sub Producto--'});
        if ($(idSelectOrigen).val() != "") {
            $('#' + idSelecDetino).multiselect("enable");
            $('#uen').multiselect("enable");
            $('#btnDetallesVentas').attr("disabled", false);
        }
        else {
            $('#sub_productos').attr("disabled", true);
            $('#cbo_periodo').attr("disabled", true);
            $('#uen').attr("disabled", true);
            $('#btnDetallesVentas').attr("disabled", true);
        }
    })
}

// Actualiza el cbo de plazas segun la regional seleccionada
function actualizarSelectRegionales(idSelectOrigen, idSelecDetino)
{
    $.get("/vcp_nuevosmercados/index.php?r=Site/CargarPlazas", {regional: $('#regionales').val(), ajax: 'true'}, function(j) {
        $('#' + idSelecDetino).multiselect("destroy");
        $("select#" + idSelecDetino).empty();
        $("select#" + idSelecDetino).append(j);
        $('#' + idSelecDetino).multiselect({height: 140, minWidth: 185, noneSelectedText: '--Plazas--'});
    })
}
