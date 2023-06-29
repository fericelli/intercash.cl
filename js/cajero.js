var URLactual = window.location;
var urlglobal = URLactual.href.replace("sesion/", "");

    
    

$("#usuari").val(localStorage.usuario);
   
function datos(){
    $("#cuentasenvio").html("");

    if(localStorage.tipousuario=="administrador"){
        var usuariodatos = $("#usuari").val();
    }else{
         var usuariodatos = localStorage.usuario;
    }
    
    $.ajax({
        url:"./../php/cajero/datos.php",
        type: 'POST',
        data: {usuario:usuariodatos},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(respuesta){
            json = JSON.parse(respuesta);

            monedas = "";
            for(var i =0;i<json[0].length;i++){
                monedas += "<option  pais='"+json[0][i].pais+"' moneda='"+json[0][i].moneda+"'>"+json[0][i].nombre+"-"+json[0][i].moneda+"</option>";
            }
            $("select").html(monedas);
            cuentas = "";
            for(var i =0;i<json[1].length;i++){
                cuentas += "<option  banco='"+json[1][i].banco+"' cuenta='"+json[1][i].cuenta+"' tipodecuenta='"+json[1][i].tipodecuenta+"' nombre='"+json[1][i].nombre+"' identificacion='"+json[1][i].identificacion+"' usuario='"+json[1][i].usuario+"' value='"+json[1][i].banco+" - "+json[1][i].cuenta+"'>"+json[1][i].banco+" - "+json[1][i].cuenta+"</option>";
            }
            $("#cuentas").html(cuentas);
            cuentas = "";
            for(var i =0;i<json[2].length;i++){
                cuentas += "<option  banco='"+json[2][i].banco+"' cuenta='"+json[2][i].cuenta+"' tipodecuenta='"+json[2][i].tipodecuenta+"' nombre='"+json[2][i].nombre+"' identificacion='"+json[2][i].identificacion+"' usuario='"+json[2][i].usuario+"' value='"+json[2][i].cuenta+"'>"+json[2][i].banco+" - "+json[2][i].cuenta+"</option>";
            }
            $("#cuentasenvio").html(cuentas);
            tipocuenta = "";
            for(var i =0;i<json[3].length;i++){
                tipocuenta += "<option  value='"+json[3][i]+"'>"+json[3][i]+"</option>";
            }
            $("#tipocuentaenvio").html(tipocuenta);
            $("#entero").text(json[4][0].split(",")[0]);
            $("#decimal").text(","+json[4][0].split(",")[1]);
            $("#enterobtc").text(json[4][1].split(",")[0]);
            $("#decimalbtc").text(","+json[4][1].split(",")[1]);

        }
    });
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
    
    $("#usuari").val(usuariodatos);
}


$("#usuari").focusout(function(){
    var usuario = $('#usuario [value="' + $(this).val() + '"]').attr("usuario");
    
    $("#cuentasenvio").html("");
    $("#cuentascuentas").html("");
    if(localStorage.tipousuario=="administrador" && typeof usuario !== "undefined"){
        var usuariodatos = $("#usuari").val();
    }else{
        var usuariodatos = localStorage.usuario;
        $("#usuari").val(localStorage.usuario);
    }
    pais = $('select option:selected').attr('pais');
    moneda = $('select option:selected').attr('moneda');
    texto = $('select option:selected').text();


        $.ajax({
            url:"./../php/cajero/datos.php",
            type: 'POST',
            data: {usuario:usuariodatos,moneda:$('select option:selected').attr('moneda'),pais:$('select option:selected').attr('pais')},
            beforeSend:function(){
                $(".contenido-imagen").css("display","flex");
            },
            complete:function(){
                $(".contenido-imagen").css("display","none");
            },
            success:function(respuesta){
                json = JSON.parse(respuesta);
                monedas = "<option  pais='"+pais+"' moneda='"+moneda+"'>"+texto+"</option>";
                for(var i =0;i<json[0].length;i++){
                    monedas += "<option  pais='"+json[0][i].pais+"' moneda='"+json[0][i].moneda+"'>"+json[0][i].nombre+"-"+json[0][i].moneda+"</option>";
                }
                $("select").html(monedas);
                cuentas = "";
                for(var i =0;i<json[1].length;i++){
                    cuentas += "<option  banco='"+json[1][i].banco+"' cuenta='"+json[1][i].cuenta+"' tipodecuenta='"+json[1][i].tipodecuenta+"' nombre='"+json[1][i].nombre+"' identificacion='"+json[1][i].identificacion+"' usuario='"+json[1][i].usuario+"' value='"+json[1][i].banco+" - "+json[1][i].cuenta+"'>"+json[1][i].banco+" - "+json[1][i].cuenta+"</option>";
                }
                $("#cuentas").html(cuentas);
                cuentas = "";
                for(var i =0;i<json[2].length;i++){
                    cuentas += "<option  banco='"+json[2][i].banco+"' cuenta='"+json[2][i].cuenta+"' tipodecuenta='"+json[2][i].tipodecuenta+"' nombre='"+json[2][i].nombre+"' identificacion='"+json[2][i].identificacion+"' usuario='"+json[2][i].usuario+"' value='"+json[2][i].cuenta+"'>"+json[2][i].banco+" - "+json[2][i].cuenta+"</option>";
                }
                $("#cuentasenvio").html(cuentas);
                tipocuenta = "";
                for(var i =0;i<json[3].length;i++){
                    tipocuenta += "<option  value='"+json[3][i]+"'>"+json[3][i]+"</option>";
                }
                $("#tipocuentaenvio").html(tipocuenta);
                $("#entero").text(json[4][0].split(",")[0]);
                $("#decimal").text(","+json[4][0].split(",")[1]);
                $("#enterobtc").text(json[4][1].split(",")[0]);
                $("#decimalbtc").text(","+json[4][1].split(",")[1]);
    
            }
        });
    
})

