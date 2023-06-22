$(document).on("ready",function(e){
    e.preventDefault();
    $(".botons").on("click",function(){
        
        var validador = 0;
        
        if($("#usuario").val()==""){
            validador ++;
            $(".mensaje-error").eq(4).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(4).css("display","none");
            },5000)
        }
        if($("#clave").val()==""){
            validador ++;
            $(".mensaje-error").eq(5).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(5).css("display","none");
            },5000)
        }
        
        if(validador==0){
            $.ajax({
                url:"./php/iniciarsesion.php",
                type: 'POST',
                data: {usuario:$("#usuario").val(),clave:$("#clave").val()},
                beforeSend:function(){
                    $(".imagensolicitud").css("display","flex");
                    $(".botons").css("display","none");
                },
                complete:function(){
                    $(".imagensolicitud").css("display","none");
                },
                success:function(respuesta){
                    json = JSON.parse(respuesta);
                    if(json[1]=="correcto"){
                        $(".mensaje-correcto").css("display","flex").text(json[0]);
                        localStorage.sesioniniciada="iniciada";
                        localStorage.usuario=$("#usuario").val();
                        localStorage.tipousuario = json[2];
                        var URLactual = window.location;
                        var url = URLactual.href+"sesion";
                        $(".botons").css("display","none");
                        setTimeout(function(){
                            location.href = url;
                        },6000)
                        
                        
                    }else{
                        $(".mensaje-error").eq(2).css("display","flex").text(json[0]);
                    }
                    setTimeout(function(){
                        $(".mensajesolicitud").css("display","none");
                    },8000)
                    
                }
            });
        }
    })
    var URLactual = window.location;

    $.ajax({
        url:URLactual+'php/paises/paisesorigen.php',
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
            //$("#paisodestino").html(html);
            $("#paisorigen").html(html1);
        }
    });
    $("#paisorige").focusout(function(){
        var moneda = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
        var pais = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('pais');
        if(typeof moneda === "undefined"){
            $(".mensaje-error").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(0).css("display","none");
            },5000)
        }
        if(typeof moneda !== "undefined"){
            $.ajax({
                url:URLactual+'php/paises/paisesdestino.php',
                type: 'POST',
                data: {moneda:moneda,pais:pais},
                beforeSend:function(){
                    $(".cargapaisdestino").css("display","flex");
                    $("#paisodestin").css("display","none");
                },
                complete:function(){
                    $(".cargapaisdestino").css("display","none");
                    $("#paisodestin").css("display","flex");
                },
                success:function(respuesta){
                    json = JSON.parse(respuesta);
                    html = "";
                    for(i=0;i<json.length;i++){
                        html += '<option moneda="'+json[i].moneda+'" decimales="'+json[i].decimales+'" pais="'+json[i].iso_pais+'" value="'+json[i].nombre+'"></option>';
                    }
                    
                    $("#paisodestino").html(html);
                }
            });
        }
    })
    $("#cantidadenviar").focusout(function(e){
        $("#cantidadrecibir").val("");
        if($(this).val()!="") {
            var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
            var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
            var decimalorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('decimales');
            var decimaldestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
            var paisorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('pais');
            var paisdestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
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
                    url:URLactual+"php/tasas/informacion.php",
                    type: 'POST',
                    data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadenviar:$("#cantidadenviar").val(),decimalorigen:decimalorigen,decimaldestino:decimaldestino,paisorigen:paisorigen,paisdestino:paisdestino},
                    beforeSend:function(){
                        $(".imagenusd").css("display","flex");
                        $(".usd").css("display","none");
                        $(".tasa").css("display","none");
                    },
                    complete:function(){
                        $(".imagenusd").css("display","none");
                        $(".usd").css("display","flex");
                        $(".tasa").css("display","flex");
                    },
                    success:function(respuesta){
                        
                        json = JSON.parse(respuesta);
                        $(".tasa").text(json[0].tasa+" Tasa");
                        $(".usd").text(json[0].usd+" USD");
                        if(json[0].diponibilidad=="si"){
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
    $("#paisorige").focusin(function(){
        $(".usd").text("0 USD");
        $(".tasa").text("0 Tasa");
        $("#cantidadrecibir").val("");
        $("#cantidadenviar").val("");
        $("#paisodestin").val("");
        
    })
    $("#cantidadenviar").focusin(function(e){
        $(".usd").text("0 USD");
        $(".tasa").text("0 Tasa");
        $("#cantidadrecibir").val("");
        
        
    })
    $("#cantidadrecibir").focusout(function(e){
        
        if($(this).val()!="") {
            var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
            var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
            var decimalorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('decimales');
            var decimaldestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
            var paisorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('pais');
            var paisdestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
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
                    url:URLactual+"/php/tasas/informacion.php",
                    type: 'POST',
                    data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadrecibir:$(this).val(),decimalorigen:decimalorigen,decimaldestino:decimaldestino,paisorigen:paisorigen,paisdestino:paisdestino},
                    beforeSend:function(){
                        $(".imagenusd").css("display","flex");
                        $(".usd").css("display","none");
                        $(".tasa").css("display","none");
                    },
                    complete:function(){
                        $(".imagenusd").css("display","none");
                        $(".usd").css("display","flex");
                        $(".tasa").css("display","flex");
                    },
                    success:function(respuesta){
                        json = JSON.parse(respuesta);
                        $(".tasa").text(json[0].tasa+" Tasa");
                        $(".usd").text(json[0].usd+" USD");
                        if(json[0].diponibilidad=="si"){
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
        $(".usd").text("0 USD");
        $(".tasa").text("0 Tasa");
        $("#cantidadenviar").val("");
    })
    $("#paisodestin").focusin(function(){
        $(".usd").text("0 USD");
        $(".tasa").text("0 Tasa");
        $("#cantidadenviar").val("");
        $("#cantidadrecibir").val("");

    })
})