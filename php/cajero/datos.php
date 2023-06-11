<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->monedas().",".$this->cuentas().",".$this->cuentasretiro().",".$this->tipodecuenta().",".$this->saldo()."]";
			$this->Conexion->CerrarConexion();
		}
		private function monedas(){
			$retorno = "[";

			if(isset($_POST["pais"])){
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE iso2<>'".$_POST["pais"]."' AND receptor IS NOT NULL");
				
			}else{
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");

			}
            while($datos1 = $this->Conexion->Recorrido($consulta2)){
                
                $retorno .= '{"pais":"'.$datos1[0].'","moneda":"'.$datos1[3].'","decimales":"'.$datos1[4].'","nombre":"'.$datos1[2].'"},';
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
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
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
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
            	if($datos1 = $this->Conexion->Recorrido($consulta2)){
					$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1[0]."' AND usuario='".$_POST["usuario"]."' AND tipo='envio'");
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
				$consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
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
			if(isset($_POST["pais"])){
				//$consulta = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
				//if($datos = $this->Conexion->Recorrido($consulta)){
					$consultarganancia = $this->Conexion->Consultar("SELECT SUM(montocompra),usuarios.porcentaje,devaluacion.porcentaje FROM intercambios INNER JOIN usuarios ON usuario=intermediario  INNER JOIN devaluacion ON moneda=monedacompra  WHERE intermediario='".$_POST["usuario"]."' AND monedacompra='".$_POST["moneda"]."'");
					if($intercambios = $this->Conexion->Recorrido($consultarganancia)){
						
						if($intercambios[0]==null){
							$retorno .= '"'.number_format("0", 2, ',', '.').'",';
							$retorno .= '"'.number_format("0", 8, ',', '.').'",';

						}else{
							$usd =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["moneda"]))->{'data'};
				
							$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
							$ganancia = ($intercambios[0]*$intercambios[1])/100;
							$ganancia = $ganancia/$usd;
							$ganancia = $ganancia/$btcprecio;
							

							$retorno .= '"'.number_format(($intercambios[0]*$intercambios[1])/100, 2, ',', '.').'",';
							$retorno .= '"'.number_format(  $ganancia    , 8, ',', '.'  ).'",';
						}

						
					}
					
				//}
			}else{
				$consulta = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
				if($datos = $this->Conexion->Recorrido($consulta)){
					$consultarganancia = $this->Conexion->Consultar("SELECT SUM(montocompra),usuarios.porcentaje,devaluacion.porcentaje FROM intercambios INNER JOIN usuarios ON usuario=intermediario  INNER JOIN devaluacion ON moneda=monedacompra  WHERE intermediario='".$_POST["usuario"]."' AND monedacompra='".$datos["iso_moneda"]."'");
					if($intercambios = $this->Conexion->Recorrido($consultarganancia)){
						
						if($intercambios[0]==null){
							$retorno .= '"'.number_format("0", 2, ',', '.').'",';
							$retorno .= '"'.number_format("0", 8, ',', '.').'",';

						}else{
							$usd =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$datos["iso_moneda"]))->{'data'};
				
							$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
							$ganancia = ($intercambios[0]*$intercambios[1])/100;
							$ganancia = $ganancia/$usd;
							$ganancia = $ganancia/$btcprecio;
							

							$retorno .= '"'.number_format(($intercambios[0]*$intercambios[1])/100, 2, ',', '.').'",';
							$retorno .= '"'.number_format(  $ganancia    , 8, ',', '.'  ).'",';
						}

						
					}
					
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