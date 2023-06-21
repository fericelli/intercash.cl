$.ajax({
    url:"./../php/tasas/agragartasas.php",
    type: 'POST',
    data: {pais:JSON.stringify("")},
    beforeSend:function(){
        $("input").eq(0).css("display","none");
        $(".imgcarga").eq(0).css("display","flex");
        
    },
    complete:function(){
        $("input").eq(0).css("display","flex");
        $(".imgcarga").eq(0).css("display","none");
    },
    success:function(data){
        html = "";
        for(i=0;i<JSON.parse(data).length;i++){
            html += "<option pais='"+JSON.parse(data)[i].iso_pais+"' moneda='"+JSON.parse(data)[i].moneda+"' value='"+JSON.parse(data)[i].nombre+"   "+JSON.parse(data)[i].moneda+"'></option>";
       }
       $("#paisorigen").html(html); 
    }
});


$("#paisorig").on("change",function(){
    var pais = $('#paisorigen [value="' + $("#paisorig").val() + '"]').attr('pais');
    var moneda = $('#paisorigen [value="' + $("#paisorig").val() + '"]').attr('moneda');
    var valor = $('#paisorigen [value="' + $("#paisorig").val() + '"]').val();
    
    

    if(typeof pais !== "undefined"){
        $.ajax({
            url:"./../php/tasas/agragartasas.php",
            type: 'POST',
            data: {paisorigen:pais,moneda:moneda},
            beforeSend:function(){
                $("input").eq(1).css("display","none");
                $(".imgcarga").eq(1).css("display","flex");
                
            },
            complete:function(){
                $("input").eq(1).css("display","flex");
                $(".imgcarga").eq(1).css("display","none");
            },
            success:function(data){
                html = "";
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<option pais='"+JSON.parse(data)[i].iso_pais+"' moneda='"+JSON.parse(data)[i].moneda+"' value='"+JSON.parse(data)[i].nombre+"   "+JSON.parse(data)[i].moneda+"'></option>";
                }
                $("#paisdestino").html(html);
            }
        });
    }else{
        $(this).val("");
    }
})

$(".porcentajeganancia").on("change",function(){
    if($.isNumeric($(this).val())==false){
        $(this).val("");
        $(".mensaje-error").eq(2).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(2).css("display","none");
        },5000)
    }
})

$(".decimalestasa").on("change",function(){
    if($.isNumeric($(this).val())==false){
        $(this).val("");
        $(".mensaje-error").eq(4).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(4).css("display","none");
        },5000)
    }
})
$(".tasa").on("change",function(){
    if($.isNumeric($(this).val())==false){
        $(this).val("");
        $(".mensaje-error").eq(6).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(6).css("display","none");
        },5000)
    }
})



$(".icono-izquierda").on("click",function(){
    $(this).parent().remove();
    $("script").eq($("script").length-1).remove();
    $(".item").eq(1).trigger("click");
})

$("#agregar").on("click",function(){
    var monedaorigen = $('#paisorigen [value="' + $("#paisorig").val() + '"]').attr('moneda');
    var monedadestino = $('#paisdestino [value="' + $("#paisdesti").val() + '"]').attr('moneda');
    var paisorigen = $('#paisorigen [value="' + $("#paisorig").val() + '"]').attr('pais');
    var paisdestino = $('#paisdestino [value="' + $("#paisdesti").val() + '"]').attr('pais');
    validador = 0;
    if(typeof monedaorigen === "undefined"){
        validador ++;
        $(".mensaje-error").eq(0).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(0).css("display","none");
        },5000)
    }
    if(typeof monedadestino === "undefined"){
        validador ++;
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },5000)
    }
    if(typeof monedadestino === "undefined"){
        validador ++;
        $(".mensaje-error").eq(1).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(1).css("display","none");
        },5000)
    }

    if($(".porcentajeganancia").val()==""){
        validador ++;
        $(".mensaje-error").eq(3).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(3).css("display","none");
        },5000)
    }
    if($(".decimalestasa").val()==""){
        validador ++;
        $(".mensaje-error").eq(5).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(5).css("display","none");
        },5000)
    }

    if($(".tasa").val()==""){
        validador ++;
        $(".mensaje-error").eq(7).css("display","flex");
        setTimeout(function(){
            $(".mensaje-error").eq(7).css("display","none");
        },5000)
    }

    if(validador==0){
        datos = '{"monedaorigen":"'+monedaorigen+'","monedadestino":"'+monedadestino+'","porcentaje":"'+$(".porcentajeganancia").val()+'","decimalestasa":"'+$(".decimalestasa").val()+'","tasa":"'+$(".tasa").val()+'","paisorigen":"'+paisorigen+'","paisdestino":"'+paisdestino+'"}';
        
        json = JSON.parse(datos);
        $.ajax({
            url:"./../php/tasas/agragartasas.php",
            type: 'POST',
            data: {datos:JSON.stringify(json)},
            beforeSend:function(){
                $("input").eq(0).css("display","none");
                $(".imgcarga").eq(0).css("display","flex");
                
            },
            complete:function(){
                $("input").eq(0).css("display","flex");
                $(".imgcarga").eq(0).css("display","none");
            },
            success:function(data){
                if(JSON.parse(data)[1]=="error"){
                    $(".mensaje").eq(1).text(JSON.parse(data)[0]);
                    $(".mensaje").eq(1).css("display","flex");

                }else{
                    $("input").val("");
                    $(".mensaje").eq(0).text(JSON.parse(data)[0]);
                    $(".mensaje").eq(0).css("display","flex");
                    
                }
                setTimeout(function(){
                    $(".mensaje").css("display","none");
                },4000)
            }
        })
    }

})


