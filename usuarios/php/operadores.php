<?php
	error_reporting(E_ALL);
    Class Operadores{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                $consultar = $this->Conexion->Consultar("SELECT * FROM usuarios WHERE tipodeusuario='administrador' OR tipodeusuario='operador' LIMIT 0,20");
                $retorno = "";
                while($usaurio = $this->Conexion->Recorrido($consultar)){
                    
                    $retorno .= '"'.$usaurio[0].'",';

                }
                return substr($retorno,0,strlen($retorno)-1)."";

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Operadores();
?>