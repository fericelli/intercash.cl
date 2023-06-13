$.ajax({
    url:"./../php/tasas/datos.php",
        type: 'POST',
        data: {usuario:localStorage.usaurio},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(respuesta){
           // json = JSON.parse(respuesta);

            

        }
})