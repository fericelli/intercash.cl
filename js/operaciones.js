var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");
var usuario = "";
if(localStorage.tipousuario=="administrador"){
        $.ajax({
            url:urlglobal+'usuarios/php/operadores.php',
            beforeSend:function(){
                $(".contenedorcarga").css("display","flex");
            },
            complete:function(){
                $(".contenedorcarga").css("display","none");
            },
            success:function(respuesta){
                json = JSON.parse(respuesta);
                html = "";
                for(i=0;i<json.length;i++){
                    html += '<option usuario="'+json[i]+'" value="'+json[i]+'">'+json[i]+'</option>';
                }
                    //$("#paisodestino").html(html);
                    $("#usuario").html(html);
            }
        });
    }

$("#operar").on("click",function(){
        html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Realizar operacion</h4>';
        html += '<div class="imegen" >';
        html += '<div style="display:flex;justify-content:space-around"><div class="icono-compra" style="font-size:50px"><input type="radio" tipo="compra"></input></div><div class="icono-venta" style="font-size:50px;"><input type="radio"></input></div></div>';
        html += '<div class="contenedor-form" style="overflow:hidden">';
        html += '<label class="margen">Moneda</label>';
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
        html += '<input class="controls cantidadintercambio" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<label class="margen">Comprobante pagado</label>';
        html += "<input class='imagen' name='imagen' type='file'>";
        html += '<label class="margen">Comprobante recibido</label>';
        html += "<input class='imagen' name='imagen' type='file'>";
        html += '<label class="mensaje-error">Ingrese los screenshot</label>';
        html += '<div id="operar" class="botons" >Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        html += "</div>";
        html += '<div class="contenedor-form" style="overflow:hidden">';
        html += '<label class="margen">Tipo de venta</label>';
       // html += '<input class="controls" id="tipovent" type="text"  placeholder="Seleccione una moneda" list="tipoventa">';
        html += '<select id="tipoventa"><option opcion="envios" selected>Envíos</option><option opcion="gastos">Gastos</option><option opcion="pagos">Pagos</option></select>';
        html += '<label class="mensaje-error">Selecciona un tipo de envio</label>';
        html += "<div class='form-tipoventa'>";
        html += '<label class="margen">Pais Origen</label>';
        html += '<input class="controls" type="text" name="paisorigen" id="paisorige" placeholder="Seleccione un país" list="paisorigen">';
        html += '<datalist id="paisorigen">';
        html += '</datalist>';
        html += '<label class="mensaje-error mensajeerrorenvio">Seleccione un País</label>';
        html += '<label class="margen">Pais Destino</label>';
        html += '<input class="controls" type="text" name="paisodestino" id="paisodestin" placeholder="Seleccione un país" list="paisodestino">';
        html += '<datalist id="paisodestino">';
        html += '</datalist>';
        html += '<img class="imgcarga cargapaisdestino" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error mensajeerrorenvio" >Seleccione un País</label>';
        html += '<label class="margen">Cantidad a enviar</label>';
        html += '<input class="controls cantidad" type="text" name="cantidadenviar" id="cantidadenviar" placeholder="Ingrese la cantidad">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese un monto</label>';
        html += '<label class="margen">Cantidad a recibir</label>';
        html += '<input class="controls cantidad" type="text" name="cantidadrecibir" id="cantidadrecibir" placeholder="Ingrese la cantidad">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese un monto</label>';
        html += '<div style="display:flex;justify-content: space-around;">';
        html += '<img class="imgcarga imagenusd" src="imagenes/carga.gif">';
        html += '<p class="usd">0 USD</p>';
        html += '<p class="tasa">0 Tasa</p>';
        html += '</div>';
        html += '<label class="margen">Usuario o correo</label>';
        html += '<input class="controls"  type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
        html += '<datalist id="usuario">';
        html += '</datalist>';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese su usuario o correo</label>';
        html += '<label class="margen"><h3>Cuenta</h3></label>';
        html += '<label class="margen">Número o correo</label>';
        html += '<input class="controls  cuenta" type="text" name="cuenta" id="cuent" placeholder="Ingrese su Cuenta" list="cuenta">';
        html += '<datalist id="cuenta">';
        html += '</datalist>';
        html += '<img class="imgcarga cargacuenta" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese su numero o correo</label>';
        html += '<label class="margen">Banco</label>';
        html += '<input class="controls banco bloquecuen" type="text" name="banco" id="banc" placeholder="Ingrese su Banco" list="banco">';
        html += '<datalist id="banco">';
        html += '</datalist>';
        html += '<img class="imgcarga cargabancos" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese el banco</label>';
        html += '<label class="margen">Tipo de cuenta</label>';
        html += '<input class="controls tipodecuenta bloquecuen" type="text" name="tipodecuenta" id="tipodecuent" placeholder="Ingrese tipo de la Cuenta" list="tipodecuenta">';            html += '<datalist id="tipodecuenta">';
        html += '</datalist>';
        html += '<img class="imgcarga cargabancos" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese el tipo de cuenta</label>';
        html += '<label class="margen">Nombre y Apellido</label>';
        html += '<input class="controls nombres bloquecuen" type="text" name="nombres" id="nombres" placeholder="Ingrese nombres de la Cuenta">';
        html += '<label class="mensaje-error mensajeerrorenvio" >Ingrese el nombre y Apellido</label>';
        html += '<label class="margen">N° Identificación</label>';
        html += '<input class="controls identificacion bloquecuen" type="text" name="identificacion" id="identificacion" placeholder="Ingrese Identificación de la Cuenta">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese el número de identificacion</label>';
        html += '<label class="margen">Screenshots</label>';
        html += '<div class="contenidocapture" style="overflow:hidden"><div class="imegen" >';
        html += '<label class="margen">Moneda de intercambio</label>';
        html += '<input class="controls" id="monedainterenvio" type="text"  placeholder="Seleccione una moneda" list="monedaintercambioenvio">';
        html += '<datalist id="monedaintercambioenvio"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error mensajeerrorenvio">Seleccione una moneda</label>';
        html += '<label class="margen ocultatcantidad">Cantidad Vendida</label>';
        html += '<input class="controls cantidadvendidaenvio ocultatcantidad" type="text"  placeholder="Catidad vendida">';
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese un monto</label>';
        html += "<label class='margen'><input class='imagenenvio' name='imagen' type='file'></div>";
        html += '<label class="mensaje-error mensajeerrorenvio">Ingrese el screenshot</label>';
        html += '<div class="botons" id="enviar" >Enviar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

      
        
        html += "</div><div class='form-tipoventa'>aaaa";
        html += "</div><div class='form-tipoventa'>fff";
        html += "</div>";
        html += "</div>";
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

        html += "</section></div>";
        
        html += '<script type="text/javascript" src="./../js/operar.js"></script>';
        $("#main-container").html(html);
})

