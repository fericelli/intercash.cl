$("#operar").on("click",function(){
    html = "<div style='margin:auto;width:100%;display:flex'>";
    html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
    html += '<section class="form-register"><h4>Ingrese la operación</h4>';
    html += '<label class="margen">Screenshots</label>';
    html += '<div class="contenidocapture" style="overflow:hidden"><div class="imegen" >';
    html += '<label class="margen">Ingrese el monto</label>';
    html += '<input class="controls monto" type="text"  placeholder="Catidad en '+monedadestino+'">';
    html += '<label class="mensaje-error">Ingrese un monto</label>';
    html += '<label class="mensaje-error">Debe ser numerico</label>';
    html += '<label class="margen">Moneda de intercambio</label>';
    html += '<input class="controls" id="monedainter" type="text"  placeholder="Seleccione una moneda" list="monedaintercambio">';
    html += '<datalist id="monedaintercambio"></datalist>';
    html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
    html += '<label class="mensaje-error">Seleccione una moneda</label>';
    html += '<label class="margen">Cantidad Vendida</label>';
    html += '<input class="controls monedacambiada" type="text"  placeholder="Catidad vendida">';
    html += '<label class="mensaje-error">Ingrese un monto</label>';
    html += '<label class="mensaje-error">Debe ser numerico</label>';
    html += "<label class='margen'><input class='imagen' name='imagen' type='file'></div>";
    html += '<label class="mensaje-error">Ingrese el screenshot</label>';
    html += '<div id="enviar" class="botons" registro="'+registro+'" moneda="'+monedadestino+'" usuario="'+usuario+'" pendiente="'+pendiente+'" total="'+cantidadrecibir+'" >Ingresar</div>';
    html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
    html += '<label class="mensaje-correcto mensajesolicitud"></label>';
    html += '<label class="mensaje-error mensajesolicitud"></label>';
    html += '</div>';
    html += "</section></div>";
    html += '<script type="text/javascript" src="./../js/enviar.js"></script>';
    $("#main-container").html(html)
})