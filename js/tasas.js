/*$.ajax({
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
            json = JSON.parse(respuesta);
            console.log(json);
            html = "";
            
            

        }
})*/




$("#calcular").on("click",function(){
    datos = '{"monedaenvio":{"iso_pais":"'+$( "select option:selected").attr("pais")+'","moneda":"'+$( "select option:selected").attr("moneda")+'","tasausdt":"'+$("#tasacompra").val()+'","nombre":"'+$( "select option:selected").attr("nombre")+'","devaluacion":"'+$( "select option:selected").attr("devaluacion")+'","decimalesmoneda":"'+$( "select option:selected").attr("decimalesmoneda")+'"},"monedasdestino":[';


    console.log($("tbody tr").length);
    for(i=0;i<$("tbody tr").length;i++){

        datos += '{"pais":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[0]+'","moneda":"'+$("tbody tr:eq("+i+") td").eq(0).text().split("/")[1]+'","ganancia":"'+$("tbody tr:eq("+i+") input").eq(0).val()+'","tasausdt":"'+$("tbody tr:eq("+i+") input").eq(1).val()+'","decimalestasa":"'+$("tbody tr:eq("+i+") input").eq(3).val()+'"},'; 

    }
    datos = datos.substr(0,datos.length-1)+"]}";
    
   json = JSON.parse(datos);

   
   console.log(json);
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
        console.log(data);
       /* html = "<div class='barrafiltros'>";

        html += '<select style="width:100px;font-size:20px;margin:auto">';
        for(i=0;i<JSON.parse(data)[0].length;i++){
            console.log(JSON.parse(data)[0][i].moneda);
            html += '<option moneda="'+JSON.parse(data)[0][i].moneda+'" pais="'+JSON.parse(data)[0][i].iso_pais+'">'+JSON.parse(data)[0][i].moneda+" "+JSON.parse(data)[0][i].nombre+'</option>'; 
        }
        html += '</select>'; 
        html += '<div style="display:flex;flex-direction:column"><label>Tasa USDT</label><input style="width:100px;font-size:20px;margin:auto" type="text" id="tasacompra" tasa="'+JSON.parse(data)[0][0].usdt+'" value="'+JSON.parse(data)[0][0].usdt+'"><div style="display:flex;flex-direction:row"><h5>USD : </h5><p>'+JSON.parse(data)[0][0].usd+'</p></div></div>';
        html += '<div id="calcular" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Calcular</div></div>'
        html += '<div id="agregar" style="height:35px; padding:0px 10px;margin:auto;cursor:pointer;border:1px solid #000;display:flex" ><i class="icono-bitcoin"></i><div>Actualizar</div></div>'
        
        html += '</div>';
        html += "<div class='table-responsive'><h2></h2><table class='table table-striped table-sm'><thead><tr><th scope='col'>Pais/Moneda</th><th scope='col'>% Ganancia</th><th scope='col'>Tasa Usdt/USD</th><th scope='col'>Tasa Envio/Sujerida</th><th scope='col'>Decimales tasa</th></tr></thead><tbody>";
        for(i=0;i<JSON.parse(data)[1].length;i++){
            html += "<tr><td>"+JSON.parse(data)[1][i].iso_pais+"/"+JSON.parse(data)[1][i].moneda+"</td><td style='display:flex'><p>%</p><input value='"+JSON.parse(data)[1][i].tasasporcentaje+"'></td><td><input type='text' value='"+JSON.parse(data)[1][i].usdt+"'><p>"+JSON.parse(data)[1][i].usd+"</p></td><td><input type='text' value='"+JSON.parse(data)[1][i].tasa+"'><p>"+JSON.parse(data)[1][i].tasasugerida+"</p></td><td ><input type='text' value='"+JSON.parse(data)[1][i].decimalestasa+"'></td></tr>"
        }
        html += '</tbody></table></div><center><script src="./../js/tasas.js"></script>';
        
        $("#main-container").html(html);*/
    }
});


})