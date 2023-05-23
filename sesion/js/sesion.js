$(document).on("ready",function(){
    
    var URLactual = window.location;
    
    var url = URLactual.href.replace("sesion/#", "")+"/imagenes/intercambios/";
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
   // setTimeout(function(){
        
    //},2000)

    $(".item").on("click",function(){ 
        $(".contenido-screeshot").remove();
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
            html += '<div class="bg-blue p-2">'; 
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
            html += '<p class="h8 textmuted"><input class="file" type="file"></p>';
            html += '<label class="mensaje-error mensajedeposito">Ingrese el comprobante</label>';
            
            html += '<label class="mensaje-correcto">Solicitud de deposito enviada</label>';
            html += '<button class="" style="border: none;margin-top:10px;padding:5px">Depositar</button></div>';
            html += '<div  class="bg-blue">';
            html += '<div style="" class="p-2">'; 
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Ingrese la cantidad</label><input style="width:45%" type="text" paceholder="Ingrese la cantatidad"></p>'; 
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Pais Destino</label><input style="width:45%" type="text" name="paisodestino" id="paisodestin" list="paisodestino"><datalist id="paisodestino"></datalist><img class="imgcarga cargapaisdestino" src="imagenes/carga.gif"><label class="mensaje-error" >Seleccione un País</label></p>';
           
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Cuenta</label><input style="width:45%" paceholder="Digite la cuenta" list="cuenta"><datalist id="cuenta"></datalist></p>'; 
            html += '<br><p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Banco</label><input style="width:45%" type="text"></p>';
            html += '<p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Tipo de cuenta</label><input style="width:45%" paceholder="tipo de cuenta" list="tipocuenta"><datalist id="tipocuenta"></datalist></p>';
            html += '<br><p class="h8 textmuted" style="display:flex;flex-direction:column"><label>Nombre y Apellido</label><input style="width:45%" type="text"></p>';
            html += '<br><p class="h8 textmuted" style="display:flex;flex-direction:column"><label>N° Identificacion</label><input style="width:45%" type="text"></p>';
            html += '<button style="border: none;margin-top:10px;padding:5px">Retirar</button></div>';
            
            html += '</div></div></div></div></div></div></div>';
                         
                     
                
            
            html += '<script type="text/javascript" src="./../js/cajero.js"></script>';
            $("#main-container").html(html);
            $(".contenido-imagen").css("display","none");
        }   
        if(opcion=="solicitudes"){   
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


                    html = "<div class='table-responsive'><h2>Solicitudes</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Cuenta</th><th scope='col'>Estado</th><th scope='col'>Enviar</th><th scope='col'>Comprabantes</th></tr></thead><tbody>";
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
                    html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../solicitud/js/solicitudes.js"></script>';
                    
                    $("#main-container").html(html);
                }
            });
        }
        if(opcion=="intercambios"){
            $.ajax({
                url:"./../php/intercambios/finalizados.php",
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
        
        
        
        $(".contenido-imagen").css("display","none");
        
    })
});


//function 