$("#main-container").css("color","#212529");
$(".col-md-7").css("width","");
$(".container").css("max-width","");

$(".row").css("display","flex");
$(".row").css("margin","");

$(".opciones a").eq(0).css("color","#2658b9");
$(".bg-blue").eq(1).css("display","none");
$(".opciones a").on("click",function(){
    $(".opciones a").css("color","#000");
    $(".bg-blue").css("display","none");
    $(".opciones a").eq($(".opciones a").index(this)).css("color","#2658b9");
    $(".bg-blue").eq($(".opciones a").index(this)).css("display","");
})

$("button").eq(0).on("click", function(e){
    e.preventDefault();
    var banco = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('banco');
    var cuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('cuenta');
    var tipodecuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('tipodecuenta');
    var nombre = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('nombre');
    var identificacion = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('identificacion');
    var usuariocuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('usuario');
    if($("#cantidadeposito").val()==""){
        $(".mensajedeposito").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensajedeposito").eq(0).css("display","none");
        },5000)    
    }
    if($("#cantidadeposito").val()!="" && $.isNumeric($("#cantidadeposito").val())==false){
        $(".mensajedeposito").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensajedeposito").eq(1).css("display","none");
        },5000)    
    }
    if(typeof banco === "undefined"){
        $(".mensajedeposito").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensajedeposito").eq(2).css("display","none");
        },5000)
    }
    if($(".file").val() == ""){
        $(".mensajedeposito").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensajedeposito").eq(3).css("display","none");
        },5000)
    }

    if($("#cantidadeposito").val()!="" && $.isNumeric($("#cantidadeposito").val())!=false && typeof banco !== "undefined" && $(".file").val() != ""){
        var formulario = $("form").eq(0); 
        var archivos = new FormData();
        for(var i = 0; i < (formulario.find("input[type=file]").length); i++){
            archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        }
        pais = $("select:eq(0) option:selected").attr("pais");
        moneda = $("select:eq(0) option:selected").attr("moneda");
        
        
        var usuariovalidar = $('#usuario [value="' + $("#usuari").val() + '"]').attr('usuario');
        if(typeof usuariovalidar !== "undefined"){
            usuario = usuariovalidar;
        }else{
            usuario = localStorage.usuario;
        }
        $.ajax({
                url:"./../php/cajero/depositar.php?cantidad="+$("#cantidadeposito").val()+"&usuario="+usuario+"&tipousaurio="+localStorage.tipousuario+"&cantidad="+$("#cantidadeposito").val()+"&usuariocuenta="+usuariocuenta+"&banco="+banco+"&tipodecuenta="+tipodecuenta+"&cuenta="+cuenta+"&nombre="+nombre+"&identificacion="+identificacion+"&pais="+pais+"&moneda="+moneda,
                type:'POST',
                contentType:false,
                data:archivos,
                processData:false,
                data:archivos,
                beforeSend:function(){
                    $(".imagensolicitud").css("display","flex");
                    $("button").eq(0).css("display","none");
                },
                complete:function(){
                    $(".imagensolicitud").css("display","none");
                    $("button").eq(0).css("display","flex");
                },
                error:function(){
                    alert("Ocurrio un error con la conexion");
                    $(".imagensolicitud").css("display","none");
                    $("button").eq(0).css("display","flex");
                },
                success:function(respuesta){
                    if(JSON.parse(respuesta)[1]=="success"){
                        $(".enviado").css("display","flex");
                        setTimeout(function(){
                            $(".error").css("display","none");
                            $(".enviado").css("display","none");
                            $("#banco").text("");
                            $("#cuenta").text("");
                            $("#tipocuenta").text("");
                            $("#nombres").text("");
                            $("#identificacion").text("");
                            $("#cantidadeposito").val("");
                            $("#cuent").val("");
                            $("#cantidadeposito").val("");
                            $("#cuent").val("");
                        },2000)
                    }else{
                        $(".error").css("display","flex");
                    }
                   
                }
            })
        
    }
})


