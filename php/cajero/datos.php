<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			session_start();
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->monedas().",".$this->cuentas().",".$this->cuentasretiro().",".$this->tipodecuenta().",".$this->saldo().",".$this->bancos().",".$this->pagodividido()."]";
			$this->Conexion->CerrarConexion();
		}
		private function monedas(){
			$retorno = "[";
			
			if(isset($_POST["pais"])){
				$consulta2 = $this->Conexion->Consultar("SELECT iso2,iso_moneda,decimalesmoneda,nombremoneda FROM paises WHERE iso2<>'".$_POST["pais"]."' AND receptor IS NOT NULL");
				
			}else{
				$consultar2 = $this->Conexion->Consultar("SELECT iso2,iso_moneda,decimalesmoneda,nombremoneda FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($informacion = $this->Conexion->Recorrido($consultar2)){
					$retorno .= '{"pais":"'.$informacion[0].'","moneda":"'.$informacion[1].'","decimales":"'.$informacion[2].'","nombre":"'.$informacion[3].'"},';
					$consulta2 = $this->Conexion->Consultar("SELECT iso2,iso_moneda,decimalesmoneda,nombremoneda FROM paises WHERE iso2<>'".$informacion[0]."' AND receptor IS NOT NULL");
				}
			}
            while($datos1 = $this->Conexion->Recorrido($consulta2)){
                
                $retorno .= '{"pais":"'.$datos1[0].'","moneda":"'.$datos1[1].'","decimales":"'.$datos1[2].'","nombre":"'.$datos1[3].'"},';
            }
            return substr($retorno,0,strlen($retorno)-1)."]";
        } 

		private function cuentas(){
			$retorno = "[";
			if(isset($_POST["pais"])){
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$_POST["pais"]."' AND tipo='deposito'");
				while($cuenta = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
				}
			}else{
				//return "SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'";
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
            	if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1["iso2"]."' AND tipo='deposito'");
					while($cuenta = $this->Conexion->Recorrido($consultar)){
						$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
					}
                }
			}
				
			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."]";
			}
		}

		private function cuentasretiro(){
			$retorno = "[";

			if(isset($_POST["pais"])){
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$_POST["pais"]."' AND usuario='".$_POST["usuario"]."' AND tipo='retiro'");
               	while($cuenta = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
				}
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
            	if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1["iso2"]."' AND usuario='".$_POST["usuario"]."' AND tipo='retiro'");
               		while($cuenta = $this->Conexion->Recorrido($consultar)){
						$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
					}
                }
			}
			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."]";
			}
		}
		private function tipodecuenta(){
			$retorno = "[";
			if(isset($_POST["pais"])){
				$consultar = $this->Conexion->Consultar("SELECT * FROM tiposdecuentas WHERE pais ='".$_POST["pais"]."'");
				while($tiposdecuentas = $this->Conexion->Recorrido($consultar)){
					$retorno .= '"'.$tiposdecuentas[1].'",';
				}
					
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM tiposdecuentas WHERE pais ='".$datos1["iso2"]."'");
					while($tiposdecuentas = $this->Conexion->Recorrido($consultar)){
						$retorno .= '"'.$tiposdecuentas[1].'",';
					}
					
				}
			}

			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."]";
			}
		}
		private function saldo(){
			$retorno = "[";
			$ganancia = 0;
			$gananciabtc = 0;
			$pais = "";
			$moneda = "";
			
			$dauda = 0;
			$pago = 0;
			$decimalesmoneda = 0;
			$sql = "SELECT * FROM paises WHERE receptor IS NOT NULL GROUP BY iso_moneda";	
			if(isset($_POST["pais"])){
				$pais = $_POST["pais"];
				$moneda = $_POST["moneda"];
				
				
			}else{
				$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($usuarios = $this->Conexion->Recorrido($consultar)){
					$pais = $usuarios["pais"];
					$moneda = $usuarios["iso_moneda"];
				}
			}
			$consultar2 =  $this->Conexion->Consultar($sql);
			while($datos = $this->Conexion->Recorrido($consultar2)){
				$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM pagos WHERE usuario='".$_POST["usuario"]."' AND monedascambiadas LIKE '%".$datos["iso_moneda"]."%' ORDER BY momento DESC LIMIT 1"))[0];

				if($fecha==""){
					$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM intercambios WHERE intermediario='".$_POST["usuario"]."' AND monedacompra='".$datos["iso_moneda"]."'"))[0];
				}

				if($fecha==""){
					$fecha = date("Y-m-d 00:00:00");
				}

				$consultar4 = $this->Conexion->Consultar("SELECT monedacompra,SUM(montocompra),usuarios.porcentaje FROM intercambios LEFT JOIN usuarios ON usuario=intermediario WHERE intermediario='".$_POST["usuario"]."' AND momento >= '".$fecha."' AND monedacompra='".$datos["iso_moneda"]."' GROUP BY monedacompra");
				$cantidad = $this->Conexion->Recorrido($consultar4);
				
				//return  "SELECT monedacompra,SUM(montocompra),usuarios.porcentaje,decimalesmoneda FROM intercambios LEFT JOIN usuarios ON usuario=intermediario WHERE intermediario='".$_POST["usuario"]."' AND momento >= '".$fecha."' AND monedacompra='".$datos["iso_moneda"]."' GROUP BY monedacompra"."sddsdsdsds";
				$pago = $cantidad[1]*$cantidad[2];
				$pago = $pago/100;
				if($datos["iso_moneda"]==$moneda){
					$dauda += $pago;
				}else{
					$consultar3 = $this->Conexion->Consultar("SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$moneda."' AND monedacompra='".$datos["iso_moneda"]."'");
					//return "SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$_POST["moneda"]."' AND monedacompra='".$datos[0]."'";
					$tasa = $this->Conexion->Recorrido($consultar3)[0];
						
					$pago = $pago*$tasa;
					$dauda += $pago;
						//return "SELECT * FROM tasas WHERE monedaventa='".$usuarios["iso_moneda"]."' AND monedacompra='".$datos[0]."'";
				}
			}
			$tasas = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$moneda."'"));
			//$tasas[0];
			$usd = $tasas[0];//$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
			$ganancia = $dauda;
			$gananciabtc = $ganancia/$usd;
			$consultarmoneda = $this->Conexion->Consultar("SELECT decimalesmoneda FROM paises WHERE iso2='".$pais."' AND iso_moneda='".$moneda."'");
			$decimalesmoneda = $this->Conexion->Recorrido($consultarmoneda)[0];
			
			$retorno .= '"'.number_format($ganancia, $decimalesmoneda, ',', '.').'",';
			$retorno .= '"'.number_format(  $gananciabtc    , 2, ',', '.'  ).'",';
			$retorno .= '"'.number_format(  $ganancia    , $decimalesmoneda, '.', '').'"';
			return $retorno."]";
			
		}
		private function bancos(){
			$retorno = "[";
			if(isset($_POST["pais"])){
				$consultar = $this->Conexion->Consultar("SELECT nombre FROM bancos WHERE pais ='".$_POST["pais"]."'");
				while($bancos = $this->Conexion->Recorrido($consultar)){
					$retorno .= '"'.$bancos[0].'",';
				}
					
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT nombre FROM bancos WHERE pais ='".$datos1["iso2"]."'");
					while($bancos = $this->Conexion->Recorrido($consultar)){
						$retorno .= '"'.$bancos[0].'",';
					}
					
				}
			}

			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."]";
			}
		}
		private function pagodividido(){
			$retorno = "[";
			$ganancia = 0;
			$gananciabtc = 0;
			$pais = "";
			$moneda = "";
			
			$dauda = 0;
			$pago = 0;
			$decimalesmoneda = 0;
			$sql = "SELECT * FROM paises WHERE receptor IS NOT NULL GROUP BY iso_moneda";	
			
			if(isset($_POST["pais"])){
				$pais = $_POST["pais"];
				$moneda = $_POST["moneda"];
				
				
			}else{
				$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($usuarios = $this->Conexion->Recorrido($consultar)){
					$pais = $usuarios["pais"];
					$moneda = $usuarios["iso_moneda"];
				}
			}
			$consultar2 =  $this->Conexion->Consultar($sql);
			while($datos = $this->Conexion->Recorrido($consultar2)){
				$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM pagos WHERE usuario='".$_POST["usuario"]."' AND monedascambiadas LIKE '%".$datos["iso_moneda"]."%' ORDER BY momento DESC LIMIT 1"))[0];

				if($fecha==""){
					$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM intercambios WHERE intermediario='".$_POST["usuario"]."' AND monedacompra='".$datos["iso_moneda"]."'"))[0];
				}

				if($fecha==""){
					$fecha = date("Y-m-d 00:00:00");
				}

				$consultar4 = $this->Conexion->Consultar("SELECT monedacompra,SUM(montocompra),usuarios.porcentaje FROM intercambios LEFT JOIN usuarios ON usuario=intermediario WHERE intermediario='".$_POST["usuario"]."' AND momento >= '".$fecha."' AND monedacompra='".$datos["iso_moneda"]."' GROUP BY monedacompra");
				$cantidad = $this->Conexion->Recorrido($consultar4);
				
				$pago = $cantidad[1]*$cantidad[2];
				$pago = $pago/100;
				if($datos["iso_moneda"]!=$moneda){
					$consultar3 = $this->Conexion->Consultar("SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$moneda."' AND monedacompra='".$datos["iso_moneda"]."'");
					$tasa = $this->Conexion->Recorrido($consultar3)[0];
						
					$pago = $pago*$tasa;
				}
				$consultarmoneda = $this->Conexion->Consultar("SELECT decimalesmoneda FROM paises WHERE iso2='".$pais."' AND iso_moneda='".$moneda."'");
				$decimalesmoneda = $this->Conexion->Recorrido($consultarmoneda)[0];
				if($pago>0){
					$retorno .= '["'.$datos["iso_moneda"].'","'.number_format($pago, $decimalesmoneda, '.', '').'"],';
				}
			}
			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."]";
			}
			
		}
	}
	new Datos();
?>