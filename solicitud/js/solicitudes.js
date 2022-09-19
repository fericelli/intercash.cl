 $(window).scroll(function(){
    
        if($("html").height()-$("html").scrollTop()<=687){
            registros()
        }
    
    })
    
    
    function registros(){
        $.ajax({
            url:"./../solicitud/php/intercambios/solicitudes.php",
            type: 'POST',
            data: {usuario:localStorage.getItem("usuario"),cantidad:$("tbody tr").length},
            beforeSend:function(){
                $(".imagencargasolicitud").css("display","flex");
            },
            complete:function(){
                $(".imagencargasolicitud").css("display","none");
                $(".eliminarsolicitud").on("click",function(){
                    index= $(".eliminarsolicitud").index(this);
                    registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
                    $.ajax({
                        url:"./../solicitud/php/intercambios/eliminar.php",
                        type: 'POST',
                        data: {usuario:localStorage.getItem("usuario"),momento:registro},
                        beforeSend:function(){
                            $("tbody tr:eq("+index+") td:eq(3) div").css("display","none");
                            $("tbody tr:eq("+index+") td:eq(3)").append('<img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif">');
                        },
                        complete:function(){
                            $("tbody tr:eq("+index+") td:eq(3) div").css("display","flex");
                            $("tbody tr:eq("+index+") td:eq(3) img").remove();
                        },
                        success:function(data){
                            if(JSON.parse(data)[0]=="si"){
                                $("tbody tr:eq("+index+")").remove();
                            }
                            
                        }
                    })
                    
                })
            },
            success:function(data){
                html = "";
                for(i=0;i<JSON.parse(data).length;i++){
                    html += "<tr><td>"+JSON.parse(data)[i].cantidadenviar+" "+JSON.parse(data)[i].monedaorigen+"</td><td>"+JSON.parse(data)[i].cantidadrecibir+" "+JSON.parse(data)[i].monedadestino+"</td><td>";
                    
                    dato = JSON.parse(data)[i].datos.split(".");
    
                    for(j=0;j<dato.length;j++){
                        html += dato[j]+"<br>";
                    }
                    html += "</td>";
                    
                    if(JSON.parse(data)[i].estado=="pendiente"){
                        html += "<td registro='"+JSON.parse(data)[i].momento+"'><div style='cursor:pointer;margin: auto; width:30px;height:30px'  class='iconos icono-borrar eliminarsolicitud' title='Cancelar'></div></td>"; 
                    }else{
                        html += '<td><img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif" title="Procesando"></td>';
                    }
                    html += "</tr>";
                
                }
                
                $("tbody").html(html);
            }
        });
    }

    $(".eliminarsolicitud").on("click",function(){
        
        index= $(".eliminarsolicitud").index(this);
        registro = $("tbody tr:eq("+index+") td:eq(3)").attr("registro");
        console.log(index);
        $.ajax({
            url:"./../solicitud/php/intercambios/eliminar.php",
            type: 'POST',
            data: {usuario:localStorage.getItem("usuario"),momento:registro},
            beforeSend:function(){
                $("tbody tr:eq("+index+") td:eq(3) div").css("display","none");
                $("tbody tr:eq("+index+") td:eq(3)").append('<img style="margin: auto; width:30px;height:30px" src="../imagenes/carga.gif">');
            },
            complete:function(){
                $("tbody tr:eq("+index+") td:eq(3) div").css("display","flex");
                $("tbody tr:eq("+index+") td:eq(3) img").remove();
                           
            },
            success:function(data){
                if(JSON.parse(data)[0]=="si"){
                    $("tbody tr:eq("+index+")").remove();
                }
            }
        })
        
    })