<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->monedas().",".$this->cuantas()."]";
			$this->Conexion->CerrarConexion();
		}
		private function monedas(){
			//$consultar = $this->Conexion->Consultar("SELECT * FROM paises");
            
            $retorno = "[";

			/*while($datos = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
            }

			$retorno = substr($retorno,0,strlen($retorno)-1)."],[";*/
            $consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
            while($datos1 = $this->Conexion->Recorrido($consulta2)){
                
                $retorno .= '{"pais":"'.$datos1[0].'","moneda":"'.$datos1[3].'","decimales":"'.$datos1[4].'","nombre":"'.$datos1[2].'"},';
            }
            return substr($retorno,0,strlen($retorno)-1)."]";
        } 

		private function cuantas(){
			//$consultar = $this->Conexion->Consultar("SELECT * FROM paises");
            
            $retorno = "[";

			/*while($datos = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
            }

			$retorno = substr($retorno,0,strlen($retorno)-1)."],[";*/
            $consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
            if($datos1 = $this->Conexion->Recorrido($consulta2)){
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais ='".$datos1[0]."' AND tipo='pago'");
                while($cuenta = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"banco":"'.$cuenta[1].'","cuenta":"'.$cuenta[2].'","tipodecuenta":"'.$cuenta[3].'","nombre":"'.$cuenta[4].'","identificacion":"'.$cuenta[5].'","usuario":"'.$cuenta[6].'"},';
				}
                
            }
			if(strlen($retorno)>1){
				return substr($retorno,0,strlen($retorno)-1)."]";
			}else{
				return $retorno."'SELECT * FROM paises WHERE receptor IS NOT NULL']";
			}
		}
	}
	new Datos();
?>