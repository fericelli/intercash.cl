$(document).on("ready",function(){
    datos = "[";
    for(i=0;i<$("table tbody tr").length;i++){
        datos += '"'+$("table tbody tr:eq("+i+") td:eq(1) a").attr("title")+'",';
    }
    datos = datos.substr(0,datos.length-1)+"]";
    
   json = JSON.parse(datos);
    $.ajax({
        url:"../php/configuracion/agregarbancos.php",
        type: 'POST',
        data: {datos:JSON.stringify(json),pais:"MX"},
        beforeSend:function(){
            $(".imgcarga").eq(0).css("display","flex");
            
        },
        complete:function(){
            $(".imgcarga").eq(0).css("display","none");
        },
        success:function(datos){
           console.log(JSON.parse(datos)); 
        }
    })
})