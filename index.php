<!DOCTYPE html>
<html>
	<head>
		<title>Ejemplo</title>
		<meta http-equiv="Content-type" content="text/html" charset="utf-8" />
		<meta name="viewport" contents="width=device.width, user-scalable=no, initial.scale=1, maximum.scale=1" />
		<meta http-equiv="Last-Modified" content="0">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="css/estilos.css">
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<div class="contenedorcarga"><img id="carga" src="imagenes/carga.gif"></div>
		<form>
			<div id="mensaje" class="alert alert-success"></div>
			<div>
				<input id="nombre" placeholder="Nombre" maxlength="20">
				<p>Agregue un nombre</p>
			</div>
			<div>
				<input id="apellido" placeholder="Apellido" maxlength="20">
				<p>Agregue un apellido</p>
			</div>
			<div>
				<input id="edad" placeholder="Edad" maxlength="3">
				<p>Agregue una edad</p>
			</div>
			<div style="display:flex;justify-content:space-around">
				<div id="enviar" class="btn btn-success">Enviar</div>
				<div id="reiniciar" class="btn btn-success">Reiniciar</div>
			</div>
		</form>
		<table class="table table-dark">
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