var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");
function usauriopagos(){
    $.ajax({
        url:"../php/usuarios/usuariospagos.php",
        type:"POST",
        data:{},
        beforeSend:function(){
            $(".imagensocio").css("display","flex");
            $("#soci").css("display","none");
        },
        complete:function(){
            $(".imagensocio").css("display","none");
            $("#soci").css("display","flex");
        },
        error:function(){
            alert("Ocurrio un error con la conexion");
            $(".imagensocio").css("display","none");
            $("#soci").css("display","flex");
        },
        success:function(data){
            console.log(JSON.parse(data));
            html = "";
            for(i=0;i<JSON.parse(data).length;i++){
                html += '<option momento="'+JSON.parse(data)[i].momento+'" decimal="'+JSON.parse(data)[i].decimales+'" value="'+JSON.parse(data)[i].nombreusuario+' - '+JSON.parse(data)[i].pendiente+' '+JSON.parse(data)[i].moneda+'" moneda="'+JSON.parse(data)[i].moneda+'" pendiente="'+JSON.parse(data)[i].pendiente+'" pais="'+JSON.parse(data)[i].pais+'" usuario="'+JSON.parse(data)[i].usuario+'" banco="'+JSON.parse(data)[i].cuenta[1]+'" tipocuenta="'+JSON.parse(data)[i].cuenta[2]+'" titular="'+JSON.parse(data)[i].cuenta[3]+'" identificacion="'+JSON.parse(data)[i].cuenta[4]+'" cuenta="'+JSON.parse(data)[i].cuenta[0]+'">'+JSON.parse(data)[i].nombrepais+'</option>';
            }
            
            $("#socio").html(html);
            
        }
    })
}
usauriopagos();

$(".salir").on("click",function(){
    for(i=0;i<$(".item").length;i++){
        if($(".item").eq(i).attr("opcion")=="debitos"){
            $(".item").eq(i).trigger("click");
        }
    }
})

$("#soci").on("change",function(){
    html = "";
    var banco = $('#socio [value="' + $("#soci").val() + '"]').attr('banco');
    var cuenta = $('#socio [value="' + $("#soci").val() + '"]').attr('cuenta');
    var tipodecuenta = $('#socio [value="' + $("#soci").val() + '"]').attr('tipocuenta');
    var titular = $('#socio [value="' + $("#soci").val() + '"]').attr('titular');
    var identificacion = $('#socio [value="' + $("#soci").val() + '"]').attr('identificacion');
    
    if(typeof banco !== "undefined"){
        html += "<div>Banco : "+banco+"</div>";
        html += "<div>Cuenta : "+cuenta+"</div>";
        html += "<div>Tipo : "+tipodecuenta+"</div>";
        html += "<div>Nombre : "+titular+"</div>";
        html += "<div>Identificacion : "+identificacion+"</div>";
    }
    
    $("#informacion").html(html);
})
$.ajax({
    url:"../php/monedas/monedaenvios.php",
    type:"POST",
    data:{registro:$("#enviar").attr("registro"),usuario:$("#enviar").attr("usuario")},
    beforeSend:function(){
        $(".imagenmoneda").css("display","flex");
        $("#monedainter").css("display","none");
    },
    complete:function(){
        $(".imagenmoneda").css("display","none");
        $("#monedainter").css("display","flex");
    },
    error:function(){
        alert("Ocurrio un error con la conexion");
        $(".imagenmoneda").css("display","none");
        $("#monedainter").css("display","flex");
    },
    success:function(data){
        html = "";
        for(i=0;i<JSON.parse(data)[0].length;i++){
            html += '<option moneda="'+JSON.parse(data)[0][i].moneda+'" decimal="'+JSON.parse(data)[0][i].decimales+'" value="'+JSON.parse(data)[0][i].nombre+'">'+JSON.parse(data)[0][i].nombre+'</option>';
        }
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += '<option moneda="'+JSON.parse(data)[1][i].moneda+'" decimal="'+JSON.parse(data)[1][i].decimales+'" value="'+JSON.parse(data)[1][i].nombre+'">'+JSON.parse(data)[1][i].nombre+'</option>';
        }
        $("#monedaintercambio").html(html);
        
    }
})

