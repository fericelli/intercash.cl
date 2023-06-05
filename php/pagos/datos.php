<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                $retorno = "";
				$consular = $this->Conexion->Consultar("SELECT * FROM pagos WHERE usuario='".$_POST["usuario"]."'");
				while($pagos = $this->Conexion->Recorrido($consular)){
					//$this->Conexion->Consultar("");
					$retorno .= '{"cantidad":"'.$pagos["cantidad"].'","banco":"'.$pagos["banco"].'","tipodecuenta":"'.$pagos["tipocuenta"].'","cuenta":"'.$pagos["cuenta"].'","nombre":"'.$pagos["nombres"].'","identificacion":"'.$pagos["identificacion"].'","pais":"'.$pagos["pais"].'","moneda":"'.$pagos["moneda"].'","momento":"'.$pagos["momento"].'"},';
				}
				if(strlen($retorno)>1){
					return substr($retorno,0,strlen($retorno)-1);
				}else{
					return "";
				}

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Datos();
?>