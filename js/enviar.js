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
            html += '<div><label>'+JSON.parse(data)[0][i].nombre+'</label><input type="radio" name="moneda" moneda="'+JSON.parse(data)[0][i].moneda+'" decimal="'+JSON.parse(data)[0][i].decimales+'" ></div>';
        }
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += '<div><label>'+JSON.parse(data)[1][i].nombre+'</label><input type="radio" name="moneda" moneda="'+JSON.parse(data)[1][i].moneda+'" decimal="'+JSON.parse(data)[1][i].decimales+'"></div>';
        }
        $("#monedaintercambio").html(html);
        
    }
})

$(".monto").keyup(function(){
    if($.isNumeric($(this).val())===false && $(this).val()!=""){
        $(this).val("");
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },3000)
    }
})
/*$(".monedacambiada").keyup(function(){
    if($.isNumeric($(this).val())===false && $(this).val()!=""){
        $(this).val("");
        $(".mensaje-error").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(4).css("display","none");
        },3000)
    }
})*/
/*$("#monedaintercambio").on("click",function(){
    var moneda = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda');
    var decimal = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('decimal');

    var moneda = $('#monedaintercambio input[type="radio"]:checked').attr('moneda');
    var decimal = $('#monedaintercambio input[type="radio"]:checked').attr('decimal');
    
    if(moneda==$("#enviar").attr("moneda")){
        $(".margen").eq(5).css("display","none"); 
        $(".controls").eq(2).css("display","none");
    }else{
        $(".margen").eq(5).css("display","flex"); 
        $(".controls").eq(2).css("display","flex"); 
    }
    
})*/

$("#enviar").on("click",function(){
    validador = 0;
    if($(".monto").val()==""){
        validador ++;
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },3000)
    }
    //$(".dia:eq("+$(".icono-guardar").index(this)+") input[type='radio']:checked").val();
    var moneda = $('#monedaintercambio input[type="radio"]:checked').attr('moneda');
    var decimal = $('#monedaintercambio input[type="radio"]:checked').attr('decimal');
    console.log(moneda);
    if(typeof moneda === "undefined"){
        validador ++;
        $(".mensaje-error").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(3).css("display","none");
        },3000)
    }

    

    cambio = $(".monto").val();
    /*if($(".controls").eq(2).css("display")!="none"){
        cambio = $(".controls").eq(2).val();
        if($(".controls").eq(2).val()==""){
            validador ++;
            $(".mensaje-error").eq(3).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(3).css("display","none");
            },3000);
        }
            
      
    }else{
        cambio = $(".monto").val();
    }*/
    
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
            url:"./../php/envios/enviar.php?cantidad="+$(".monto").val()+"&usuario="+$(this).attr("usuario")+"&registro="+$(this).attr("registro")+"&pendiente="+$(this).attr("pendiente")+"&total="+$(this).attr("total")+"&operador="+localStorage.getItem("usuario")+"&moneda="+$(this).attr("moneda")+"&cambio="+cambio+"&monedacambio="+moneda+"&decimal="+decimal,
            type:'POST',
            contentType:false,
            data:archivos,
            processData:false,
            data:archivos,
            beforeSend:function(){
                $(".imagensolicitud").css("display","flex");
                $("#enviar").css("display","none");
            },
            complete:function(){
                $(".imagensolicitud").css("display","none");
                $("#enviar").css("display","");
            },
            error:function(){
                alert("Ocurrio un error con la conexion");
                $(".imagensolicitud").css("display","none");
                $("#enviar").css("display","");
            },
            success:function(respuesta){
                console.log(JSON.parse(respuesta));

                if(JSON.parse(respuesta)[1]=="error"){
                    $(".mensajesolicitud").eq(1).text(JSON.parse(respuesta)[0]);
                    $(".mensajesolicitud").eq(1).css("display","flex");
                }else{
                    if(JSON.parse(respuesta)[0][1]=="finalizar"){
                         $(".mensajesolicitud").eq(0).css("display","flex");
                         $(".mensajesolicitud").eq(0).text(JSON.parse(respuesta)[0][0]);
                         
                        $("#enviar").remove();
                         setTimeout(function(){

                            $(".salir").trigger("click");
                         },1000)
                    }else{
                        $(".mensajesolicitud").eq(0).css("display","flex");
                        $(".mensajesolicitud").eq(0).text(JSON.parse(respuesta)[0][0]);
                       setTimeout(function(){
                           $(".mensajesolicitud").eq(0).css("display","none");
                           $("#enviar").attr("pendiente",JSON.parse(respuesta)[1]);
                           $(".pendiente").text(JSON.parse(respuesta)[1]+" "+JSON.parse(respuesta)[2]);
                           $(".monto").val();
                        },1000)
                    }
                    if(JSON.parse(respuesta)[0][2]!=""){
                        copy =  window.location.href.replace("sesion/", "")+JSON.parse(respuesta)[0][2];
                        navigator.clipboard.writeText(copy);
                    }
                    $('#monedaintercambio input[type="radio"]:checked').prop("checked", false);
                    $(".monto").val("");
                    $(".imagen").val("");
                }
            }
        })
    }
})
$(".salir").on("click",function(){
    for(i=0;i<$(".item").length;i++){
        if($(".item").eq(i).attr("opcion")=="solicitudes"){
            $(".item").eq(i).trigger("click");
        }
    }
})
