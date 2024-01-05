


$("#gastar").on("click",function(){
    html = "<div style='margin:auto;width:100%;display:flex'>";
        html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
        html += '<section class="form-register"><h4>Realizar gasto</h4>';
       
        html += '<div style="overflow:hidden;display:flex;flex-direction:column">';
        html += '<label class="margen">Pais Gasto</label>';
        html += '<input class="controls" id="monedacomp" type="text"  placeholder="Seleccione una moneda" list="monedacompra">';
        html += '<datalist id="monedacompra"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen">Ingrese el monto</label>';
        html += '<input class="controls monto" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<div style="display:flex">';
        html += '<label class="margen">Modenda Pais</label>';
        html += '<input style="margin-left:10px" type="checkbox" id="miElementoCheckbox"></input>';
        html += '</div>';
        html += '<label class="margen ocultar">Moneda de intercambio</label>';
        html += '<input class="controls ocultar" id="monedainter" type="text"  placeholder="Seleccione una moneda" list="monedaintercambio">';
        html += '<datalist id="monedaintercambio"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen ocultar">Cantidad vendida</label>';
        html += '<input class="controls cantidadintercambio ocultar" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser númerico</label>';
        html += '<label class="margen">Descripción</label>';
        html += '<textarea class="controls descripcion"  placeholder="Especifique el gasto"></textarea>';
        html += '<label class="mensaje-error">Ingrese la descripcion de su gasto</label>';

        html += '<label class="margen">Comprobante</label>';
        html += "<input class='imagen controls' name='imagen' type='file'>";
        html += '<label class="mensaje-error">Ingrese los screenshot</label>';
        html += '<div id="gastar" class="botons" >Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        html += "</div>";
        
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

        html += "</section></div>";
        
        html += '<script type="text/javascript" src="./../js/gastar.js"></script>';
        $("#main-container").html(html);
})

$("#pagar").on("click",function(){
    html = "<div style='margin:auto;width:100%;display:flex'>";
    html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
    html += '<section class="form-register"><h4>Realizar pagos</h4>';
       
        html += '<div style="overflow:hidden;display:flex;flex-direction:column">';
        html += '<label class="margen">Socio</label>';
        html += '<input class="controls" id="soci" type="text"  placeholder="Seleccione una moneda" list="socio">';
        html += '<datalist id="socio"></datalist>';
        html += '<img class="imgcarga imagensocio" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione un socio</label>';
        html += '<div id="informacion"></div>';
        html += '<label class="margen">Ingrese el monto</label>';
        html += '<input class="controls monto" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<div style="display:flex">';
        html += '<label class="margen">Modenda Pais</label>';
        html += '<input style="margin-left:10px" type="checkbox" id="miElementoCheckbox"></input>';
        html += '</div>';
        html += '<label class="margen ocultar">Moneda de intercambio</label>';
        html += '<input class="controls ocultar" id="monedainter" type="text"  placeholder="Seleccione una moneda" list="monedaintercambio">';
        html += '<datalist id="monedaintercambio"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen ocultar">Cantidad vendida</label>';
        html += '<input class="controls cantidadintercambio ocultar" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser númerico</label>';

        html += '<label class="margen">Comprobante</label>';
        html += "<input class='imagen controls' name='imagen' type='file'>";
        html += '<label class="mensaje-error">Ingrese los screenshot</label>';
        html += '<div id="pagar" class="botons" >Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        html += "</div>";
        
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

        html += "</section></div>";
    
    html += '<script type="text/javascript" src="./../js/pagar.js"></script>';
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
        url:"./../php/debito/datos.php",
        type: 'POST',
        data: {fecha:fechabusqueda,usuario:localStorage.usuario},
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
            }
                               
            html += '<input type="date">'; 
            html += '<div id="gastar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Gastar</div></div>'
            html += '<div id="pagar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Pagar</div></div>'
            html += '</div>';
            html += "<div class='table-responsive'><h2>Debito</h2><table class='table table-striped table-sm'><thead style='text-align:center'><tr><th scope='col'>Moneda</th><th scope='col'>Dinero</th><th scope='col'>Tipo</th><th scope='col'>Informaion</th><th></th></tr></thead><tbody>";                
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i][2]+"</td>";
                                html += "<td>"+JSON.parse(data)[i][3]+"</td>";
                                if(JSON.parse(data)[i][1]=="gastos"){
                                    html += "<td><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-gastar'></div></td>";
                                }else{
                                    html += "<td><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-pagar'></div></td>";
                                }
                                   
                                html += "<td ><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-cartamenu informacion' tipo='"+JSON.parse(data)[i][1]+"' registro='"+JSON.parse(data)[i][0]+"' title='Informacion'></div></td>"; 
                                html += "<td ><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-borrar eliminar' tipo='"+JSON.parse(data)[i][1]+"' registro='"+JSON.parse(data)[i][0]+"' title='Eliminar'></div></td>"; 
                                
                                html += "</tr>";
                
                }
        
            html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../js/debito.js"></script>';
            $("#main-container").html(html);
            $("input[type=date]").val(fechabusqueda);
            $("#usuari").val(usuario);
        }
        
    });
    

})

