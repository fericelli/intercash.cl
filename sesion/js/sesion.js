$(document).on("ready",function(){

    console.log(localStorage.getItem('usuario'));

    const btn = document.querySelector("#menu-btn");
    const menu = document.querySelector("#sidemenu");
    
    $(".item").eq(0).append('<div class="selectormenu"></div>');
    html = "";
    $.ajax({
        url:"./php/informacion/satoshis.php",
        type: 'POST',
        data: {usuario:localStorage.getItem("usuario")},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            html = "<div class='table-responsive'><h2>Inversion satoshis</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Satoshis Invertido</th><th scope='col'>Ganancia Satoshi</th><th scope='col'>Dinero Disponible</th><th scope='col'>Dinero Cambiado</th></tr></thead><tbody>";
            for(i=0;i<JSON.parse(data).length;i++){
                html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].invertidosatoshi+"</td><td>"+JSON.parse(data)[i].gananciasatoshis+"</td><td>"+JSON.parse(data)[i].dinerodisponoble+"</td><td>"+JSON.parse(data)[i].dineroenviado+"</td></tr>";
            }
            html += '</tbody></table></div>';
            $("#main-container").append(html);
        }
    });
    $.ajax({
        url:"./php/informacion/satoshis.php",
        type: 'POST',
        data: {usuario:localStorage.getItem("usuario")},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            html = "<div class='table-responsive'><h2>Inversion satoshis</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Satoshis Invertido</th><th scope='col'>Ganancia Satoshi</th><th scope='col'>Dinero Disponible</th><th scope='col'>Dinero Cambiado</th></tr></thead><tbody>";
            for(i=0;i<JSON.parse(data).length;i++){
                html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].invertidosatoshi+"</td><td>"+JSON.parse(data)[i].gananciasatoshis+"</td><td>"+JSON.parse(data)[i].dinerodisponoble+"</td><td>"+JSON.parse(data)[i].dineroenviado+"</td></tr>";
            }
            html += "</tbody></table></div>";
            
            $("#main-container").append(html);
        }
    });
    btn.addEventListener("click", e =>{
        menu.classList.toggle("menu-expanded");
        menu.classList.toggle("menu-collapsed");
        document.querySelector("body").classList.toggle("body-expanded")
    });
   // setTimeout(function(){
        
    //},2000)

    $(".item").on("click",function(){ 
        
        $(".selectormenu").remove();
        $(".item").eq($(".item").index(this)).append('<div class="selectormenu"></div>');
        opcion = $(".item").eq($(".item").index(this)).attr("opcion");
        
        $("#main-container").html("");
        html = "";
        if(opcion=="capital"){
            $.ajax({
                url:"./php/informacion/satoshis.php",
                type: 'POST',
                data: {usuario:localStorage.getItem("usuario")},
                beforeSend:function(){
                    $(".contenido-imagen").css("display","flex");
                },
                complete:function(){
                    $(".contenido-imagen").css("display","none");
                },
                success:function(data){
                    html = "<div class='table-responsive'><h2>Inversion satoshis</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Satoshis Invertido</th><th scope='col'>Ganancia Satoshi</th><th scope='col'>Dinero Disponible</th><th scope='col'>Dinero Cambiado</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].invertidosatoshi+"</td><td>"+JSON.parse(data)[i].gananciasatoshis+"</td><td>"+JSON.parse(data)[i].dinerodisponoble+"</td><td>"+JSON.parse(data)[i].dineroenviado+"</td></tr>";
                    }
                    html += "</tbody></table></div>";
                    $("#main-container").append(html);
                }
            });
            $.ajax({
                url:"./php/informacion/satoshis.php",
                type: 'POST',
                data: {usuario:localStorage.getItem("usuario")},
                beforeSend:function(){
                    $(".contenido-imagen").css("display","flex");
                },
                complete:function(){
                    $(".contenido-imagen").css("display","none");
                },
                success:function(data){
                    html = "<div class='table-responsive'><h2>Inversion satoshis</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda</th><th scope='col'>Satoshis Invertido</th><th scope='col'>Ganancia Satoshi</th><th scope='col'>Dinero Disponible</th><th scope='col'>Dinero Cambiado</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].moneda+"</td><td>"+JSON.parse(data)[i].invertidosatoshi+"</td><td>"+JSON.parse(data)[i].gananciasatoshis+"</td><td>"+JSON.parse(data)[i].dinerodisponoble+"</td><td>"+JSON.parse(data)[i].dineroenviado+"</td></tr>";
                    }
                    html += "</tbody></table></div>";
                    
                    $("#main-container").append(html);
                }
            });
        }
        if(opcion=="solicitud"){ 
            $(".contenido-imagen").css("display","flex");
            html = '<section class="form-register"><h4>Solicitar Intercambio</h4>';
            html += '<label class="margen">Pais Origen</label>';
            html += '<input class="controls" type="text" name="paisorigen" id="paisorige" placeholder="Seleccione un país" list="paisorigen">';
            html += '<datalist id="paisorigen">';
            html += '</datalist>';
            html += '<label class="mensaje-error">Seleccione un País</label>';
            html += '<label class="margen">Pais Destino</label>';
            html += '<input class="controls" type="text" name="paisodestino" id="paisodestin" placeholder="Seleccione un país" list="paisodestino">';
            html += '<datalist id="paisodestino">';
            html += '</datalist>';
            html += '<img class="imgcarga cargapaisdestino" src="imagenes/carga.gif">';
            html += '<label class="mensaje-error" >Seleccione un País</label>';
            html += '<label class="margen">Cantidad a enviar</label>';
            html += '<input class="controls" type="text" name="cantidadenviar" id="cantidadenviar" placeholder="Ingrese la cantidad">';
            html += '<label class="mensaje-error">Ingrese un monto</label>';
            html += '<label class="margen">Cantidad a recibir</label>';
            html += '<input class="controls" type="text" name="cantidadrecibir" id="cantidadrecibir" placeholder="Ingrese la cantidad">';
            html += '<label class="mensaje-error">Ingrese un monto</label>';
            html += '<div style="display:flex;justify-content: space-around;">';
            html += '<img class="imgcarga imagenusd" src="imagenes/carga.gif">';
            html += '<p class="usd">0 USD</p>';
            html += '<p class="tasa">0 Tasa</p>';
            html += '</div>';
            html += '<label class="margen">Usuario o correo</label>';
            html += '<input class="controls"  type="text" name="usuario" id="usuario" placeholder="Ingrese su Usuario o Correo">';
            html += '<label class="mensaje-error">Ingrese su usuario o correo</label>';
            html += '<label class="margen"><h3>Cuenta</h3></label>';
            html += '<label class="margen">Número o correo</label>';
            html += '<input class="controls  cuenta" type="text" name="cuenta" id="cuent" placeholder="Ingrese su Cuenta" list="cuenta">';
            html += '<datalist id="cuenta">';
            html += '</datalist>';
            html += '<img class="imgcarga cargacuenta" src="imagenes/carga.gif">';
            html += '<label class="mensaje-error">Ingrese su numero o correo</label>';
            html += '<label class="margen">Banco</label>';
            html += '<input class="controls banco bloquecuen" type="text" name="banco" id="banc" placeholder="Ingrese su Banco" list="banco">';
            html += '<datalist id="banco">';
            html += '</datalist>';
            html += '<img class="imgcarga cargabancos" src="imagenes/carga.gif">';
            html += '<label class="mensaje-error">Ingrese el banco</label>';
            html += '<label class="margen">Tipo de cuenta</label>';
            html += '<input class="controls tipodecuenta bloquecuen" type="text" name="tipodecuenta" id="tipodecuent" placeholder="Ingrese tipo de la Cuenta" list="tipodecuenta">';
            html += '<datalist id="tipodecuenta">';
            html += '</datalist>';
            html += '<img class="imgcarga cargabancos" src="imagenes/carga.gif">';
            html += '<label class="mensaje-error">Ingrese el tipo de cuenta</label>';
            html += '<label class="margen">Nombre y Apellido</label>';
            html += '<input class="controls nombres bloquecuen" type="text" name="nombres" id="nombres" placeholder="Ingrese nombres de la Cuenta">';
            html += '<label class="mensaje-error" >Ingrese el nombre y Apellido</label>';
            html += '<label class="margen">N° Identificación</label>';
            html += '<input class="controls identificacion bloquecuen" type="text" name="identificacion" id="identificacion" placeholder="Ingrese Identificación de la Cuenta">';
            html += '<label class="mensaje-error">Ingrese el número de identificacion</label>';
            html += '<div class="botons" >Solicitar</div>';
            html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
            html += '<label class="mensaje-correcto mensajesolicitud"></label>';
            html += '<label class="mensaje-error mensajesolicitud"></label>';
            html += '<p><a>Registrate</a></p>';
            html += '</section>';
            html += '<script type="text/javascript" src="./../solicitud/js/solicitar.js"></script>';
            $("#main-container").html(html);
            $(".contenido-imagen").css("display","none");
        }     
        if(opcion=="solicitudes"){   
            $.ajax({
                url:"./../solicitud/php/intercambios/solicitudes.php",
                type: 'POST',
                data: {usuario:localStorage.getItem("usuario")},
                beforeSend:function(){
                    $(".contenido-imagen").css("display","flex");
                },
                complete:function(){
                    $(".contenido-imagen").css("display","none");
                    $(".imagencargasolicitud").css("display","none");
                    

                },
                success:function(data){
                    html = "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th></tr></thead><tbody>";
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
                        html += "</tr>";
                    
                    }
                    html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/solicitudes.js"></script>';
                    
                    $("#main-container").html(html);
                }
            });
        }
        
        
        
        $(".contenido-imagen").css("display","none");
        
    })
});


//function 
