<?php
	
    Class Usuarios{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // return var_dump($_POST);
            
			$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LIMIT 0,5");
            $retorno = "";
            while($usaurio = $this->Conexion->Recorrido($consultar)){
                
                $retorno .= '"'.$usaurio[0].'",';

            }
            
            
            
           return substr($retorno,0,strlen($retorno)-1)."";
        } 
	}
	new Usuarios();
?>