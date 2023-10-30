<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			session_start();
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->monedas().",".$this->cuentas().",".$this->cuentasretiro().",".$this->tipodecuenta().",".$this->saldo()."]";
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
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$_POST["pais"]."' AND tipo='pago'");
				while($cuenta = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
				}
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
            	if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1[0]."' AND tipo='pago'");
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
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$_POST["pais"]."' AND usuario='".$_POST["usuario"]."' AND tipo='envio'");
               	while($cuenta = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
				}
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
            	if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1["iso2"]."' AND usuario='".$_POST["usuario"]."' AND tipo='envio'");
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
					$consultar = $this->Conexion->Consultar("SELECT * FROM tiposdecuentas WHERE pais ='".$datos1[0]."'");
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
			$consultar = $this->Conexion->Consultar("SELECT * FROM pagos WHERE usuario='".$_POST["usuario"]."' ORDER BY momento DESC LIMIT 1");
			$fechapago = "";
			///$fecha = $this->Conexion->Recorrido($consultar)[0];
			if($fecha1 = $this->Conexion->Recorrido($consultar)){
				 $fechapago = " AND solicitud > '".$fecha1["momento"]."'";
			}
			$dauda = 0;
			$pago = 0;
			$decimalesmoneda = 0;
			if(isset($_POST["pais"])){

				$sql = "SELECT monedacompra,SUM(montocompra),usuarios.porcentaje,decimalesmoneda FROM intercambios LEFT JOIN usuarios ON usuario=intermediario LEFT JOIN paises ON paises.iso_moneda=intercambios.monedacompra AND paises.receptor IS NOT NULL WHERE intermediario='".$_POST["usuario"]."' ".$fechapago."  GROUP BY monedacompra ORDER BY intercambios.solicitud DESC";
					
				$consultar2 =  $this->Conexion->Consultar($sql);
				
				while($datos = $this->Conexion->Recorrido($consultar2)){
					$pago = $datos[1]*$datos[2];
					$pago = $pago/100;
					if($datos[0]==$_POST["moneda"]){
						$dauda += $pago;
					}else{
						$consultar3 = $this->Conexion->Consultar("SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$_POST["moneda"]."' AND monedacompra='".$datos[0]."'");
						//return "SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$_POST["moneda"]."' AND monedacompra='".$datos[0]."'";
						$tasa = $this->Conexion->Recorrido($consultar3)[0];
						
						 $pago = $pago*$tasa;
						$dauda += $pago;
						//return "SELECT * FROM tasas WHERE monedaventa='".$usuarios["iso_moneda"]."' AND monedacompra='".$datos[0]."'";
					}

				}
					
				$usd =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["moneda"]))->{'data'};
				$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
				$ganancia = $dauda;
				$gananciabtc = $ganancia/$usd;
				$gananciabtc = $gananciabtc/$btcprecio;
				$consultarmoneda = $this->Conexion->Consultar("SELECT decimalesmoneda FROM paises WHERE iso2='".$_POST["pais"]."' AND iso_moneda='".$_POST["moneda"]."'");
				$decimalesmoneda = $this->Conexion->Recorrido($consultarmoneda)[0];
			}else{
				$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND usuarios.usuario='".$_POST["usuario"]."'");
				if($usuarios = $this->Conexion->Recorrido($consultar)){
					$sql = "SELECT monedacompra,SUM(montocompra),usuarios.porcentaje,decimalesmoneda FROM intercambios LEFT JOIN usuarios ON usuario=intermediario LEFT JOIN paises ON paises.iso_moneda=intercambios.monedacompra AND paises.receptor IS NOT NULL WHERE intermediario='".$_POST["usuario"]."' ".$fechapago." GROUP BY monedacompra ORDER BY intercambios.solicitud DESC";
					
					$consultar2 =  $this->Conexion->Consultar($sql);
					
					while($datos = $this->Conexion->Recorrido($consultar2)){
						
						$pago = $datos[1]*$datos["porcentaje"];
						$pago = $pago/100;
						if($datos[0]==$usuarios["iso_moneda"]){
							$dauda += $pago;
						}else{
							$consultar3 = $this->Conexion->Consultar("SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$usuarios["iso_moneda"]."' AND monedacompra='".$datos[0]."'");
							 $tasa = $this->Conexion->Recorrido($consultar3)[0];
							 $pago = $pago*$tasa;
							$dauda += $pago;
						}

					}
					$usd = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$usuarios["iso_moneda"]))->{'data'};
					$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
					$ganancia = $dauda;
					$gananciabtc = $ganancia/$usd;
					$gananciabtc = $gananciabtc/$btcprecio;	

					$consultarmoneda = $this->Conexion->Consultar("SELECT decimalesmoneda FROM paises WHERE iso2='".$usuarios["pais"]."' AND iso_moneda='".$usuarios["moneda"]."'");
					$decimalesmoneda = $this->Conexion->Recorrido($consultarmoneda)[0];
				}
				
			
				
			}
			
			$retorno .= '"'.number_format($ganancia, $decimalesmoneda, ',', '.').'",';
			$retorno .= '"'.number_format(  $gananciabtc    , 8, ',', '.'  ).'"';
			return $retorno."]";
			
		}
	}
	new Datos();
?>