$("#operar").on("click",function(){
   
        
        html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Realizar operacion</h4>';
        html += '<div style="overflow:hidden" class="imegen" >';
        html += '<label class="margen">Moneda de Compra</label>';
        html += '<input class="controls" id="monedacomp" type="text"  placeholder="Seleccione una moneda" list="monedacompra">';
        html += '<datalist id="monedacompra"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen">Ingrese el monto</label>';
        html += '<input class="controls monto" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<label class="margen">Moneda de intercambio</label>';
        html += '<input class="controls" id="monedainter" type="text"  placeholder="Seleccione una moneda" list="monedaintercambio">';
        html += '<datalist id="monedaintercambio"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen">Cantidad comprada</label>';
        html += '<input class="controls monedacambiada" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<div style="display:flex;justify-content:space-around"><div class="icono-compra" style="font-size:50px"><input type="radio"></input></div><div class="icono-venta" style="font-size:50px"><input type="radio"></input></div></div>';
        html += "<label class='margen'><input class='imagen' name='imagen' type='file'></div>";
        html += '<label class="mensaje-error">Ingrese el screenshot</label>';
        html += '<div id="enviar" class="botons" >Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

        html += "</section></div>";
        
        html += '<script type="text/javascript" src="./../js/operar.js"></script>';
        $("#main-container").html(html);
})