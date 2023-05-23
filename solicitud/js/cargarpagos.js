$("#enviar").on("click",function(){
        if($(".monto").val()==""){
            $(".mensaje-error").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(0).css("display","none");
            },3000)
        }
        if($(".imagen").val()==""){
            $(".mensaje-error").eq(1).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(1).css("display","none");
            },3000)
        }
        total = parseFloat($("label").eq(0).text().split(" ")[2])-parseFloat($(".monto").val());

        
        if($(".monto").val()!="" && $(".imagen").val()!=""){
            var formulario = $(".form-register"); 
            var archivos = new FormData();
            for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
                archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
            }
            $.ajax({
                url:"./../solicitud/php/intercambios/pagar.php?cantidad="+$(".monto").val()+"&usuario="+$(this).attr("usuario")+"&registro="+$(this).attr("registro"),
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
                        $("#main-container label").eq(1).text("Monto pendiente "+total+" "+$("label").eq(0).text().split(" ")[3])
                    },2000)
                }
            })
        }
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
            html = "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th><th scope='col'>Enviar</th><th scope='col'>Pagar</th></tr></thead><tbody>";
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
                    html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-bitcoin enviar' title='Cancelar'></div>";
                    html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px' class='icono-photo'></div>";
                        
                    
                    
                    html += "</td>"; 
                    html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-dinero pagar' title='Cancelar'></div>";   
                    html += "<div  style='cursor:pointer;margin: auto; width:30px;heigth:30px' class='icono-photo'></div>";
                        
                    html += "</td>"; 
                }else{
                    html += "<td registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' cantidadrecibir='"+JSON.parse(data)[i].cantidadrecibir+"' cantidadenviar='"+JSON.parse(data)[i].cantidadenviar+"' monedaorigen='"+JSON.parse(data)[i].monedaorigen+"' monedadestino='"+JSON.parse(data)[i].monedadestino+"'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-dinero pagar' title='Cancelar'></div></td>";  
                }
                html += "</tr>";
            
            }
            html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/solicitudes.js"></script>';
            
            $("#main-container").html(html);
        }
    });
})

$("#cuenta").on("change",function(){
    if(typeof $("#cuenta option:selected").attr("banco") === "undefined"){
        $("#vercuenta").html("");
    }else{
        console.log($("#cuenta option:selected").attr("banco"));
        console.log($("#cuenta option:selected").attr("cuenta"));
        console.log($("#cuenta option:selected").attr("tipo"));
        console.log($("#cuenta option:selected").attr("nombres"));
        console.log($("#cuenta option:selected").attr("identificacion"));

        html = "<div>"+$("#cuenta option:selected").attr("banco")+"</div>";
        html += "<div>"+$("#cuenta option:selected").attr("tipo")+"  "+$("#cuenta option:selected").attr("cuenta")+"</div>";
        html += "<div>"+$("#cuenta option:selected").attr("nombres")+"</div>";
        html += "<div>"+$("#cuenta option:selected").attr("identificacion")+"</div>";

        $("#vercuenta").html(html);
    }
})


$("#pagar").on("click",function(){
    if(typeof $("#cuenta option:selected").attr("banco") === "undefined"){
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },3000)
    }
    if($(".imagen").val()==""){
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },3000)
    }
    
    if(typeof $("#cuenta option:selected").attr("banco") !== "undefined" && $(".imagen").val()!=""){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        $.ajax({
            url:"./../solicitud/php/intercambios/pagar.php?usuario="+$(this).attr("usuario")+"&registro="+$(this).attr("registro")+"&banco="+$("#cuenta option:selected").attr("banco")+"&cuenta="+$("#cuenta option:selected").attr("banco")+"&identificacion="+$("#cuenta option:selected").attr("identificacion")+"&cantidad="+$(this).attr("cantidadenviar"),
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
                if(JSON.parse(respuesta)[1]=="error"){
                    $(".mensajesolicitud").eq(1).css("display","flex");
                    $(".mensajesolicitud").eq(1).text(JSON.parse(respuesta)[0]);
                }else{
                    $(".mensajesolicitud").eq(0).css("display","flex");
                    $(".mensajesolicitud").eq(0).text(JSON.parse(respuesta)[0]);
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                    $("#cuenta option[value=reset]").attr("selected",'selected');
                    $(".imagen").val("");
                    $("#vercuenta").html("");
                    //$("#main-container label").eq(1).text("Monto pendiente "+total+" "+$("label").eq(0).text().split(" ")[3])
                },2000)
            }
        })
    }
    
})