$("#enviar").on("click",function(){
    validador = 0;
    if($(".monto").val()==""){
        validador ++;
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },3000)
    }
    
    if($.isNumeric($(".monto").val())===false){
        validador ++;
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },3000)
    }
    if($(".imagen").val()==""){
        validador ++;
        $(".mensaje-error").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(2).css("display","none");
        },3000)
    }
    total = parseFloat($("label").eq(0).text().split(" ")[2])-parseFloat($(".monto").val());

    if(validador==0){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        $.ajax({
            url:"./../php/envios/enviar.php?cantidad="+$(".monto").val()+"&usuario="+$(this).attr("usuario")+"&registro="+$(this).attr("registro")+"&pendiente="+$(this).attr("pendiente")+"&total="+$(this).attr("total"),
            type:'POST',
            contentType:false,
            data:archivos,
            processData:false,
            data:archivos,
            beforeSend:function(){
                $(".imagensolicitud").css("display","flex");
                $(this).css("display","none");
            },
            complete:function(){
                $(".imagensolicitud").css("display","none");
                $(this).css("display","flex");
            },
            error:function(){
                alert("Ocurrio un error con la conexion");
                $(".imagensolicitud").css("display","none");
                $(this).css("display","flex");
            },
            success:function(respuesta){
                console.log(JSON.parse(respuesta));
                
                if(JSON.parse(respuesta)[1]=="error"){
                    $(".mensajesolicitud").eq(1).css("display","flex");
                    $(".mensajesolicitud").eq(1).text(JSON.parse(respuesta)[0]);
                }else{
                    $(".mensajesolicitud").eq(0).css("display","flex");
                    $(".mensajesolicitud").eq(0).text(JSON.parse(respuesta)[0]);
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                    $("input").val("");
                },2000)
            }
        })
    }
    console.log(total);
    /*if($(".monto").val()!="" && $(".imagen").val()!=""){
        
    }*/
    /*var formulario = $(".form-register"); 
    var archivos = new FormData();
    for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
        archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
    }*/
})
$(".salir").on("click",function(){
    $.ajax({
        url:"./../solicitud/php/intercambios/solicitudes.php",
        type: 'POST',
        data: {usuario:localStorage.getItem("usuario"),tipodeusuario:localStorage.tipousuario},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
            $(".imagencargasolicitud").css("display","none");
            

        },
        success:function(data){
            html = "<div class='barrafiltros'>";
                    if(localStorage.tipousuario=="administrador"){
                        html += '<input type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                        html += '<datalist id="usuario">';
                        html += '</datalist>';
                        html += '<label class="mensaje-error">Ingrese su usuario o correo</label>';
                    }
                    
                    
                    html += '</div>';
                    html += "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th><th scope='col'>Enviar</th><th scope='col'>Comprabantes</th></tr></thead><tbody>";
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
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' pagado='"+JSON.parse(data)[i].pagado+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-bitcoin enviar' title='Cancelar'></div>";
                        
                        //html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos envios icono-photo'></div>";
                        var imagen = "";
                        if(JSON.parse(data)[i].envios.length==0){
                            imagen += "imagenes/imagennidisponible.jpg";
                        }
                        for(j=0;j<JSON.parse(data)[i].envios.length;j++){
                            imagen += "imagenes/intercambios/envios/"+JSON.parse(data)[i].envios[j];
                        }
                        html += "</td>"; 
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"' imagenes='"+imagen+"'>";   
                        html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px' class='iconos envios icono-photo'></div>";
                        html += "</td>";  
                    }else{
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-dinero pagar' title='Cancelar'></div></td>";  
                    }
                    html += "</tr>";
                    
                    }
                    html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/solicitudes.js"></script>';
                    
                    $("#main-container").html(html);
                    $("#usuari").val(usuario);
        }
    });
})

