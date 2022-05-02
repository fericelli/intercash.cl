$(document).on("ready",function(){
    $.ajax({
        url:'./php/tasas/solicitudes.php',
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
                html += '<option moneda="'+json[0][i].moneda+'" decimales="'+json[0][i].decimales+'" pais="'+json[0][i].iso_pais+'" value="'+json[0][i].nombre+'"></option>';
            }
            for(i=0;i<json[1].length;i++){
                html1 += '<option moneda="'+json[1][i].moneda+'" decimales="'+json[1][i].decimales+'" pais="'+json[1][i].iso_pais+'" value="'+json[1][i].nombre+'"></option>';
            }
            $("#paisodestino").html(html);
            $("#paisorigen").html(html1);
        }
    });


    $("#cantidadenviar").keypress(function(e){
        $("#cantidadrecibir").val("");
        
        if (e.keyCode === 13 && !e.shiftKey) {
            
            $("#cantidadenviar").blur();
            var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
            var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
            var decimalorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('decimales');
            var decimaldestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
            if(typeof monedadestino === "undefined"){
                $(".mensaje-error").eq(0).css("display","flex");
                setTimeout(function(){
                    $(".mensaje-error").eq(0).css("display","none");
                },5000)
            }
            if(typeof monedaorigen === "undefined"){
                $(".mensaje-error").eq(1).css("display","flex");
                setTimeout(function(){
                    $(".mensaje-error").eq(1).css("display","none");
                },5000)
            }
            if(typeof monedadestino !== "undefined" && typeof monedaorigen !== "undefined"){
                $.ajax({
                    url:"./php/tasas/informacion.php",
                    type: 'POST',
                    data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadenviar:$(this).val(),decimalorigen:decimalorigen,decimaldestino:decimaldestino},
                    beforeSend:function(){
                        $(".contenedorcarga").css("display","flex");
                    },
                    complete:function(){
                        $(".contenedorcarga").css("display","none");
                    },
                    success:function(respuesta){
                        
                        json = JSON.parse(respuesta);
                        console.log(respuesta);
                        console.log(JSON.parse(respuesta))
                        if(json[0].diponibilidad=="si"){
                            $(".usd").html(json[0].usd+" USD");
                            $("#cantidadenviar").val(json[0].dineroenviar);
                            $("#cantidadrecibir").val(json[0].dinerorecibir);
                        }else{
                            
                            $(".mensaje-error").eq(2).text("Monto no permitido");
                            $(".mensaje-error").eq(2).css("display","flex");
                            setTimeout(function(){
                                $(".mensaje-error").eq(2).text("Ingrese un monto");
                                $(".mensaje-error").eq(2).css("display","none");
                            },2000);
                        }
                    }
                });
            }

            
        }
    })

    $("#cantidadenviar").focusin(function(e){
        $(".usd").html("0 USD");
        $("#cantidadrecibir").val("");
    })
    $("#cantidadrecibir").keypress(function(e){
   
        if (e.keyCode === 13 && !e.shiftKey) {
            $("#cantidadrecibir").blur();
            var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
            var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
            var decimalorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('decimales');
            var decimaldestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
            if(typeof monedadestino === "undefined"){
                $(".mensaje-error").eq(0).css("display","flex");
                setTimeout(function(){
                    $(".mensaje-error").eq(0).css("display","none");
                },5000)
            }
            if(typeof monedaorigen === "undefined"){
                $(".mensaje-error").eq(1).css("display","flex");
                setTimeout(function(){
                    $(".mensaje-error").eq(1).css("display","none");
                },5000)
            }
            if(typeof monedadestino !== "undefined" && typeof monedaorigen !== "undefined"){

                $.ajax({
                    url:"./php/tasas/informacion.php",
                    type: 'POST',
                    data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadrecibir:$(this).val(),decimalorigen:decimalorigen,decimaldestino:decimaldestino},
                    beforeSend:function(){
                        $(".contenedorcarga").css("display","flex");
                    },
                    complete:function(){
                        $(".contenedorcarga").css("display","none");
                    },
                    success:function(respuesta){
                        json = JSON.parse(respuesta);
                        console.log(respuesta);
                        console.log(JSON.parse(respuesta))
                        if(json[0].diponibilidad=="si"){
                            $(".usd").html(json[0].usd+" USD");
                            $("#cantidadenviar").val(json[0].dineroenviar);
                            $("#cantidadrecibir").val(json[0].dinerorecibir);
                        }else{
                            $(".mensaje-error").eq(3).text("Monto no permitido");
                            $(".mensaje-error").eq(3).css("display","flex");
                            setTimeout(function(){
                                $(".mensaje-error").eq(3).text("Ingrese un monto");
                                $(".mensaje-error").eq(3).css("display","none");
                            },2000);
                        }
                    }
                });
            }
        }
    })
    $("#cantidadrecibir").focusin(function(e){
        $(".usd").html("0 USD");
        $("#cantidadenviar").val("");
    })


    $(".botons").on("click",function(){

    })
})