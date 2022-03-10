<?php
	Class Datos{
		private $Conexion;
		function __construct(){
			include("conexion.php");
			$this->Conexion = new Conexion("ejemplo");
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM datos");
			$retorno = "";
			while($datos = $this->Conexion->Recorrido($consultar)){
				$retorno .= '{"nombre":"'.$datos[1].'",
				"apellido":"'.$datos[2].'",
				"edad":"'.$datos[3].'"},';
			}
			
			if(strlen($retorno)>0){
				$retorno = substr($retorno,0,strlen($retorno)-1);
			}
			return $retorno;
			
		} 
	}
	new Datos();
?>