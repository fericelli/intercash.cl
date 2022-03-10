$(document).on("ready",function(){
	cargardatos();
	$("button").on("click",function(){
		$.ajax({
			url:'cargardatos.php',
			beforeSend:function(respuesta){
				$(".contenedorcarga").css("display","flex");
			},
			error:function(){
				alert("Ocurrio un error");
				$(".contenedorcarga").css("display","none");
			},
			success:function(respuesta){
				
			},
			complete:function(){
				$(".contenedorcarga").css("display","none");
				cargardatos();
			}
	})
	})
})

function cargardatos(){
	$.ajax({
			url:'datos.php',
			beforeSend:function(respuesta){
				$(".contenedorcarga").css("display","flex");
			},
			error:function(){
				alert("Ocurrio un error");
				$(".contenedorcarga").css("display","none");
			},
			success:function(respuesta){
				console.log(respuesta);
				var objeto = JSON.parse(respuesta);
				console.log(objeto);
				html = "";
				for(var i = 0;i<objeto.length;i++){
					html += "<tr><td>"+objeto[i].nombre+"</td><td>"+objeto[i].apellido+"</td><<td>"+objeto[i].edad+"</td>/tr>";
				}
				$("tbody").html(html);
			},
			complete:function(){
				$(".contenedorcarga").css("display","none");
			}
	})
}