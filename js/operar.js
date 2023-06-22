$.ajax({
    url:"../php/monedas/monedaoperaciones.php",
    type:"POST",
    data:{moneda:$("#enviar").attr("moneda")},
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
        $("#monedaintercambio").html(html);
        html = "";
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += '<option moneda="'+JSON.parse(data)[1][i].moneda+'" decimal="'+JSON.parse(data)[1][i].decimales+'" value="'+JSON.parse(data)[1][i].nombre+'" >'+JSON.parse(data)[1][i].nombre+'</option>';
        }
        $("#monedacompra").html(html);      
    }
})

