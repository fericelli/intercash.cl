function datos(){
    $.ajax({
        url:"./../php/cajero/datos.php",
        type: 'POST',
        data: {usuario:localStorage.usuario,tipousuario:localStorage.tipousuario},
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
                monedas += "<option  pais='"+json[0][i].pais+"'  >"+json[0][i].nombre+"-"+json[0][i].moneda+"</option>";
            }
            $("select").html(monedas);
            cuentas = "";
            for(var i =0;i<json[1].length;i++){
                cuentas += "<option  banco='"+json[1][i].banco+"' cuenta='"+json[1][i].cuenta+"' tipodecuenta='"+json[1][i].tipodecuenta+"' nombre='"+json[1][i].nombre+"' identificacion='"+json[1][i].identificacion+"' usuario='"+json[1][i].usuario+"' value='"+json[1][i].banco+" - "+json[1][i].cuenta+"'>"+json[1][i].banco+" - "+json[1][i].cuenta+"</option>";
            }
            $("#cuentas").html(cuentas);
            
        }
    });
}

datos();
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

$("button").eq(0).on("click", function(){
    var banco = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('banco');
    var cuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('cuenta');
    var tipodecuenta = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('tipodecuenta');
    var nombre = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('nombre');
    var identificacion = $('#cuentas [value="' + $("#cuent").val() + '"]').attr('identificacion');
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
        
    }
})