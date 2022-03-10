$(document).on("ready",function(){
	cargardatos();
	validarmumero();
	$("#enviar").on("click",function(){
		
		if($("#nombre").val()==""){
			$("p").eq(0).css("display","flex");
		}
		
		if($("#apellido").val()==""){
			$("p").eq(1).css("display","flex");
		}
		
		if($("#edad").val()==""){
			$("p").eq(2).css("display","flex");
		}
		setTimeout(function(){
			$("p").css("display","none");
		},3000);
		if($("#nombre").val()!="" && $("#apellido").val()!="" && $("#edad").val()!=""){
			$.ajax({
				url:'cargardatos.php',
				type:"POST",
				data:{nombre:$("#nombre").val(),apellido:$("#apellido").val(),edad:$("#edad").val()},
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
					$("#mensaje").text(objeto[0]);
					setTimeout(function(){
						$("#mensaje").css("display","none");
					},2000)
				},
				complete:function(){
					$("#mensaje").css("display","flex");
					$(".contenedorcarga").css("display","none");
					cargardatos();
					$("input").val("");
				}
			})
		}
	})
	
	$("#reiniciar").on("click",function(){
		$.ajax({
			url:'reinicar.php',
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
				
					$("#mensaje").text(objeto[0]);
					setTimeout(function(){
						$("#mensaje").css("display","none");
					},2000)
				
			},
			complete:function(){
				$("#mensaje").css("display","flex");
				cargardatos();
				$(".contenedorcarga").css("display","none");
			}
		})
	})
})

function validarmumero(){
	$("#edad").keyup(function(){
		if(!$.isNumeric($(this).val())){
			$(this).val("");
		}
	})
}

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
				//console.log(respuesta);
				var objeto = JSON.parse(respuesta);
				//console.log(objeto);
				html = "";
				for(var i = 0;i<objeto.length;i++){
					html += "<tr><td>"+objeto[i].nombre+"</td><td>"+objeto[i].apellido+"</td><<td>"+objeto[i].edad+"</td>/tr>";
				}
				if(html!=""){
					$("tbody").html(html);
				}else{
					$("tbody").html("<tr><td colspan='3'>Ningun datos almacenado</td>/tr>")
				}
				
			},
			complete:function(){
				$(".contenedorcarga").css("display","none");
			}
	})
}
