<?php
	error_reporting(E_ALL);
    Class Prestamo{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
				
                
				

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Prestamo();
?>