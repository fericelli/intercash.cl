var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");
var usuario = "";
if(typeof localStorage.usuario !== "undefined" ){
    if(localStorage.tipousuario=="administrador"){
        $.ajax({
            url:urlglobal+'usuarios/php/usuarios.php',
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
}

    
/*$(window).scroll(function(){
    if($("html").height()-$("html").scrollTop()<=687){
        registros()
    }
})*/

    
    
function registros(){
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
                        url:"./../solicitud/php/intercambios/solicitudes.php",
                        type: 'POST',
                        data: {usuario:usuario,tipodeusuario:localStorage.tipousuario},
                        beforeSend:function(){
                            $(".contenido-imagen").css("display","flex");
                        },
                        complete:function(){
                            $(".contenido-imagen").css("display","none");
                            $(".imagencargasolicitud").css("display","none");
                            
    
                        },
                        success:function(data){
    
                            html = "";
                            if(localStorage.tipousuario=="administrador"){
                                html += "<div class='barrafiltros'>";
                                html += '<input type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                                html += '<datalist id="usuario">';
                                html += '</datalist>';
                            
                            
                            
                                html += '</div>';
                                html += "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th><th scope='col'>Enviar</th><th scope='col'>Comprabantes</th></tr></thead><tbody>";
                            }
                            if(localStorage.tipousuario == "sociocomercial"){
                                html += "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th><th scope='col'>Comprabantes</th></tr></thead><tbody>";
                        
    
                            }
                            
                            for(i=0;i<JSON.parse(data).length;i++){
                                html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td><td>";
                                
                                dato = JSON.parse(data)[i].datos.split(","); 
    
                                for(j=0;j<dato.length;j++){
                                    html += dato[j]+"<br>";
                                }
                                html += "</td>";
                                
                                
                            if(JSON.parse(data)[i].estado=="pendiente"){
                                html += "<td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' class='iconos icono-borrar eliminarsolicitud' title='Cancelar'></div></td>"; 
                            }else{
                                html += '<td><img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif" title="Procesando"></td>';
                                
                            }
                            if(localStorage.tipousuario == "administrador"){
                                html += "<td ><div style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' pagado='"+JSON.parse(data)[i].pagado+"' class='iconos icono-bitcoin enviar' title='Enviar'></div>";
                                
                                //html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos envios icono-photo'></div>";
                                var imagen = "";
                                if(JSON.parse(data)[i].envios.length==0){
                                    imagen += "imagenes/imagennidisponible.jpg";
                                }
                                for(j=0;j<JSON.parse(data)[i].envios.length;j++){
                                    imagen += JSON.parse(data)[i].envios[j]+",";
                                }
                                html += "</td>"; 
                                html += "<td>";   
                                html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' imagenes='"+imagen+"' class='iconos envios icono-photo'></div>";
                                html += "</td>"; 
                            }
                            if(localStorage.tipousuario == "sociocomercial"){
                                var imagen = "";
                                if(JSON.parse(data)[i].envios.length==0){
                                    imagen += "imagenes/imagennidisponible.jpg";
                                }
                                for(j=0;j<JSON.parse(data)[i].envios.length;j++){
                                    imagen += JSON.parse(data)[i].envios[j]+",";
                                }
                                html += "</td>"; 
                                html += "<td>";   
                                html += "<div  registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' imagenes='"+imagen+"' style='cursor:pointer;margin: auto; width:30px;heigth:30px' class='iconos envios icono-photo'></div>";
                                html += "</td>"; 
                            }
                            html += "</tr>";
                            
                            }
                            html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/solicitudes.js"></script>';
                            
                            $("#main-container").html(html);
                            $("#usuari").val(usuario);
                        }
                    });
}

$(".eliminarsolicitud").on("click",function(){
        
        index= $(".eliminarsolicitud").index(this);
        registro = $(this).attr("registro");
        usuariosolicitud = $(this).attr("usuario");

        $.ajax({
            url:"./../solicitud/php/intercambios/eliminar.php",
            type: 'POST',
            data: {usuario:localStorage.getItem("usuario"),momento:registro,usuariosolicitud:usuariosolicitud},
            beforeSend:function(){
                $(".contenido-imagen").css("display","flex");},
            complete:function(){
                $(".contenido-imagen").css("display","none");
                           
            },
            success:function(data){
                registros();
            }
        })
        
    })

$(".enviar").on("click",function(){
        
        index= $(".enviar").index(this);
        registro = $(this).attr("registro");
        monedadestino = $(this).attr("monedadestino");
        cantidadrecibir = $(this).attr("cantidadrecibir");
        usuario = $(this).attr("usuario");
        pagado = $(this).attr("pagado");
        pendiente = parseFloat(cantidadrecibir)-parseFloat(pagado);
        
        html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Ingrese el screenshots</h4>';
        html += '<label class="margen" >Monto total '+cantidadrecibir+' '+monedadestino+'</label>';
        html += '<label class="margen">Monto pendiente '+pendiente+' '+monedadestino+'</label>';
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
        $("#main-container").html(html);
       /* $.ajax({
            url:"./../solicitud/php/intercambios/finalizar.php",
            type: 'POST',
            data: {usuario:localStorage.getItem("usuario"),momento:registro},
            beforeSend:function(){
                $("tbody tr:eq("+index+") td:eq(3) div").css("display","none");
                $("tbody tr:eq("+index+") td:eq(3)").append('<img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif">');
            },
            complete:function(){
                $("tbody tr:eq("+index+") td:eq(3) div").css("display","flex");
                $("tbody tr:eq("+index+") td:eq(3) img").remove();
                           
            },
            success:function(data){
                registros();
            }
        })*/
        
})


$(".envios").on("click",function(){
    index= $(".envios").index(this);
    registro = $(this).attr("registro");
    monedaorigen = $(this).attr("monedaorigen");
    cantidadenviar = $(this).attr("cantidadenviar");
    usuario = $(this).attr("usuario");
    if(localStorage.tipousuario=="administrador"){
        imagenes = $(this).attr("imagenes");
    }else{
        imagenes = $(this).attr("imagenes");
    }
    

    envios = imagenes.split(",");
    console.log(envios);
    
    var URLactual = window.location;
    
    var url = URLactual.href.replace("/sesion/", "");
    
    html = '<div class="contenido-screeshot"><div style="display:flex;flex-direction:column; margin-right:20px"><div class="icono-izquierda " style="color: #fff;font-size: 30px;left: 15px;top: 15px; cursor: pointer;"></div>';
    html += '<a class="icono-descargar" href="'+url+'/'+envios[0]+'" download style="color: #fff;font-size: 30px;right: 15px;top: 15px;cursor: pointer;text-decoration:none;"></a></div>';
    
    html += '<img class="principal" src="'+url+'/'+envios[0]+'" alt=""></img>';
    html += '<div class="imegenes-peque">';
    validar =0;
    for(var i=0;i<envios.length-1;i++){
        validar ++;
        html += '<img class="secundaria" src="'+url+'/'+envios[i]+'" alt=""></img>'
    }

    if(validar == 0){
        html += '<img class="secundaria" src="'+url+'/'+envios[0]+'" alt=""></img>' 
    }
    html += '</div></div>';
    
    html += '<script type="text/javascript" src="'+URLactual.href.replace("/sesion/", "")+'/js/screenshot.js"></script>';

    $("body").append(html);


})


