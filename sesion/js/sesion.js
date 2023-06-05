$(document).on("ready",function(){
    
    var URLactual = window.location;
    
    
    if(localStorage.getItem('usuario')== null){
        
        setTimeout(function(){
            location.href = URLactual.href.replace("sesion/", "")
        },1000);
    }

    const btn = document.querySelector("#menu-btn");
    const menu = document.querySelector("#sidemenu");
    
    $(".item").eq(0).append('<div class="selectormenu"></div>');
    $(".item").css("display","none");
    if( localStorage.tipousuario=="administrador"){
        $(".item").css("display","");
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
    }
    

    
    btn.addEventListener("click", e =>{
        menu.classList.toggle("menu-expanded");
        menu.classList.toggle("menu-collapsed");
        document.querySelector("body").classList.toggle("body-expanded")
    });
   

    $(".item").on("click",function(){ 
        $(".contenido-screeshot").remove();
        $(".script").remove();
        
        $(".selectormenu").remove();
        $(".item").eq($(".item").index(this)).append('<div class="selectormenu"></div>');
        opcion = $(".item").eq($(".item").index(this)).attr("opcion");
        
        $("#main-container").html("");
        html = "";
        if(opcion=="capital"){
            $.ajax({
                url:"./php/informacion/satoshis.php",
                type: 'POST',
                data: {usuario:localStorage.getItem("usuario"),tipodeusuario:localStorage.tipousuario},
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
                data: {usuario:localStorage.getItem("usuario"),tipodeusuario:localStorage.tipousuario},
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
            html += '<input class="controls"  type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
            html += '<datalist id="usuario">';
            html += '</datalist>';
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
            html += '<p style="display:none"><a>Registrate</a></p>';
            html += '</section>';
            html += '<script type="text/javascript" src="./../solicitud/js/solicitar.js"></script>';
            $("#main-container").html(html);
            $(".contenido-imagen").css("display","none");
        } 
        if(opcion=="cajero"){
            
            $(".contenido-imagen").css("display","flex");
            html += "<div class='barrafiltros'>";
            if(localStorage.tipousuario=="administrador"){
                html += '<input type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                html += '<datalist id="usuario">';
                html += '</datalist>';
                html += '<label class="mensaje-error">Ingrese su usuario o correo</label>';
            }
            
            
            html += '</div>';
            html += '<div class="row m-0" style="justify-content:space-around">'; 
            html += '<div class="col-md-7 col-12">'; 
            html += '<div class="row">'; 
            html += '<div class="col-12 mb-4">'; 
            html += '<div class="row box-right">'; 
            html += '<div class="col-md-8 ps-0 ">'; 
            html += '<p class="ps-3 textmuted fw-bold h6 mb-0">TOTAL RECIEVED</p>'; 
            html += '<p class="h1 fw-bold d-flex">'; 
            html += '<span class=" fas fa-dollar-sign textmuted pe-1 h6 align-text-top mt-1"></span>84,254<span class="textmuted">.58</span>'; 
            html += '<select style="width:100px;font-size:20px"><option>-</option></select>'; 
            html += '</p>'; 
            html += '<p class="ms-3 px-2 bg-green">+10% since last month</p>'; 
            html += '</div>'; 
            html += '<div class="col-md-4">'; 
            html += '<p class="p-blue">'; 
            html += '<span class="fas fa-circle pe-2"></span>Pending'; 
            html += '</p>'; 
            html += '<p class="fw-bold mb-3">';
            html += '<span class="fas fa-dollar-sign pe-1"></span>1254 <span class="textmuted">.50</span>'; 
            html += '</p>'; 
            html += '<p class="p-org">';
            html += '<span class="fas fa-circle pe-2"></span>On drafts';
            html += '</p>';
            html += '<p class="fw-bold">';
            html += '<span class="fas fa-dollar-sign pe-1"></span>00<span class="textmuted">.00</span>';
            html += '</p>'; 
            html += '</div>'; 
            html += '<div class="col-12 px-0 mb-4">'; 
            html += '<div class="opciones" ><a >Depositar</a><a >Retirar</a></div>'; 
            html += '<form class="bg-blue p-2">'; 
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Ingrese la cantidad</label><input style="width:45%" type="text" id="cantidadeposito" paceholder="Ingrese la cantatidad"></p>'; 
            html += '<label class="mensaje-error mensajedeposito">Ingresa una cantidad</label>';
            html += '<label class="mensaje-error mensajedeposito">Debe ser numerica</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Cuenta</label><input style="width:45%" paceholder="Digite la cuenta" id="cuent" list="cuentas"><datalist id="cuentas"></datalist></p>'; 
            html += '<label class="mensaje-error mensajedeposito">Seleccione una cuenta</label>';
            html += '<div class="contenidocuenta" style="display:flex;flex-direction:column">';
            html += '<lable style="display:flex;flex-direction:row"><b>Banco : </b><p id="banco"></p></lable>';
            html += '<lable style="display:flex;flex-direction:row"><b>Cuenta : </b><p id="cuenta"></p></lable>';
            html += '<lable style="display:flex;flex-direction:row"><b>Tipo de cuenta : </b><p id="tipocuenta" ></p></lable>';
            html += '<lable style="display:flex;flex-direction:row"><b>Nombre : </b><p id="nombres"></p></lable>';
            html += '<lable style="display:flex;flex-direction:row"><b>Identificacion : </b><p id="identificacion"></p></lable>';
            html += '</div>'
            html += '<p class="h8 textmuted"><input class="file" name="file" type="file"></p>';
            html += '<label class="mensaje-error mensajedeposito">Ingrese el comprobante</label>';
            html += '<label class="mensaje-error error">Error al solicitar</label>';

            
            html += '<label class="mensaje-correcto enviado">Solicitud de deposito enviada</label>';
            html += '<button class="" style="border: none;margin-top:10px;padding:5px">Depositar</button><img class="imgcarga imagensolicitud" src="imagenes/carga.gif"></form>';
            html += '<form  class="bg-blue">';
            html += '<div style="" class="p-2">'; 
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Ingrese la cantidad</label><input id="cantidadenvio" style="width:45%" type="text" paceholder="Ingrese la cantatidad"></p>'; 
            html += '<label class="mensaje-error mensajeretiro">Ingrese una cantidad</label>';
            html += '<label class="mensaje-error mensajeretiro">La cantidad debe ser numerica</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Cuenta</label><input style="width:45%" paceholder="Digite la cuenta" id="cuentsenvios" list="cuentasenvio"><datalist id="cuentasenvio"></datalist></p>'; 
            html += '<label class="mensaje-error mensajeretiro">Ingrese la cuenta</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Banco</label><input id="bancoenvio" class="bloquecuen" style="width:45%" type="text"></p>';
            html += '<label class="mensaje-error mensajeretiro">Seleccione un banco</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Tipo de cuenta</label><input style="width:45%" class="bloquecuen" paceholder="tipo de cuenta" id="ticuenenvio" list="tipocuentaenvio"><datalist id="tipocuentaenvio"></datalist></p>';
            html += '<label class="mensaje-error mensajeretiro">Seleccione un tipo de cuenta</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Nombre y Apellido</label><input id="nombreenvio" class="bloquecuen" style="width:45%" type="text"></p>';
            html += '<label class="mensaje-error mensajeretiro">Ingrese los nombre</label>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>N° Identificacion</label><input id="identificacionenvio" class="bloquecuen" style="width:45%" type="text"></p>';
            html += '<label class="mensaje-error mensajeretiro">Ingrese la identificacion</label>';
            
            html += '<label class="mensaje-error retiro errorretiro">Error al solicitar</label>';
            html += '<label class="mensaje-correcto retiro enviadoretiro">Solicitud de retiro enviada</label>';
            html += '<button style="border: none;margin-top:10px;padding:5px">Retirar</button><img class="imgcarga imagensolicitud" src="imagenes/carga.gif"></form>';
            
            html += '</div></div></div></div></div></div></div>';
                         
                     
                
            
            html += '<script type="text/javascript" src="./../js/cajero.js"></script>';
            $("#main-container").html(html);
            $(".contenido-imagen").css("display","none");
        }   
        if(opcion=="solicitudes"){   
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

                    html += "<div class='barrafiltros'>";
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
        }
        if(opcion=="intercambios"){
            $.ajax({
                url:"./../solicitud/php/intercambios/finalizados.php",
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
                    html = "<div class='table-responsive'><h2>Intercambios</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Imagen</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td>";
                        
                        html += "<td registro='"+JSON.parse(data)[i].momento+"' ><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-descargar comprobante' title='Descargar'></div></td>"; 
                        
                        html += "</tr>";
                    
                    }
                    html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/finalizadas.js"></script>';
                    
                    $("#main-container").html(html);
                }
            });
        }
        if(opcion=="depositos"){
            var url = URLactual.href.replace("sesion/#", "");
            url = URLactual.href.replace("sesion/", "");


            $.ajax({
                url:"./../php/depositos/datos.php",
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
                    html += "<div class='table-responsive'><h2>Despositos</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero</th><th scope='col'>Cuenta</th><th scope='col'>Imagen</th><th scope='col'>Confirmar</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                        html += "<tr><td>"+JSON.parse(data)[i].cantidad+" "+JSON.parse(data)[i].moneda+"</td><td>";
                        html += "<label style='display:block'>"+JSON.parse(data)[i].banco+"</label><label style='display:block'>"+JSON.parse(data)[i].tipodecuenta+" "+JSON.parse(data)[i].cuenta+"</label><label style='display:block'>"+JSON.parse(data)[i].nombre+"</label><label style='display:block'>"+JSON.parse(data)[i].identificacion+"</label>";
                        html += "</td><td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-descargar comprobante' imagen='"+url+JSON.parse(data)[i].directorio+"' title='Descargar'></div></td><td >";
                        if(JSON.parse(data)[i].estado!="1"){
                            html += "<div style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' usuariocuenta='"+JSON.parse(data)[i].usuariocuenta+"' class='iconos icono-bien confirmar' title='Confirmar'></div>"; 
                        }
                        
                        html += "</td></tr>";
                    
                    }
                    html += '</tbody></table><script class="script" src="./../js/depositos.js"></script></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center>';
                    
                    $("#main-container").html(html);
                }
            });
        }
        if(opcion=="pagos"){
            var url = URLactual.href.replace("sesion/#", "");
            url = URLactual.href.replace("sesion/", "");
            $.ajax({
                url:"./../php/pagos/datos.php",
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
                    console.log(JSON.parse(data));

                    html = "<div class='table-responsive'><h2>Pagos</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero</th><th scope='col'>Cuenta</th><th scope='col'>Pagar</th><th scope='col'>Imagenes</th></tr></thead><tbody>";
                    for(i=0;i<JSON.parse(data).length;i++){
                       html += "<tr><td>"+JSON.parse(data)[i].cantidad+" "+JSON.parse(data)[i].moneda+"</td><td>";
                       html += "<label style='display:block'>"+JSON.parse(data)[i].banco+"</label><label style='display:block'>"+JSON.parse(data)[i].tipodecuenta+" "+JSON.parse(data)[i].cuenta+"</label><label style='display:block'>"+JSON.parse(data)[i].nombre+"</label><label style='display:block'>"+JSON.parse(data)[i].identificacion+"</label>";
                       html += "</td><td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-dinero pagar' registro='"+JSON.parse(data)[i].momento+"' title='Pagar'></div></td>";
                        
                       html += "<td ><img src='../imagenes/carga.gif' class='cargaimga' style='display:none;margin: auto; width:30px;heigth:30px'><div style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' class='iconos icono-bien imagenes' title='Confirmar'></div></td>"; 
                        
                        html += "</tr>";
                    
                    }
                    html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../js/depositos.js"></script>';
                    
                    $("#main-container").html(html);
                }
            });
        }
        
        $(".contenido-imagen").css("display","none");
        
    })
});


//function 
