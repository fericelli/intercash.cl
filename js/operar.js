var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");
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
    }else{
        $("#usuari").val(localStorage.usuario);  
        $("#usuari").css("display","none");
        $("label").eq(8).css("display","none");
    }
}
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
        $("#monedacomp").css("display","flex");
        $("#monedainter").css("display","flex");
    },
    success:function(data){
        html = "";
        for(i=0;i<JSON.parse(data)[0].length;i++){
            html += '<option moneda="'+JSON.parse(data)[0][i].moneda+'" decimal="'+JSON.parse(data)[0][i].decimales+'" value="'+JSON.parse(data)[0][i].nombre+'">'+JSON.parse(data)[0][i].nombre+'</option>';
        }
        $("#monedaintercambioenvio").html(html);
        $("#monedaintercambio").html(html);
        html = "";
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += '<option moneda="'+JSON.parse(data)[1][i].moneda+'" decimal="'+JSON.parse(data)[1][i].decimales+'" value="'+JSON.parse(data)[1][i].pais+'-'+JSON.parse(data)[1][i].nombre+'" pais="'+JSON.parse(data)[1][i].pais+'">'+JSON.parse(data)[1][i].nombre+'</option>';
        }
        $("#monedacompra").html(html);      
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
            html += '<option moneda="'+json[0][i].moneda+'" decimales="'+json[0][i].decimales+'" pais="'+json[0][i].iso_pais+'" value="'+json[0][i].nombre+'"></option>';
        }
        for(i=0;i<json[1].length;i++){
            html1 += '<option moneda="'+json[1][i].moneda+'" decimales="'+json[1][i].decimales+'" pais="'+json[1][i].iso_pais+'" value="'+json[1][i].nombre+'"></option>';
        }
        //$("#paisodestino").html(html);
        $("#paisorigen").html(html1);
    }
});

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
            $(".mensajeerrorenvio").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensajeerrorenvio").eq(0).css("display","none");
            },5000)
        }
        if(typeof monedaorigen === "undefined"){
            $(".mensajeerrorenvio").eq(1).css("display","flex");
            setTimeout(function(){
                $(".mensajeerrorenvio").eq(1).css("display","none");
            },5000)
        }
        if(typeof monedadestino !== "undefined" && typeof monedaorigen !== "undefined"){
            $.ajax({
                url:urlglobal+"php/tasas/informacion.php",
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
                        
                        $(".mensaje-error mensajeerrorenvio").eq(2).text("Monto no permitido");
                        $(".mensaje-error mensajeerrorenvio").eq(2).css("display","flex");
                        setTimeout(function(){
                            $(".mensaje-error mensajeerrorenvio").eq(2).text("Ingrese un monto");
                            $(".mensaje-error mensajeerrorenvio").eq(2).css("display","none");
                        },2000);
                    }
                }
            });
        }

        
    }
})

