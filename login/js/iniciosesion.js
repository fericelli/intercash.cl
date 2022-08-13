$(document).on("ready",function(){
    $(".botons").on("click",function(){
        
        var validador = 0;
        
        if($("#usuario").val()==""){
            validador ++;
            $(".mensaje-error").eq(0).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(0).css("display","none");
            },5000)
        }
        if($("#clave").val()==""){
            validador ++;
            $(".mensaje-error").eq(1).css("display","flex");
            setTimeout(function(){
                $(".mensaje-error").eq(1).css("display","none");
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
                    $(".botons").css("display","");
                },
                success:function(respuesta){
                    json = JSON.parse(respuesta);
                    if(json[1]=="correcto"){
                        $(".mensaje-correcto").css("display","flex").text(json[0]);
                    }else{
                        $(".mensaje-error").eq(2).css("display","flex").text(json[0]);
                    }
                    setTimeout(function(){
                        $(".mensajesolicitud").css("display","none");
                    },5000)
                    
                }
            });
        }
    })
})