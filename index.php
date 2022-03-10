<!DOCTYPE html>
<html>
	<head>
		<title>Ejemplo</title>
		<meta http-equiv="Content-type" content="text/html" charset="utf-8" />
		<meta name="viewport" contents="width=device.width, user-scalable=no, initial.scale=1, maximum.scale=1" />
		<meta http-equiv="Last-Modified" content="0">
		<link rel="stylesheet" href="css/estilos.css">
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<div class="contenedorcarga"><img id="carga" src="imagenes/carga.gif"></div>
		<form>
			<input id="nombre" placeholder="Nombre" maxlength="20">
			<input id="apellido" placeholder="Apellido" maxlength="20">
			<input id="edad" placeholder="Edad" maxlength="3">
			<button>enviar</button>
		</form>
		<table>
			<thead>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Edad</th>
			</thead>
			<tbody>
			</tbody>
		</table>
	</body>
</html>