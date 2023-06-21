<?php
	Class ValidarSesion{
		private $Conexion;
		function __construct(){
			include("../../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			
		} 
	}
	new ValidarSesion();
?>