$("input[type=date]").focusout(function(){
        fecha = new Date();
        mes = (fecha.getMonth()+1).toString();
        if(mes.length==1){
            mes = "0"+mes;
        }
        dia = fecha.getDate().toString();
        if(dia.length==1){
            dia = "0"+dia;
        }
        
        
        if(fecha.getFullYear()+mes+dia>=$(this).val().split("-")[0]+$(this).val().split("-")[1]+$(this).val().split("-")[2]){
            fechabusqueda = $(this).val();
        }else{
            fechabusqueda = fecha.getFullYear() + "-" + mes + "-" + dia;;
        }
        var usuario = "";
        if(typeof localStorage.usuario !== "undefined" ){
            var usua = $('#usuario [value="' + $("#usuari").val() + '"]').val();
            if(typeof usua !== "undefined"){
                usuario = usua;
            }else{
                usuario = localStorage.usuario;
            }
                
        }else{
                usuario = localStorage.usuario;
        }
        $.ajax({
                url:"./../php/operaciones/datos.php",
                type: 'POST',
                data: {usuario:usuario,tipodeusuario:localStorage.tipousuario,fecha:fechabusqueda},
                beforeSend:function(){
                    $(".contenido-imagen").css("display","flex");
                },
                complete:function(){
                    $(".contenido-imagen").css("display","none");
                },
                success:function(data){
                    html = "<div class='barrafiltros'>";
                    if(localStorage.tipousuario=="administrador"){
                        html += '<input type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                        html += '<datalist id="usuario">';
                        html += '</datalist>';
                    }
                        
                    html += '<input type="date">';   
                    
                    html += '<div id="operar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Operar</div></div>'
                    
                    html += '</div>';
                    html += "<div class='table-responsive'><h2>Operaciones</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Cantidad</th><th scope='col'>Tipo</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].monto+"</td><td>";
                        if(JSON.parse(data)[i].operacion=="venta"){
                            html += "<div class='iconos icono-venta' style='font-size:35px;'></div>"; 
                        }else{
                            html += "<div class='iconos icono-compra' style='font-size:35px;'></div>"; 
                        }
                        html += "</td></tr>";
                    
                    }
                    html += '</tbody></table></div><center><script src="./../js/operaciones.js"></script>';
                    
                    $("#main-container").html(html);
                    $("#usuari").val(usuario);
                    $("input[type=date]").val(fechabusqueda);
                }
            });
        
    
})
$("#usuari").focusout(function(){
        fechabusqueda = $("input[type=date]").val();
        usuario = "";
        if(typeof localStorage.usuario !== "undefined" ){
            var usua = $('#usuario [value="' + $("#usuari").val() + '"]').val();
            if(typeof usua !== "undefined"){
                usuario = usua;
            }else{
                usuario = localStorage.usuario;
            }
                
        }else{
                usuario = localStorage.usuario;
        }
        
        $.ajax({
                url:"./../php/operaciones/datos.php",
                type: 'POST',
                data: {usuario:usuario,tipodeusuario:localStorage.tipousuario,fecha:fechabusqueda},
                beforeSend:function(){
                    $(".contenido-imagen").css("display","flex");
                },
                complete:function(){
                    $(".contenido-imagen").css("display","none");
                },
                success:function(data){
                    html = "<div class='barrafiltros'>";
                    if(localStorage.tipousuario=="administrador"){
                        html += '<input type="text" name="usuario" id="usuari" value="'+usuario+'" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                        html += '<datalist id="usuario">';
                        html += '</datalist>';
                    }
                        
                    html += '<input type="date">';   
                    
                    html += '<div id="operar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Operar</div></div>'
                    
                    html += '</div>';
                    html += "<div class='table-responsive'><h2>Operaciones</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Cantidad</th><th scope='col'>Tipo</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].monto+"</td><td>";
                        if(JSON.parse(data)[i].operacion=="venta"){
                            html += "<div class='iconos icono-venta' style='font-size:35px;'></div>"; 
                        }else{
                            html += "<div class='iconos icono-compra' style='font-size:35px;'></div>"; 
                        }
                        html += "</td></tr>";
                    
                    }
                    html += '</tbody></table></div><center><script src="./../js/operaciones.js"></script>';
                    
                    $("#main-container").html(html);
                    $("input[type=date]").val(fechabusqueda);
                }
            });
    })