$("#paisorige").focusin(function(){
    $(this).val("");
    $(".usd").text("0 USD");
    $(".tasa").text("0 Tasa");
    $("#cantidadrecibir").val("");
    $("#cantidadenviar").val("");
    
    $("#paisodestin").val("");
    $("#paisodestino").html("");
    $("#cuenta").html("");
    $("#banco").html("");
    $("#tipodecuenta").html("");
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
                url:urlglobal+"/php/tasas/informacion.php",
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
    $(this).val("");
    $(".usd").text("0 USD");
    $(".tasa").text("0 Tasa");
    $("#cantidadenviar").val("");
    $("#cantidadrecibir").val("");
    $("#cuenta").html("");
    $("#banco").html("");
    $("#tipodecuenta").html("");
    $("#monedaintercambioenvio option").eq(2).remove();

})

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
            url:urlglobal+'php/paises/paisesdestino.php',
            type: 'POST',
            data: {moneda:moneda,pais:pais},
            beforeSend:function(){
                $("#paisodestin").css("display","none");
                $(".cargapaisdestino").css("display","flex");
            },
            complete:function(){
                $(".cargapaisdestino").css("display","none");
                $("#paisodestin").css("display","flex");
            },
            success:function(respuesta){
                json = JSON.parse(respuesta);
                html = "";
                for(i=0;i<json.length;i++){
                    html += '<option moneda="'+json[i].moneda+'" decimales="'+json[i].decimales+'" pais="'+json[i].iso_pais+'" value="'+json[i].nombre+'" receptor="'+json[i].receptor+'" nombremoneda="'+json[i].nombremoneda+'"></option>';
                }
                
                $("#paisodestino").html(html);
            }
        });
    }
})
$("#paisodestin").focusout(function(){
    
    var pais = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
    var moneda = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
    var receptor = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('receptor');
    var decimales = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
    var nombremoneda = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('nombremoneda');
    
    if(typeof pais === "undefined"){
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },5000)
    }
    if(typeof pais !== "undefined"){
        $.ajax({
            url:urlglobal+'php/paises/bancos.php',
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
                html = "";
                html1 = "";
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
        if(receptor=="1"){
            $("#monedaintercambioenvio").append('<option moneda="'+moneda+'" decimal="'+decimales+'" value="'+nombremoneda+'"></option>');
        }


    }
    if (typeof localStorage.getItem("usuario") !== "undefined" && localStorage.tipousuario!="administrador"){
        $("#usuari").val(localStorage.getItem("usuario"));
        $("#usuari").attr("disabled","disabled");
        var pais = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
        if(typeof pais !== "undefined"){
            $.ajax({
                url:urlglobal+"php/cuentas/usuarios.php",
                type: 'POST',
                data: {pais:pais,usuario:localStorage.getItem("usuario")},
                beforeSend:function(){
                    $(".cargacuenta").css("display","flex");
                    $("#cuent").css("display","none");
                },
                complete:function(){
                    $(".cargacuenta").css("display","none");
                    $("#cuent").css("display","flex");
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
            //$("#paisodestin").focus();
        }
    }
})


$(".cantidad").keyup(function(){
    if(!$.isNumeric($(this).val())){
        $(this).val("");
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

$("#usuari").focusout(function(){
    $("#cuent").val("");
    $("#cuenta").html("");
    $("#banc").removeAttr("nombre");
    $("#banc").val("");
    $("#tipodecuent").removeAttr("nombre");
    $("#tipodecuent").val("");
    $("#nombres").removeAttr("nombre");
    $("#nombres").val("");
    $("#identificacion").removeAttr("nombre");
    $("#identificacion").val("");
    $(".bloquecuen").removeAttr("disabled");
    //$("#usuario").val(localStorage.getItem("usuario"));
    //$("#usuario").attr("disabled","disabled");
    
    var pais = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
    var usuario = $('#usuario [value="' + $("#usuari").val() + '"]').attr('usuario');
   
    if(typeof pais !== "undefined"){
        $.ajax({
            url:urlglobal+'php/cuentas/usuarios.php',
            type: 'POST',
            data: {pais:pais,usuario:usuario},
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
    }
})

$("#cuent").on("change",function(){
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

$("input[type=radio]").eq(0).prop('checked',"checked");



$(".contenedor-form").eq(0).css("display","flex");

$(".form-tipoventa").eq(0).css("display","flex");


$("input[type=radio]").on("click",function(){
    $("input[type=radio]").eq($("input[type=radio]").index(this)-1).removeAttr('checked');
    
   $("input[type=radio]").eq($("input[type=radio]").index(this)).prop('checked',"checked");

    $(".contenedor-form").css("display","none");
    $(".contenedor-form").eq($("input[type=radio]").index(this)).css("display","block");
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


$(".monedacambiada").keyup(function(){
    if($.isNumeric($(this).val())===false && $(this).val()!=""){
        $(this).val("");
        $(".mensaje-error").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(4).css("display","none");
        },3000)
    }
})
$("#monedainter").on("change",function(){
    var moneda = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('moneda');
    var decimal = $('#monedaintercambio [value="' + $("#monedainter").val() + '"]').attr('decimal');
    
    if(moneda==$("#enviar").attr("moneda")){
        $(".margen").eq(5).css("display","none"); 
        $(".controls").eq(2).css("display","none");
    }else{
        $(".margen").eq(5).css("display","flex"); 
        $(".controls").eq(2).css("display","flex"); 
    }
    
})


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

$("#tipoventa").on("change",function(){
    $("#tipoventa option:selected").each(function(){
        tipoventa = $(this).attr("opcion");
    });
    $(".form-tipoventa").css("display","none");
    if(tipoventa=="envios"){
        $(".form-tipoventa").eq(0).css("display","flex");
    }
    if(tipoventa=="pagos"){

    }
    if(tipoventa=="gastos"){

    }
})

/*$.ajax({
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
})*/

$('#monedainterenvio').focusout(function(){
    var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
    var monedaintercambio = $('#monedaintercambioenvio [value="' + $("#monedainterenvio").val() + '"]').attr('moneda');


    $(".ocultatcantidad").css("display","flex");
    if(monedadestino == monedaintercambio){
        $(".ocultatcantidad").css("display","none");
    }
    
})
$("#enviar").on("click",function(){
    var monedaorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('moneda');
    var monedadestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('moneda');
    var decimal = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('decimales');
    var paisdestino = $('#paisodestino [value="' + $("#paisodestin").val() + '"]').attr('pais');
    var paisorigen = $('#paisorigen [value="' + $("#paisorige").val() + '"]').attr('pais');
    var tipodecuenta = $('#tipodecuenta [value="' + $("#tipodecuent").val() + '"]').val();
    var monedaintercambio = $('#monedaintercambioenvio [value="' + $("#monedainterenvio").val() + '"]').attr("moneda");
    var validador = 0;

    
    
    if(typeof monedadestino === "undefined"){
        validador ++;
        $(".mensajeerrorenvio").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(0).css("display","none");
        },5000)
    }
    if(typeof monedaorigen === "undefined"){
        validador ++;
        $(".mensajeerrorenvio").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(1).css("display","none");
        },5000)
    }
    if($("#cantidadenviar").val()==""){
         validador ++;
        $(".mensajeerrorenvio").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(2).css("display","none");
        },5000)
    }
    if($("#cantidadrecibir").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(3).css("display","none");
        },5000)
    }
    if($("#cuent").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(5).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(5).css("display","none");
        },5000)
    }
    if($("#banc").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(6).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(6).css("display","none");
        },5000)
    }
    if(typeof tipodecuenta === "undefined"){
        validador ++;
        $(".mensajeerrorenvio").eq(7).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(7).css("display","none");
        },5000)
    }
    if($("#nombres").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(8).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(8).css("display","none");
        },5000)
    }
    if($("#identificacion").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(9).css("display","flex");
        setTimeout(function(){
            $(".mensajeerrorenvio").eq(9).css("display","none");
        },5000)
    }
    var usuario = "";
    if(typeof localStorage.tipousuario !== "administrador" ){
        var usua = $('#usuario [value="' + $("#usuari").val() + '"]').val();
        if(typeof usua !== "undefined"){
            usuario = usua;
        }else{
            usuario = localStorage.usuario;
        }
    }else{
        usuario = localStorage.usuario;
    }

    /*if($(".montointercambio").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(10).css("display","flex");
        setInterval(function(){
            $(".mensajeerrorenvio").eq(10).css("display","none");
        },5000);  
    }*/
    if(typeof monedaintercambio === "undefined"){
        validador ++;
        $(".mensajeerrorenvio").eq(11).css("display","flex");
        setInterval(function(){
            $(".mensajeerrorenvio").eq(11).css("display","none");
        },5000);  
    }
    if($(".cantidadvendidaenvio").val()==""){
         
    }
    if($(".imagenenvio").val()==""){
        validador ++;
        $(".mensajeerrorenvio").eq(13).css("display","flex");
        setInterval(function(){
            $(".mensajeerrorenvio").eq(13).css("display","none");
        },5000);  
    }

    cambio = "";
    if($(".cantidadvendidaenvio").css("display")!="none"){
        cambio = $(".cantidadvendidaenvio").val();
        if($(".cantidadvendidaenvio")==""){
            validador ++;
            $(".mensajeerrorenvio").eq(12).css("display","flex");
            setInterval(function(){
                $(".mensajeerrorenvio").eq(12).css("display","none");
            },5000); 
        }
            
      
    }else{
        cambio = $("#cantidadrecibir").val();
    }

    if(validador==0){
        var formulario = $(".form-register"); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        $.ajax({
            url:"./../php/operaciones/enviar.php?paisorigen="+paisorigen+"&monedaorigen="+monedaorigen+"&paisdestino="+paisdestino+"&monedadestino="+monedadestino+"&cantidadenviar="+$("#cantidadenviar").val()+"&cantidadrecibir="+$("#cantidadrecibir").val()+"&cuenta="+$("#cuent").val()+"&banco="+$("#banc").val()+"&tipodecuenta="+tipodecuenta+"&nombres="+$("#nombres").val()+"&identificacion="+$("#identificacion").val()+"&usuario="+usuario+"&monedaintercambio="+monedaintercambio+"&cantidadvendidaenvio="+cambio+"&operador="+localStorage.usuario+"&decimal="+decimal,
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
                    setTimeout(function(){
                        $("#paisorige").val("");
                        $("#paisodestin").val("");
                        $("#paisodestino").html("");
                        $("#cantidadenviar").val("");
                        $("#cantidadrecibir").val("");
                        $(".usd").text("0 USD");
                        $(".tasa").text("0 Tasa");
                        $("#usuari").val("");
                        $("#cuent").val("");
                        $("#banc").removeAttr("nombre");
                        $("#banc").val("");
                        $("#tipodecuent").removeAttr("nombre");
                        $("#tipodecuent").val("");
                        $("#nombres").removeAttr("nombre");
                        $("#nombres").val("");
                        $("#identificacion").removeAttr("nombre");
                        $("#identificacion").val("");
                        $(".bloquecuen").removeAttr("disabled");

                        $("#identificacion").val("");
                        $(".cantidadvendidaenvio").val("");
                        $(window).scrollTop(0);
                    },6000)
                }
                setTimeout(function(){
                    $(".mensajesolicitud").css("display","none");
                },6000)
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
