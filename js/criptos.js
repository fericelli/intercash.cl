$.ajax({
    url:"../php/monedas/monedaoperaciones.php",
    type:"POST",
    data:{moneda:$("#enviar").attr("moneda")},
    beforeSend:function(){
        $(".imagenmoneda").css("display","flex");
        $("#cript").css("display","none");
    },
    complete:function(){
        $(".imagenmoneda").css("display","none");
        $("#cript").css("display","flex");
    },
    error:function(){
        alert("Ocurrio un error con la conexion");
        $(".imagenmoneda").css("display","none");
        $("#cript").css("display","flex");
    },
    success:function(data){
        console.log(JSON.parse(data)[0]);
        html = "";
        for(i=0;i<JSON.parse(data)[0].length;i++){

            if(JSON.parse(data)[0][i].moneda!="USDT"){
                html += '<option moneda="'+JSON.parse(data)[0][i].moneda+'" decimal="'+JSON.parse(data)[0][i].decimales+'" value="'+JSON.parse(data)[0][i].nombre+'">'+JSON.parse(data)[0][i].nombre+'</option>';
            }
        }
        $("#cripto").html(html);      
    }
})


$(".cantidad").keyup(function(){
    if(!$.isNumeric($(this).val())){
        $(this).val("");
        console.log($(".cantidad").index(this));
        $(".numero").eq($(".cantidad").index(this)).css("display","flex");
    }
    setTimeout(function(){
        $(".numero").css("display","none");
    },1000)
})

$(".botons").on("click",function(){
    var cripto = $('#cripto [value="' + $("#cript").val() + '"]').attr('moneda');
    var decimales = $('#cripto [value="' + $("#cript").val() + '"]').attr('decimal');
    if(typeof cripto === "undefined"){
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none"); 
        },2000);
    }
    if($("#cantidadcripto").val()==""){
        $(".mensaje-error").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(2).css("display","none"); 
        },2000);
    }
    if($("#cantidadusdt").val()==""){
        $(".mensaje-error").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(4).css("display","none"); 
        },2000);
    }

    if(typeof cripto !== "undefined" && $("#cantidadcripto").val()!="" && $("#cantidadusdt").val()!=""){
        $.ajax({
            url:"../php/criptos/comprarcripto.php",
            type:"POST",
            data:{usuario:localStorage.usuario,cripto:cripto,decimales:decimales,cantidadcripto:$("#cantidadcripto").val(),usdt:$("#cantidadusdt").val()},
            beforeSend:function(){
                $(".imagensolicitud").css("display","flex");
                $(this).css("display","none");
            },
            complete:function(){
                $(".imagensolicitud").css("display","none");
                $(this).css("display","none");
            },
            error:function(){
                alert("Ocurrio un error con la conexion");
                $(".imagensolicitud").css("display","none");
                $(this).css("display","none");
            },
            success:function(data){
                console.log(JSON.parse(data));
                if(typeof JSON.parse(data)[0][1] === "undefined"){
                    $(".mensajesolicitud").eq(1).css("display","flex");
                    $(".mensajesolicitud").eq(1).text(JSON.parse(data)[0][0]);
                } else{
                    $(".mensajesolicitud").eq(0).css("display","flex");
                    $(".mensajesolicitud").eq(0).text(JSON.parse(data)[0]);
                    $("input").val("");
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                },1000)
            }
        })
    }
    

})