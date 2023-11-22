$("#calcular").on("click",function(){
    datos = '{"monedaenvio":{"iso_pais":"'+$( "select option:selected").attr("pais")+'","moneda":"'+$( "select option:selected").attr("moneda")+'","tasausdt":"'+$("#tasacompra").val()+'","nombre":"'+$( "select option:selected").attr("nombre")+'","devaluacion":"'+$( "select option:selected").attr("devaluacion")+'","decimalesmoneda":"'+$( "select option:selected").attr("decimalesmoneda")+'"},"monedasdestino":[';


    
    for(i=0;i<$("tbody tr").length;i++){

        datos += '{"pais":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[0]+'","moneda":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[1]+'","ganancia":"'+$("tbody tr:eq("+i+") input").eq(0).val()+'","tasausdt":"'+$("tbody tr:eq("+i+") input").eq(1).val()+'","decimalestasa":"'+$("tbody tr:eq("+i+") input").eq(3).val()+'"},'; 

    }
    datos = datos.substr(0,datos.length-1)+"]}";
    
   json = JSON.parse(datos);

   
   $.ajax({
        url:"./../php/tasas/calculartasas.php",
        type: 'POST',
        data: {datos:JSON.stringify(json)},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            JSON.parse(data);
            for(i=0;i<JSON.parse(data).length;i++){
                $("tbody tr:eq("+i+") td:eq(3) p").text(JSON.parse(data)[i]); 
            }
        
        }
    });


})

$("#actualizar").on("click",function(){
    datos = '{"monedaenvio":{"iso_pais":"'+$( "select option:selected").attr("pais")+'","moneda":"'+$( "select option:selected").attr("moneda")+'","tasausdt":"'+$("#tasacompra").val()+'","nombre":"'+$( "select option:selected").attr("nombre")+'","devaluacion":"'+$( "select option:selected").attr("devaluacion")+'","decimalesmoneda":"'+$( "select option:selected").attr("decimalesmoneda")+'"},"monedasdestino":[';


    for(i=0;i<$("tbody tr").length;i++){

        datos += '{"pais":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[0]+'","moneda":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[1]+'","ganancia":"'+$("tbody tr:eq("+i+") input").eq(0).val()+'","tasausdt":"'+$("tbody tr:eq("+i+") input").eq(1).val()+'","decimalestasa":"'+$("tbody tr:eq("+i+") input").eq(3).val()+'","tasa":"'+$("tbody tr:eq("+i+") input").eq(2).val()+'"},'; 

    }
    datos = datos.substr(0,datos.length-1)+"]}";
    
   json = JSON.parse(datos);

   
   $.ajax({
        url:"./../php/tasas/actualizartasas.php",
        type: 'POST',
        data: {datos:JSON.stringify(json)},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            $("h2").text(JSON.parse(data)[0]);
                
            
            setTimeout(function(){
                $("h2").text("");  

            },1000)
           
        }
    });
})

$("#agregar").on("click",function(){
    html = "<div style='margin:auto;width:100%;display:flex'>";
    html += "<div class='salir icono-izquierda' style='cursor:pointer;border-radius:50%;padding:9px;background-color:rgb(52,52,52);color:#fff;position:absolute;right:2px'></div>";
    html += '<section class="form-register"><h4>Agregar tasas</h4>';
    html += '<label class="margen">Seleccione el pais origen</label>'; 
    html += '<input class="controls" id="paisorig" list="paisorigen" type="text"  placeholder="Seleccione un pais">';
    html += '<datalist id="paisorigen"></datalist>';
    html += '<img class="imgcarga" src="imagenes/carga.gif">';
    html += '<label class="mensaje-error">Seleccione un pais</label>';
    html += '<label class="margen">Seleccione el pais destino</label>'; 
    html += '<input class="controls" id="paisdesti" list="paisdestino" type="text"  placeholder="Seleccione un pais">';
    html += '<datalist id="paisdestino"></datalist>';
    html += '<img class="imgcarga" src="imagenes/carga.gif">';
    html += '<label class="mensaje-error">Seleccione un pais</label>';
    html += '<label class="margen">Ingrece el porcentaje</label>'; 
    html += '<input class="controls porcentajeganancia" type="text"  placeholder="Ingrece el pocentaje">';
    html += '<label class="mensaje-error">Debe ser numero</label>';
    html += '<label class="mensaje-error">Ingrece el pocentaje</label>';
    html += '<label class="margen">Ingrece los decimales de la tasa</label>'; 
    html += '<input class="controls decimalestasa" type="text"  placeholder="Ingrece los decimales de la tasa">';
    html += '<label class="mensaje-error">Debe ser numero</label>';
    html += '<label class="mensaje-error">Ingrece los decimales de la tasa</label>';
    html += '<label class="margen">Ingrece la tasa</label>'; 
    html += '<input class="controls tasa" type="text"  placeholder="Ingrece  la tasa">';
    html += '<label class="mensaje-error">Debe ser numero</label>';
    html += '<label class="mensaje-error">Ingrece la tasa</label>';

    html += '<div id="agregar" class="botons">Agregar</div>';
    html += '<img class="imgcarga" src="imagenes/carga.gif">';
    html += '<label class="mensaje-correcto mensaje"></label>';
    html += '<label class="mensaje-error mensaje"></label>';
    html += '</div>';
    html += "</section></div>";
    html += '<script type="text/javascript" src="./../js/agregartasa.js"></script>';
    $("#main-container").html(html)
})

$("select").on("change",function(){
    pais = $("option:selected", this).attr("pais");
    moneda = $("option:selected", this).attr("moneda");
    $.ajax({
        url:"./../php/tasas/datos.php",
        type: 'POST',
        data: {pais:pais,moneda:moneda},
        beforeSend:function(){
            $(".contenido-imagen").css("display","flex");
        },
        complete:function(){
            $(".contenido-imagen").css("display","none");
        },
        success:function(data){
            $("#tasacompra").attr("tasa",JSON.parse(data)[0][0].usdt); 
            $("#tasacompra").val(JSON.parse(data)[0][0].usdt);  
            $("#usd").text(JSON.parse(data)[0][0].usd);
            JSON.parse(data);
            
            html = "";
            for(i=0;i<JSON.parse(data)[1].length;i++){
                html += "<tr><td>"+JSON.parse(data)[1][i].iso_pais+"/"+JSON.parse(data)[1][i].moneda+"</td><td style='display:flex'><p>%</p><input value='"+JSON.parse(data)[1][i].tasasporcentaje+"'></td><td><input type='text' value='"+JSON.parse(data)[1][i].usdt+"'><p>"+JSON.parse(data)[1][i].usd+"</p></td><td><input type='text' value='"+JSON.parse(data)[1][i].tasa+"'><p>"+JSON.parse(data)[1][i].tasasugerida+"</p></td><td ><input type='text' value='"+JSON.parse(data)[1][i].decimalestasa+"'></td></tr>"
            }
            
            
            $("tbody").html(html);
        }
    });
})