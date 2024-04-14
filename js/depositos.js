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
    $("#usuari").val(localStorage.usuario);
    
}
function datos(){
    if(localStorage.tipousuario=="administrador"){
        html += '<input type="text" name="usuario" id="usuari" list="usuario" placeholder="Ingrese su Usuario o Correo">';
        html += '<datalist id="usuario">';
        html += '</datalist>';
        html += '<label class="mensaje-error">Ingrese su usuario o correo</label>';
    }
    var URLactual = window.location;
    var url = URLactual.href.replace("sesion/#", "");
    urll = url.replace("sesion/", "");
    $.ajax({
        url:"./../php/depositos/datos.php",
        type: 'POST',
        data: {usuario:localStorage.getItem("usuario"),tipodeusuario:localStorage.tipousuario},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            html = "<h2>Despositos</h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Dinero</th><th scope='col'>Cuenta</th><th scope='col'>Imagen</th><th scope='col'>Confirmar</th><th scope='col'>Eliminar</th></tr></thead><tbody>";
            for(i=0;i<JSON.parse(data).length;i++){
                html += "<tr><td>"+JSON.parse(data)[i].cantidad+" "+JSON.parse(data)[i].moneda+"</td>";
                html += "<td><label style='display:block'>"+JSON.parse(data)[i].banco+"</label><label style='display:block'>"+JSON.parse(data)[i].tipodecuenta+" "+JSON.parse(data)[i].cuenta+"</label><label style='display:block'>"+JSON.parse(data)[i].nombre+"</label><label style='display:block'>"+JSON.parse(data)[i].identificacion+"</label></td>";
                html += "<td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px'  class='iconos icono-descargar comprobante' imagen='"+urll+JSON.parse(data)[i].directorio+"' title='Descargar'></div></td>";
                html += "<td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' usuariocuenta='"+JSON.parse(data)[i].usuariocuenta+"' class='iconos icono-bien confirmar' title='Confirmar'></div></td>"; 
                html += "<td><div style='cursor:pointer;margin: auto; width:30px;heigth:30px' registro='"+JSON.parse(data)[i].momento+"' usuario='"+JSON.parse(data)[i].usuario+"' usuariocuenta='"+JSON.parse(data)[i].usuariocuenta+"' class='iconos icono-borrar eliminar' title='Eliminar'></div></td>"; 
                html += "</tr>";
                    
            }
            html += '</tbody></table><script src="./../js/depositos.js"></script>';
                    
            $(".table-responsive").html(html);
        }
    });
}

$(".comprobante").on("click",function(){
    window.open($(this).attr("imagen"), '_blank'); 
})

$(".confirmar").on("click",function(){
    $.ajax({
        url:"./../php/depositos/confirmar.php",
        type: 'POST',
        data: {usuario:$(this).attr("usuario"),usuariocuenta:$(this).attr("usuariocuenta"),tipousuario:localStorage.tipousuario,registro:$(this).attr("registro")},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
            $(this).css("display","none");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
            $(this).css("display","flex");
            
        },
        success:function(respuesta){
            json = JSON.parse(respuesta);
            if(json[1]=="correcto"){
                datos(); 
            } 
        }
    })
})
$(".eliminar").on("click",function(){
    $.ajax({
        url:"./../php/depositos/eliminar.php",
        type: 'POST',
        data: {usuario:$(this).attr("usuario"),usuariocuenta:$(this).attr("usuariocuenta"),tipousuario:localStorage.tipousuario,registro:$(this).attr("registro")},
        beforeSend:function(){
            $(this).css("display","none");
            
        },
        complete:function(){
            $(this).css("display","flex");
            
        },
        success:function(respuesta){
            
            json = JSON.parse(respuesta);
            if(json[0]=="success"){
                datos(); 
            }
            
        }
    })
})

