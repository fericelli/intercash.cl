$(window).scroll(function(){
    if($("html").height()-$("html").scrollTop()<=687){
        registros()
    }
})
    
    
function registros(){
        $.ajax({
            url:"./../solicitud/php/intercambios/solicitudes.php",
            type: 'POST',
            data: {usuario:localStorage.getItem("usuario"),cantidad:$("tbody tr").length},
            beforeSend:function(){
                $(".imagencargasolicitud").css("display","flex");
            },
            complete:function(){
                $(".imagencargasolicitud").css("display","none");
                $(".eliminarsolicitud").on("click",function(){
                    index= $(".eliminarsolicitud").index(this);
                    registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
                    $.ajax({
                        url:"./../solicitud/php/intercambios/eliminar.php",
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
                            if(JSON.parse(data)[0]=="si"){
                                $("tbody tr:eq("+index+")").remove();
                            }
                            
                        }
                    })
                    
                })
            },
            success:function(data){
                html = "";
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td><td>";
                    
                    dato = JSON.parse(data)[i].datos.split(".");
    
                    for(j=0;j<dato.length;j++){
                        html += dato[j]+"<br>";
                    }
                    html += "</td>";
                    
                    if(JSON.parse(data)[i].estado=="pendiente"){
                        
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' ><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-borrar eliminarsolicitud' title='Cancelar'></div></td>"; 
                    
                    }else{
                        html += '<td><img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif" title="Procesando"></td>';
                        
                    }
                    if(localStorage.tipousuario == "administrador"){
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' imagenes='"+JSON.parse(data)[i].envios+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-bitcoin enviar' title='Cancelar'></div>";
                        
                        //html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos envios icono-photo'></div>";
                        
                        
                        
                        html += "</td>"; 
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' imagenes='"+JSON.parse(data)[i].pagos+"'>";   
                        html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px' class='iconos envios icono-photo'></div>";
                        html += "</td>"; 
                    }else{
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-dinero pagar' title='Cancelar'></div></td>";  
                    }
                    html += "</tr>";
                
                }
                
                $("tbody").html(html);
            }
        });
}

$(".eliminarsolicitud").on("click",function(){
        
        index= $(".eliminarsolicitud").index(this);
        registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
        console.log(index);
        $.ajax({
            url:"./../solicitud/php/intercambios/eliminar.php",
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
        })
        
    })

