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

    $("#paisodestin").focusout(function(){
        var pais = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
        if(typeof pais === "undefined"){
            $(".mensaje-error").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(0).css("display","none");
            },5000)
        }
        if(typeof pais !== "undefined"){
            $.ajax({
                url:"./php/paises/bancos.php",
                type: 'POST',
                data: {pais:pais},
                beforeSend:function(){
                    $(".cargabancos").css("display","flex");
                    $("#banc").css("display","none");
                    $("#tipodecuent").css("display","none");
                },
                complete:function(){
                    $(".cargabancos").css("display","none");
                    $("#banc").css("display","flex");
                    $("#tipodecuent").css("display","flex");
                },
                success:function(respuesta){
                    json = JSON.parse(respuesta);
                    console.log(json);
                    html = "";
                    html1 = "";
                    console.log(json.bancos.length);
                    for(i=0;i<json.bancos.length;i++){
                        html += '<option codigo="'+json.bancos[i].codigo+'"  value="'+json.bancos[i].nombre+'"></option>';
                    }
                    for(i=0;i<json.tiposdecuenta.length;i++){
                        html1 += '<option value="'+json.tiposdecuenta[i]+'"></option>';
                    }
                    $("#banco").html(html);
                    $("#tipodecuenta").html(html1);
                }
            });
        }

    })
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
                    data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadenviar:$("#cantidadenviar").val(),decimalorigen:decimalorigen,decimaldestino:decimaldestino},
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

    $("#cantidadenviar").focusin(function(e){
        $(".usd").text("0 USD");
        $(".tasa").text("0 Tasa");
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
    $("#usuario").keypress(function(e){
        if (e.keyCode === 13 && !e.shiftKey){
            $("#cantidadrecibir").blur();
            var pais = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
            if(typeof pais !== "undefined"){
                $.ajax({
                    url:"./php/cuentas/usuarios.php",
                    type: 'POST',
                    data: {pais:pais,usuario:$(this).val()},
                    beforeSend:function(){
                        $(".cargacuenta").css("display","flex");
                    },
                    complete:function(){
                        $(".cargacuenta").css("display","none");
                    },
                    success:function(respuesta){
                        json = JSON.parse(respuesta);
                        if(json.cuentas.length>0){
                            html = "";
                            for(i=0;i<json.cuentas.length;i++){
                                html += '<option cuenta="'+json.cuentas[i].cuenta+'" banco="'+json.cuentas[i].banco+'" tipo="'+json.cuentas[i].tipo+'" nombres="'+json.cuentas[i].nombres+'" value="'+json.cuentas[i].cuenta+'" identificacion="'+json.cuentas[i].identificacion+'"></option>';
                            }
                            $("#cuenta").html(html);
                        }
                        
                    }
                });
            }else{
                $("#paisodestin").focus();
            }
            
        }
    })
    $("#cuent").focusin(function(e){
        $(this).val("");
        $("#banc").removeAttr("nombre");
        $("#banc").val("");
        $("#tipodecuent").removeAttr("nombre");
        $("#tipodecuent").val("");
        $("#nombres").removeAttr("nombre");
        $("#nombres").val("");
        $("#identificacion").removeAttr("nombre");
        $("#identificacion").val("");
        $(".bloquecuen").removeAttr("disabled");
    })
    $("#cuent").focusout(function(e){
        var  banco = $('#cuenta [value="' + $("#cuent").val() + '"]').attr('banco');
        var  tipodecuenta = $('#cuenta [value="' + $("#cuent").val() + '"]').attr('tipo');
        var  nombres = $('#cuenta [value="' + $("#cuent").val() + '"]').attr('nombres');
        var  identificacion = $('#cuenta [value="' + $("#cuent").val() + '"]').attr('identificacion');
        if(typeof banco !== "undefined"){
            $("#banc").attr("nombre",banco);
            $("#banc").val(banco);
            $("#tipodecuent").attr("nombre",tipodecuenta);
            $("#tipodecuent").val(tipodecuenta);
            $("#nombres").attr("nombre",nombres);
            $("#nombres").val(nombres);
            $("#identificacion").attr("nombre",identificacion);
            $("#identificacion").val(identificacion);
            $(".bloquecuen").attr("disabled","disabled");
        }
    })

    $(".botons").on("click",function(){
        
        var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
        var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
        var validador = 0;
        if(typeof monedadestino === "undefined"){
            validador ++;
            $(".mensaje-error").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(0).css("display","none");
            },5000)
        }
        if(typeof monedaorigen === "undefined"){
            validador ++;
            $(".mensaje-error").eq(1).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(1).css("display","none");
            },5000)
        }
        if($("#cantidadenviar").val()==""){
            validador ++;
            $(".mensaje-error").eq(2).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(2).css("display","none");
            },5000)
        }
        if($("#cantidadrecibir").val()==""){
            validador ++;
            $(".mensaje-error").eq(3).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(3).css("display","none");
            },5000)
        }
        if($("#usuario").val()==""){
            validador ++;
            $(".mensaje-error").eq(4).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(4).css("display","none");
            },5000)
        }
        if($("#cuent").val()==""){
            validador ++;
            $(".mensaje-error").eq(5).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(5).css("display","none");
            },5000)
        }
        if($("#banc").val()==""){
            validador ++;
            $(".mensaje-error").eq(6).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(6).css("display","none");
            },5000)
        }
        if($("#tipodecuent").val()==""){
            validador ++;
            $(".mensaje-error").eq(7).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(7).css("display","none");
            },5000)
        }
        if($("#nombres").val()==""){
            validador ++;
            $(".mensaje-error").eq(8).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(8).css("display","none");
            },5000)
        }
        if($("#identificacion").val()==""){
            validador ++;
            $(".mensaje-error").eq(9).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(9).css("display","none");
            },5000)
        }
        

        if(validador==0){
            $.ajax({
                url:"./php/intercambios/solicitudes.php",
                type: 'POST',
                data: {monedaorigen:monedaorigen,monedadestino:monedadestino,cantidadrecibir:$("#cantidadrecibir").val(),cantidadenviar:$("#cantidadenviar").val(),cuenta:$("#cuent").val(),banco:$("#banc").val(),tipodecuenta:$("#tipodecuent").val(),nombres:$("#nombres").val(),usuario:$("#usuario").val(),identificacion:$("#identificacion").val()},
                beforeSend:function(){
                    $(".imagensolicitud").css("display","flex");
                    $(".botons").css("display","none");
                },
                complete:function(){
                    $(".imagensolicitud").css("display","none");
                    $(".botons").css("display","");
                },
                success:function(respuesta){
                    json = JSON.parse(respuesta);
                    console.log(json[0].mensausuario[1]);
                    if(json[0].mensausuario[1]="correcto"){
                        $(".mensaje-correcto").css("display","flex").text(json[0].mensausuario[0]);
                    }else{
                        $(".mensaje-error").eq(10).css("display","flex").text(json[0].mensausuario[0]);
                    }
                    setTimeout(function(){
                        $(".mensajesolicitud").css("display","none");
                    },5000)
                    
                }
            });
        }
    })
})