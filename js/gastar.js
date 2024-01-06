var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");
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

$.ajax({
    url:urlglobal+'php/paises/paisesorigen.php',
    beforeSend:function(){
        $(".contenedorcarga").css("display","flex");
    },
    complete:function(){
        $(".contenedorcarga").css("display","none");
    },
   
    success:function(respuesta){
        json = JSON.parse(respuesta);
        html = "";
        html1 = "";
        for(i=0;i<json[0].length;i++){
            html += '<option moneda="'+json[0][i].moneda+'" decimales="'+json[0][i].decimales+'" pais="'+json[0][i].iso_pais+'" value="'+json[0][i].nombre+'" receptor="'+json[0][i].receptor+'"></option>';
        }
        for(i=0;i<json[1].length;i++){
            html1 += '<option moneda="'+json[1][i].moneda+'" decimales="'+json[1][i].decimales+'" pais="'+json[1][i].iso_pais+'" value="'+json[1][i].nombre+'" receptor="'+json[0][i].receptor+'"></option>';
        }
        //$("#paisodestino").html(html);
        $("#monedacompra").html(html1);
    }
});

$("#monedacomp").focusin(function(){
    $("input").val("");
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

$("#gastar").on("click",function(){
    var monedapago = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('moneda');
    var decimalpago = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('decimales');
    var paispago = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('pais');


    
    var monedagasto = 0;
    var decimalgasto = 0;
    var cantidadgasto = 0;
    validador = 0;
    if($("#miElementoCheckbox").is(':checked')){
        monedagasto = monedapago;
        decimalgasto = decimalpago;
        cantidadgasto = $(".monto").val();
    }else{
        monedagasto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda');
        decimalgasto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('decimal');
        cantidadgasto = $(".cantidadintercambio ").val();
    }
    if(typeof monedapago === "undefined"){
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

    if($(".descripcion").val()==""){
        validador ++;
        $(".mensaje-error").eq(6).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(6).css("display","none");
        },3000)
    }
    /*if($(".imagen").val()==""){
        validador ++;
        $(".mensaje-error").eq(7).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(7).css("display","none");
        },3000)
    }*/

    
    if(validador == 0){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        
        $.ajax({
            url:urlglobal+'php/debito/gastar.php?pais='+paispago+'&monedapago='+monedapago+'&decimalpago='+decimalpago+'&monedagasto='+monedagasto+'&decimalgasto='+decimalgasto+'&cantidadgasto='+cantidadgasto+'&descripcion='+$(".descripcion").val()+'&gastopais='+$(".monto").val()+"&usuario="+localStorage.usuario,
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