$(".enviar").on("click",function(){
        
        index= $(".enviar").index(this);
        registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
        monedadestino = $("tbody tr:eq("+index+") td:eq(4)").attr("monedadestino");
        cantidadrecibir = $("tbody tr:eq("+index+") td:eq(4)").attr("cantidadrecibir");
        usuario = $("tbody tr:eq("+index+") td:eq(4)").attr("usuario");
        
        html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Ingrese el screenshots</h4>';
        html += '<label class="margen" >Monto total '+cantidadrecibir+' '+monedadestino+'</label>';
        html += '<label class="margen">Monto pendiente 0 '+monedadestino+'</label>';
        html += '<label class="margen">Screenshots</label>';
        html += '<div class="contenidocapture" style="overflow:hidden"><div class="imegen" >';
        html += '<input class="controls monto" type="text"  placeholder="Catidad en '+monedadestino+'">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += "<label class='margen'><input class='imagen' name='imagen' type='file'></div>";
        html += '<label class="mensaje-error">Ingrese el screenshot</label>';
        html += '<div id="enviar" class="botons" registro="'+registro+'" moneda="'+monedadestino+'" usuario="'+usuario+'">Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';
        html += '</div>';

        html += "</section></div>";
        
        html += '<script type="text/javascript" src="./../solicitud/js/cargarpagos.js"></script>';
        $("#main-container").html(html)
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
$(".pagar").on("click",function(){
        
        index= $(".pagar").index(this);
        registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
        monedaorigen = $("tbody tr:eq("+index+") td:eq(4)").attr("monedaorigen");
        cantidadenviar = $("tbody tr:eq("+index+") td:eq(4)").attr("cantidadenviar");
        usuario = $("tbody tr:eq("+index+") td:eq(4)").attr("usuario");
        
        html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Ingrese el screenshots</h4>';
        html += '<label class="margen" >Monto total '+cantidadenviar+' '+monedaorigen+'</label>';
        html += '<label class="margen">Cuenta</label>';
       // html += '<input class="controls" type="text" name="cuenta" id="cuent" placeholder="Seleccione un país" list="cuenta">';
        html += '<img class="imgcarga imagencuenta" src="imagenes/carga.gif">';
        html += '<select class="controls" id="cuenta">';
        html += '</select>';
        html += '<label class="mensaje-error">Seleccione una cuenta</label>';
        html += '<label class="margen"></label>';
        html += "<div style='display:flex;flex-direction:column' id='vercuenta'></div>";
        html += '<label class="margen">Screenshots</label>';
        html += '<div class="contenidocapture" style="overflow:hidden"><div class="imegen" >';
        html += "<input class='imagen' name='imagen' type='file'></div>";
        html += '<label class="mensaje-error">Ingrese el screenshot</label>';
        html += '<div id="pagar" class="botons" registro="'+registro+'" moneda="'+monedaorigen+'" cantidadenviar="'+cantidadenviar+'" usuario="'+usuario+'">Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';
        html += '</div>';

        html += "</section></div>";
        
        $("#main-container").html(html);

        $.ajax({
            url:"./../solicitud/php/cuentas/pago.php",
            type: 'POST',
            data: {monedaorigen:monedaorigen},
            beforeSend:function(){
                $("#cuenta").css("display","none");
                $(".imagencuenta").css("display","flex");
            },
            complete:function(){
                $("#cuenta").css("display","flex");
                $(".imagencuenta").css("display","none");
                           
            },
            success:function(data){
                json = JSON.parse(data);
                html = "<option value='reset'>Seleccione un banco</option>";
                for(i = 0;i<JSON.parse(data).cuentas.length; i++){
                    html += '<option cuenta="'+json.cuentas[i].cuenta+'" banco="'+json.cuentas[i].banco+'" tipo="'+json.cuentas[i].tipo+'" nombres="'+json.cuentas[i].nombres+'"  identificacion="'+json.cuentas[i].identificacion+'">'+json.cuentas[i].banco+'</option>';
                }
                
                $("#cuenta").html(html);
                $("#main-container").append('<script type="text/javascript" src="./../solicitud/js/cargarpagos.js"></script>');
            }
        })
        
})

$(".envios").on("click",function(){
    index= $(".envios").index(this);
    registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
    monedaorigen = $("tbody tr:eq("+index+") td:eq(4)").attr("monedaorigen");
    cantidadenviar = $("tbody tr:eq("+index+") td:eq(4)").attr("cantidadenviar");
    usuario = $("tbody tr:eq("+index+") td:eq(4)").attr("usuario");
    
    imagenes = $("tbody tr:eq("+index+") td:eq(4)").attr("imagenes");
    console.log(imagenes);

    envios = imagenes.split(",");

    console.log(envios);
    var URLactual = window.location;
    
    var url = URLactual.href.replace("/sesion/", "")+"/imagenes/intercambios/";
    console.log(URLactual);
        
    html = '<div class="contenido-screeshot"><div class="icono-izquierda " style="color: #fff;font-size: 30px;left: 15px;top: 15px; cursor: pointer;"></div>';
    html += '<img class="principal" src="'+url+''+envios[0]+'" alt=""></img>';
    html += '<a class="icono-descargar" href="'+url+''+envios[0]+'" download style="color: #fff;font-size: 30px;right: 15px;top: 15px;cursor: pointer;"></a>';
    html += '<div class="imegenes-peque">';
    for(var i=0;i<envios.length;i++){
        html += '<img class="secundaria" src="'+url+''+envios[i]+'" alt=""></img>'
    }
    html += '</div></div>';
    
    html += '<script type="text/javascript" src="'+URLactual.href.replace("/sesion/", "")+'/js/screenshot.js"></script>';

    $("body").append(html);

})

$(".pagos").on("click",function(){
    index= $(".pagos").index(this);
    registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
    monedaorigen = $("tbody tr:eq("+index+") td:eq(5)").attr("monedaorigen");
    cantidadenviar = $("tbody tr:eq("+index+") td:eq(5)").attr("cantidadenviar");
    usuario = $("tbody tr:eq("+index+") td:eq(5)").attr("usuario");
    
    imagenes = $("tbody tr:eq("+index+") td:eq(5)").attr("imagenes");
    console.log(imagenes);

    pagos = imagenes.split(",");

    console.log(pagos);
    
    var URLactual = window.location;
    var url = URLactual.href.replace("/sesion/", "")+"/imagenes/intercambios/";

    html = '<div class="contenido-screeshot"><div class="icono-izquierda " style="color: #fff;font-size: 30px;left: 15px;top: 15px; cursor: pointer;"></div>';
    html += '<img class="principal" src="'+url+'/'+pagos[0]+'" alt=""></img>';
    html += '<a class="icono-descargar" href="'+url+'/'+pagos[0]+'" download style="color: #fff;font-size: 30px;right: 15px;top: 15px;cursor: pointer;"></a>';
    html += '<div class="imegenes-peque">';
    for(var i=0;i<pagos.length;i++){
        html += '<img class="secundaria" src="'+url+'/'+pagos[i]+'" alt=""></img>'
    }
    html += '</div></div>';
    url = URLactual.href.replace("/sesion/", "");
    html += '<script type="text/javascript" src="'+url+'/js/screenshot.js"></script>';
    $("body").append(html);

})

