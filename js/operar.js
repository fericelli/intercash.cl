$.ajax({
    url:"../php/monedas/monedaoperaciones.php",
    type:"POST",
    data:{moneda:$("#enviar").attr("moneda")},
    beforeSend:function(){
        $(".imagenmoneda").css("display","flex");
        $("#monedainter").css("display","none");
        $("#monedacomp").css("display","none");
    },
    complete:function(){
        $(".imagenmoneda").css("display","none");
        $("#monedainter").css("display","flex");
        $("#monedacomp").css("display","flex");
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
        $("#monedaintercambio").html(html);
        html = "";
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += '<option moneda="'+JSON.parse(data)[1][i].moneda+'" decimal="'+JSON.parse(data)[1][i].decimales+'" value="'+JSON.parse(data)[1][i].pais+'-'+JSON.parse(data)[1][i].nombre+'" pais="'+JSON.parse(data)[1][i].pais+'">'+JSON.parse(data)[1][i].nombre+'</option>';
        }
        $("#monedacompra").html(html);      
    }
})
$("input[type=radio]").eq(0).prop('checked',"checked");

/*$("input[type=radio]").on("click",function(){
    $("input[type=radio]").eq($("input[type=radio]").index(this)-1).removeAttr('checked');
    
   $("input[type=radio]").eq($("input[type=radio]").index(this)).prop('checked',"checked");
    
})*/


$(".monto").keyup(function(){
    if(!$.isNumeric($(this).val())){
        $(this).val("");
        $(".mensaje-error").eq(2).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(2).css("display","none");
        },2000);
    }
});
$(".cantidadintercambio").keyup(function(){
    if(!$.isNumeric($(this).val())){
        $(this).val("");
        $(".mensaje-error").eq(5).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(5).css("display","none");
        },2000);
    }
});

$("#operar").on("click",function(){
    validador = 0;


    var pais = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('pais');
    var moneda = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('moneda');
    var decimal = $('#monedacompra [value="' + $("#monedacomp").val() + '"]').attr('decimal');
    
    var cripto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda');
    var decimalcripto = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('decimal');

    $("#radio_1").is(":checked")

    tipoperacion = $('input[type="radio"]:eq(0):checked').attr("tipo");
   
        
    if(typeof pais === "undefined"){
        validador ++;
        $(".mensaje-error").eq(0).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },2000);
    }
    if($(".monto").val()==""){
        validador ++;
        $(".mensaje-error").eq(1).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },2000);  
    }
    if(typeof cripto === "undefined"){
        validador ++;
        $(".mensaje-error").eq(3).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(3).css("display","none");
        },2000);  
    }
    if($(".cantidadintercambio").val()==""){
        validador ++;
        $(".mensaje-error").eq(4).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(4).css("display","none");
        },2000);  
    }
    if($(".imagen").eq(0).val()==""){
        validador ++;
        $(".mensaje-error").eq(6).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(6).css("display","none");
        },2000);  
    }if($(".imagen").eq(1).val()==""){
        validador ++;
        $(".mensaje-error").eq(7).css("display","flex");
        setInterval(function(){
            $(".mensaje-error").eq(7).css("display","none");
        },2000);  
    }
    if(validador==0){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append(i,((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        $.ajax({
            url:"./../php/operaciones/operar.php?pais="+pais+"&moneda="+moneda+"&decimalmoneda="+decimal+"&cripto="+cripto+"&decimalcripto="+decimalcripto+"&tipoperacion="+tipoperacion+"&cantidadmoneda="+$(".monto").val()+"&cantidadcripto="+$(".cantidadintercambio").val()+"&usuario="+localStorage.usuario,
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
                    $(".mensajesolicitud").eq(1).text(JSON.parse(respuesta)[0]);
                    $(".mensajesolicitud").eq(1).css("display","flex");
                }else{
                    $(".mensajesolicitud").eq(0).css("display","flex");
                    $(".mensajesolicitud").eq(0).text(JSON.parse(respuesta)[0]);
                    $("input[type=file]").val("");
                    $("input[type=text]").val("");
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                },3000)
            }
        })
    }

})

$(".salir").on("click",function(){
    for(i=0;i<$(".item").length;i++){
        if($(".item").eq(i).attr("opcion")=="operaciones"){
            $(".item").eq(i).trigger("click");
        }
    }
})
