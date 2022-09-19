<?php
	Class Eliminar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // return var_dump($_POST);
           $retorno = "";
			$this->Conexion->Consultar("DELETE FROM solicitudes WHERE usuario='".$_POST["usuario"]."' AND momento='".$_POST["momento"]."'");
            $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE usuario='".$_POST["usuario"]."' AND momento='".$_POST["momento"]."'");
            if($this->Conexion->Recorrido($consultar)){
                $retorno = '"no"';
            }else{
                $retorno = '"si"';
            } 
            

           return $retorno;
        } 
	}
	new Eliminar();
?>