$("#miElementoCheckbox").on("click",function(){
    
    if($(this).context.checked){
        $(".ocultar").css("display","none");
        $("#monedainter").val("");
        $(".cantidadintercambio").val("");
         
    }else{
        $(".ocultar").css("display","");

    }
})

$("#socio").focusin(function(){
    $("input").val("");
})


$(".monto").keyup(function(){
    if(($.isNumeric($(this).val())===false && $(this).val()!="") || $(this).val()==0){
        $(this).val("");
        $(".mensaje-error").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(2).css("display","none");
        },3000)
    }
})

$(".cantidadintercambio").keyup(function(){
    if(($.isNumeric($(this).val())===false && $(this).val()!="") || $(this).val()==0){
        $(this).val("");
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },3000)
    }
})
$(".salir").on("click",function(){
    for(i=0;i<$(".item").length;i++){
        if($(".item").eq(i).attr("opcion")=="debitos"){
            $(".item").eq(i).trigger("click");
        }
    }
})

$("#pagar").on("click",function(){
    
    var moneda = $('#socio [value="' + $("#soci").val() + '"]').attr('moneda');
    var decimalpago = 2;
    var usuario = $('#socio [value="' + $("#soci").val() + '"]').attr('usuario');
    var momento = $('#socio [value="' + $("#soci").val() + '"]').attr('momento');
    var paispago = $('#socio [value="' + $("#soci").val() + '"]').attr('pais');
    var pendiente = $('#socio [value="' + $("#soci").val() + '"]').attr('pendiente');
    
    var monedagasto = 0;
    var decimalgasto = 0;
    var cantidadgasto = 0;
    validador = 0;
    if($("#miElementoCheckbox").is(':checked')){
        monedagasto = moneda;
        decimalgasto = decimalpago;
        cantidadgasto = $(".monto").val();
    }else{
        monedagasto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda');
        decimalgasto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('decimal');
        cantidadgasto = $(".cantidadintercambio ").val();
    }
    if(typeof usuario === "undefined"){
        validador ++;
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },3000)
    }
    if($(".monto").val() == ""){
        validador ++;
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },3000)
    }
    if(!$("#miElementoCheckbox").is(':checked') && typeof $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda') === "undefined"){
        validador ++;
        $(".mensaje-error").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(3).css("display","none");
        },3000)
    }
    if(!$("#miElementoCheckbox").is(':checked') && $('.cantidadintercambio').val() == ""){
        validador ++;
        $(".mensaje-error").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(4).css("display","none");
        },3000)
    }

   
    if($(".imagen").val()==""){
        validador ++;
        $(".mensaje-error").eq(7).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(7).css("display","none");
        },3000)
    }

    
    if(validador == 0){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        
        $.ajax({
            url:urlglobal+'php/debito/pagar.php?pais='+paispago+'&monedapago='+moneda+'&decimalpago='+decimalpago+'&monedagasto='+monedagasto+'&decimalgasto='+decimalgasto+'&cantidadgasto='+cantidadgasto+'&gastopais='+$(".monto").val()+"&usuario="+localStorage.usuario+"&usuariocobro="+usuario+"&momento="+momento+"&pendiente="+pendiente,
            type:'POST',
            contentType:false,
            data:archivos,
            processData:false,
            data:archivos,
            beforeSend:function(){
                $(".contenido-imagen").css("display","flex");
            },
            complete:function(){
                $(".contenido-imagen").css("display","none");
                usauriopagos();
                $("#informacion").html("");
            },
           
            success:function(respuesta){
                
                $(".mensajesolicitud").text(JSON.parse(respuesta)[0]);
                if(JSON.parse(respuesta)[1]=="error"){
                    $(".mensajesolicitud").eq(1).css("display","flex");
                }else{
                    $(".mensajesolicitud").eq(0).css("display","flex");
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                    if(JSON.parse(respuesta)[1]!="error"){
                        $(".controls").val("")
                    }
                    $(window).scrollTop(0);
                },2000)
            }
        });
    }

})