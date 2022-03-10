<?php
	Class Reinicar{
		private $Conexion;
		function __construct(){
			include("conexion.php");
			$this->Conexion = new Conexion("ejemplo");
			echo '["'.$this->retorno().'"]';
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("TRUNCATE TABLE datos");
			if($consultar){
				return "Reinicio exitoso";
			}else{
				return "Error al reiniciar";
			}
			//return $retorno;
		} 
	}
	new Reinicar();
?>