$("#cuent").on("change",function(){
    var banco = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('banco');
    var cuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('cuenta');
    var tipodecuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('tipodecuenta');
    var nombre = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('nombre');
    var identificacion = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('identificacion');
    $("#banco").text("");
    $("#cuenta").text("");
    $("#tipocuenta").text("");
    $("#nombre").text("");
    $("#identificacion").text("");
    

    if(typeof banco !== "undefined"){
        $("#banco").text(banco);
        $("#cuenta").text(cuenta);
        $("#tipocuenta").text(tipodecuenta);
        $("#nombres").text(nombre);
        $("#identificacion").text(identificacion);
    }
})
$("#cuentsenvios").on("change",function(){

    var banco = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('banco');
    var cuenta = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('cuenta');
    var tipodecuenta = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('tipodecuenta');
    var nombre = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('nombre');
    var identificacion = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('identificacion');
    $("#bancoenvio").val("");
    $("#ticuenenvio").val("");
    $("#nombreenvio").val("");
    $("#nombre").val("");
    $("#identificacionenvio").val("");
    

    if(typeof banco !== "undefined"){
        $("#bancoenvio").val(banco);
        $("#bancoenvio").attr("nombre",banco);
        $("#ticuenenvio").val(tipodecuenta);
        $("#ticuenenvio").attr("nombre",tipodecuenta);
        $("#nombreenvio").val(nombre);
        $("#nombreenvio").attr("nombre",nombre);
        $("#identificacionenvio").val(identificacion);
        $("#identificacionenvio").attr("nombre",identificacion);
        
        $(".bloquecuen").attr("disabled","disabled");
    }
})

$("#cuentsenvios").focusin(function(){
    $(this).val("");
    $("#bancoenvio").removeAttr("nombre");
    $("#bancoenvio").val("");
    $("#ticuenenvio").removeAttr("nombre");
    $("#ticuenenvio").val("");
    $("#nombreenvio").removeAttr("nombre");
    $("#nombreenvio").val("");
    $("#identificacionenvio").removeAttr("nombre");
    $("#identificacionenvio").val("");
    $(".bloquecuen").removeAttr("disabled");
})

$("button").eq(1).on("click", function(e){
    e.preventDefault();
    validador = 0;

   /* var cuenta = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('cuenta');
    var banco = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('banco');
    var tipodecuenta = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('tipodecuenta');
    var nombre = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('nombre');
    var identificacion = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('identificacion');
    var usuariocuenta = $('#cuentasenvio [value="' + $("#cuentsenvios").val() + '"]').attr('usuario');*/
    if($("#cantidadenvio").val()==""){
        validador ++;
        $(".mensajeretiro").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(0).css("display","none");
        },5000)    
    }
    if($("#cantidadenvio").val()!="" && $.isNumeric($("#cantidadenvio").val()) == false){
        validador ++;
        $(".mensajeretiro").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(1).css("display","none");
        },5000)    
    }
    if($("#cuentsenvios").val()==""){
        validador ++;
        $(".mensajeretiro").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(2).css("display","none");
        },5000)    
    }
    if($("#bancoenvio").val()==""){
        validador ++;
        $(".mensajeretiro").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(3).css("display","none");
        },5000)    
    }
   /* if($("#ticuenenvio").val()==""){
        validador ++;
        $(".mensajeretiro").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(3).css("display","none");
        },5000)    
    }*/
    
    var tipocuentaenvio = $('#tipocuentaenvio [value="' + $("#ticuenenvio").val() + '"]').val();
    if(typeof tipocuentaenvio === "undefined"){
        validador ++;
        $(".mensajeretiro").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(4).css("display","none");
        },5000)    
    }

    if($("#nombreenvio").val()==""){
        validador ++;
        $(".mensajeretiro").eq(5).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(5).css("display","none");
        },5000)    
    }
    if($("#identificacionenvio").val()==""){
        validador ++;
        $(".mensajeretiro").eq(6).css("display","flex");
        setTimeout(function(){
            $(".mensajeretiro").eq(6).css("display","none");
        },5000)    
    }
    pais = $("select:eq(0) option:selected").attr("pais");
    moneda = $("select:eq(0) option:selected").attr("moneda");
    if(validador==0){
        $.ajax({
            url:"./../php/cajero/retirar.php",
            type: 'POST',
            data: {usuario:localStorage.usuario,tipousuario:localStorage.tipousuario,cantidad:$("#cantidadenvio").val(),cuenta:$("#cuentsenvios").val(),banco:$("#bancoenvio").val(),tipocuenta:tipocuentaenvio,nombre:$("#nombreenvio").val(),identificacion:$("#identificacionenvio").val(),pais:pais,moneda:moneda},
            beforeSend:function(){
                $(".imagensolicitud").css("display","flex");
                $(this).css("display","none");
            },
            complete:function(){
                $(".imagensolicitud").css("display","none");
                $(this).css("display","flex");
            },
            success:function(respuesta){
                json = JSON.parse(respuesta);
                if(json[1]=="correcto"){
                    $(".enviadoretiro").css("display","flex");
                    $("#cantidadenvio").val("");
                    $("#cuentsenvios").val("");
                    $("#bancoenvio").removeAttr("nombre");
                    $("#bancoenvio").val("");
                    $("#ticuenenvio").removeAttr("nombre");
                    $("#ticuenenvio").val("");
                    $("#nombreenvio").removeAttr("nombre");
                    $("#nombreenvio").val("");
                    $("#identificacionenvio").removeAttr("nombre");
                    $("#identificacionenvio").val("");
                    $(".bloquecuen").removeAttr("disabled");
                }else{
                    $(".errorretiro").css("display","flex");
                }
                
                setTimeout(function(){
                    $(".retiro").css("display","none")
                },2000)
            }
        })
    }
    

   /* if($("#cantidadeposito").val()!="" && $.isNumeric($("#cantidadeposito").val())!=false && typeof banco !== "undefined" && $(".file").val() != ""){
        
        $.ajax({
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
                console.log(JSON.parse(respuesta));
                   
            }
        })
    
    }*/
})

