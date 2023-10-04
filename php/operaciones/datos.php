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
				if($_POST["tipodeusuario"]=="administrador"){
					$consular = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE momento LIKE '".$_POST["fecha"]."%'");
				}else{
					$consular = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE operador='".$_POST["usuario"]."' OR usuario='".$_POST["usuario"]."' AND momento LIKE '".$_POST["fecha"]."%'");
				}
				while($operaciones = $this->Conexion->Recorrido($consular)){
					$retorno .= '{"moneda":"'.$operaciones["moneda"].'","monto":"'.$operaciones["monto"].'","operacion":"'.$operaciones["operacion"].'","momento":"'.$operaciones["momento"].'","usuario":"'.$operaciones["usuario"].'"},';
				}
				if(strlen($retorno)>1){
					return substr($retorno,0,strlen($retorno)-1)."";
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