$(".informacion").on("click",function(){
    console.log("awawdwda");
    html = "<div style='margin:auto;width:100%;display:flex'>";
    html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
    html += '<section class="form-register"><h4>Realizar gasto</h4>';
       
        html += '<div style="overflow:hidden;display:flex;flex-direction:column">';
        html += '<label class="margen">Socio</label>';
        html += '<input class="controls" id="soci" type="text"  placeholder="Seleccione una moneda" list="monedacompra">';
        html += '<datalist id="socio"></datalist>';
        html += '<img class="imgcarga imagensocio" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen">Ingrese el monto</label>';
        html += '<input class="controls monto" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser numerico</label>';
        html += '<div style="display:flex">';
        html += '<label class="margen">Modenda Pais</label>';
        html += '<input style="margin-left:10px" type="checkbox" id="miElementoCheckbox"></input>';
        html += '</div>';
        html += '<label class="margen ocultar">Moneda de intercambio</label>';
        html += '<input class="controls ocultar" id="monedainter" type="text"  placeholder="Seleccione una moneda" list="monedaintercambio">';
        html += '<datalist id="monedaintercambio"></datalist>';
        html += '<img class="imgcarga imagenmoneda" src="imagenes/carga.gif">';
        html += '<label class="mensaje-error">Seleccione una moneda</label>';
        html += '<label class="margen ocultar">Cantidad vendida</label>';
        html += '<input class="controls cantidadintercambio ocultar" type="text"  placeholder="Catidad">';
        html += '<label class="mensaje-error">Ingrese un monto</label>';
        html += '<label class="mensaje-error">Debe ser númerico</label>';
        html += '<label class="margen">Descripción</label>';
        html += '<textarea class="controls descripcion"  placeholder="Especifique el gasto"></textarea>';
        html += '<label class="mensaje-error">Ingrese la descripcion de su gasto</label>';

        html += '<label class="margen">Comprobante</label>';
        html += "<input class='imagen controls' name='imagen' type='file'>";
        html += '<label class="mensaje-error">Ingrese los screenshot</label>';
        html += '<div id="gastar" class="botons" >Ingresar</div>';
        html += '<img class="imgcarga imagensolicitud" src="imagenes/carga.gif">';
        html += "</div>";
        
        
        html += '<label class="mensaje-correcto mensajesolicitud"></label>';
        html += '<label class="mensaje-error mensajesolicitud"></label>';

        html += "</section></div>";
    
    html += '<script type="text/javascript" src="./../js/pagar.js"></script>';
    $("#main-container").html(html);
})

$(".eliminar").on("click",function(){
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
        url:"./../php/debito/eliminar.php",
        type: 'POST',
        data: {fecha:fechabusqueda,usuario:usuario,usuarioregistro:$(this).attr("usuario"),registro:$(this).attr("registro"),tipo:$(this).attr("tipo")},
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
            }
                               
            html += '<input type="date">'; 
            html += '<div id="gastar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Gastar</div></div>'
            html += '<div id="pagar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Pagar</div></div>'
            html += '</div>';
            html += "<div class='table-responsive'><h2>Debito</h2><table class='table table-striped table-sm'><thead style='text-align:center'><tr><th scope='col'>Moneda</th><th scope='col'>Dinero</th><th scope='col'>Tipo</th><th scope='col'>Informaion</th><th></th></tr></thead><tbody>";                
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i][2]+"</td>";
                                html += "<td>"+JSON.parse(data)[i][3]+"</td>";
                                if(JSON.parse(data)[i][1]=="gastos"){
                                    html += "<td><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-gastar'></div></td>";
                                }else{
                                    html += "<td><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-pagar'></div></td>";
                                }
                                   
                                html += "<td ><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-cartamenu informacion' tipo='"+JSON.parse(data)[i][1]+"' registro='"+JSON.parse(data)[i][0]+"' title='Informacion'></div></td>"; 
                                html += "<td ><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-borrar eliminar' tipo='"+JSON.parse(data)[i][1]+"' registro='"+JSON.parse(data)[i][0]+"' usuario='"+JSON.parse(data)[i][5]+"' title='Eliminar'></div></td>"; 
                                
                                html += "</tr>";
                
                }
        
            html += '</tbody></table></div><center><img class="imagencargasolicitud" style="display:none;width:30px;height:30px" src="../imagenes/carga.gif"></center><script src="./../js/debito.js"></script>';
            $("#main-container").html(html);
            $("input[type=date]").val(fechabusqueda);
            $("#usuari").val(usuario);
        }
        
    });
})