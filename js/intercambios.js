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

$(".comprobante").on("click",function(){
    index= $(".comprobante").index(this);
    
    
    imagenes = $(this).attr("imagenes");
    

    envios = imagenes.split(",");

    
    var URLactual = window.location;
    
    var url = URLactual.href.replace("/sesion/", "");
    
        
    html = '<div class="contenido-screeshot"><div class="icono-izquierda " style="color: #fff;font-size: 30px;left: 15px;top: 15px; cursor: pointer;"></div>';
    html += '<a href="'+url+'/'+envios[0]+'" download style="color: #fff;font-size: 30px;right: 15px;top: 15px;cursor: pointer;"><img class="principal" src="'+url+'/'+envios[0]+'" alt=""></img></a>';
    html += '';
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
            url:"./../php/intercambios/finalizados.php",
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
                html += '</div>';
                html += "<div class='table-responsive'><h2>Intercambios</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero enviado</th><th scope='col'>Dinero a recibir</th><th scope='col'>Comprobantes</th></tr></thead><tbody>";
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td>";
                    
                    var imagen = "";
                    if(JSON.parse(data)[i].imegen.length==0){
                        imagen += "imagenes/imagennidisponible.jpg";
                    }
                    for(j=0;j<JSON.parse(data)[i].imegen.length;j++){
                        imagen += JSON.parse(data)[i].imegen[j]+",";
                    }
                    html += "<td ><div style='cursor:pointer'  imagenes='"+imagen+"' class='iconos icono-photo comprobante' title='Descargar'></div></td>"; 
                    
                    html += "</tr>";
                
                }
                html += '</tbody></table></div><center><script src="./../js/intercambios.js"></script>';
                
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
            url:"./../php/intercambios/finalizados.php",
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
                    html += '<input type="text" name="usuario" value="'+usuario+'" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
                    html += '<datalist id="usuario">';
                    html += '</datalist>';
                }
                    
                html += '<input type="date">';   
                html += '</div>';
                html += "<div class='table-responsive'><h2>Intercambios</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Moneda comprada</th><th scope='col'>Contidad</th><th scope='col'>Tipo</th></tr></thead><tbody>";
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td>";
                    
                    var imagen = "";
                    if(JSON.parse(data)[i].imegen.length==0){
                        imagen += "imagenes/imagennidisponible.jpg";
                    }
                    for(j=0;j<JSON.parse(data)[i].imegen.length;j++){
                        imagen += JSON.parse(data)[i].imegen[j]+",";
                    }
                    html += "<td ><div style='cursor:pointer'  imagenes='"+imagen+"' class='iconos icono-photo comprobante' title='Descargar'></div></td>"; 
                    
                    html += "</tr>";
                
                }
                html += '</tbody></table></div><center><script src="./../js/intercambios.js"></script>';
                $("#main-container").html(html);
                $("input[type=date]").val(fechabusqueda);

            }
        });
})

