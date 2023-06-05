<?php
	Class Paisesorigen{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion("intercash.cl");
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM paises");
            
            $retorno = "[";

			while($datos = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
            }

			$retorno = substr($retorno,0,strlen($retorno)-1)."],[";
            $consulta2 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
            while($datos1 = $this->Conexion->Recorrido($consulta2)){
                
                $retorno .= '{"iso_pais":"'.$datos1[0].'","moneda":"'.$datos1[3].'","decimales":"'.$datos1[4].'","nombre":"'.$datos1[2].'"},';
            }
            return substr($retorno,0,strlen($retorno)-1)."]";
		} 
	}
	new Paisesorigen();
?>

