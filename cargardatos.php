<?php
	Class CargarDatos{
		private $Conexion;
		function __construct(){
			include("conexion.php");
			$this->Conexion = new Conexion("ejemplo");
			echo '["'.$this->retorno().'"]';
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("INSERT INTO datos (nombre,apellido,edad) VALUES ('".$_POST["nombre"]."','".$_POST["apellido"]."',".$_POST["edad"].")");
			if($consultar){
				return "Datos cargados exitosamente";
			}else{
				return "No se cargaron los datos";
			}
			//return $retorno;
		} 
	}
	new CargarDatos();
?>