$("select").on("change",function(){
    $("#cuentasenvio").html("");
    $("#cuentascuentas").html("");
    if(localStorage.tipousuario=="administrador"){
        var usuariodatos = $("#usuari").val();
    }else{
         var usuariodatos = localStorage.usuario;
    }
    pais = $('select option:selected').attr('pais');
    moneda = $('select option:selected').attr('moneda');
    texto = $('select option:selected').text();
    $.ajax({
        url:"./../php/cajero/datos.php",
        type: 'POST',
        data: {usuario:usuariodatos,moneda:$('select option:selected').attr('moneda'),pais:$('select option:selected').attr('pais')},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(respuesta){
            json = JSON.parse(respuesta);

            monedas = "<option  pais='"+pais+"' moneda='"+moneda+"'>"+texto+"</option>";
            for(var i =0;i<json[0].length;i++){
                monedas += "<option  pais='"+json[0][i].pais+"' moneda='"+json[0][i].moneda+"'>"+json[0][i].nombre+"-"+json[0][i].moneda+"</option>";
            }
            $("select").html(monedas);
            cuentas = "";
            for(var i =0;i<json[1].length;i++){
                cuentas += "<option  banco='"+json[1][i].banco+"' cuenta='"+json[1][i].cuenta+"' tipodecuenta='"+json[1][i].tipodecuenta+"' nombre='"+json[1][i].nombre+"' identificacion='"+json[1][i].identificacion+"' usuario='"+json[1][i].usuario+"' value='"+json[1][i].banco+" - "+json[1][i].cuenta+"'>"+json[1][i].banco+" - "+json[1][i].cuenta+"</option>";
            }
            $("#cuentas").html(cuentas);
            cuentas = "";
            for(var i =0;i<json[2].length;i++){
                cuentas += "<option  banco='"+json[2][i].banco+"' cuenta='"+json[2][i].cuenta+"' tipodecuenta='"+json[2][i].tipodecuenta+"' nombre='"+json[2][i].nombre+"' identificacion='"+json[2][i].identificacion+"' usuario='"+json[2][i].usuario+"' value='"+json[2][i].cuenta+"'>"+json[2][i].banco+" - "+json[2][i].cuenta+"</option>";
            }
            $("#cuentasenvio").html(cuentas);
            tipocuenta = "";
            for(var i =0;i<json[3].length;i++){
                tipocuenta += "<option  value='"+json[3][i]+"'>"+json[3][i]+"</option>";
            }
            $("#tipocuentaenvio").html(tipocuenta);
            $("#entero").text(json[4][0].split(",")[0]);
            $("#decimal").text(","+json[4][0].split(",")[1]);
            $("#enterobtc").text(json[4][1].split(",")[0]);
            $("#decimalbtc").text(","+json[4][1].split(",")[1]);

        }
    });

})

datos();