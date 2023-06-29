$(document).on("ready",function(){
    datos = "[";
    for(i=0;i<$("a").length;i++){
        datos += '"'+$("a").eq(i).attr("title")+'",';
    }
    datos = datos.substr(0,datos.length-1)+"]";
    
   json = JSON.parse(datos);
   console.log(json);
   $.ajax({
        url:"../php/configuracion/agregarbancos.php",
        type: 'POST',
        data: {datos:JSON.stringify(json),pais:"BR"},
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