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
            
            try{
				$consultar = $this->Conexion->Consultar("SELECT * FROM depositos WHERE momento='".$_POST["registro"]."' AND usuario='".$_POST["usuario"]."'");
				if($depositos = $this->Conexion->Recorrido($consultar)){
					unlink('./../../'.$depositos["directorio"]);
					$this->Conexion->Consultar("DELETE FROM depositos WHERE momento='".$_POST["registro"]."' AND usuario='".$_POST["usuario"]."'");
                
				}
				return '"success","Eliminado"';
            }catch(Exception $e){
                return '"error","'.$e.'"';
            }
		} 
	}
	new Eliminar();
?>