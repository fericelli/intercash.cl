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
					$consular = $this->Conexion->Consultar("SELECT * FROM depositos WHERE confirmado IS NULL");
				}else{
					$consular = $this->Conexion->Consultar("SELECT * FROM depositos WHERE (usuariocuenta='".$_POST["usuario"]."' OR usuario='".$_POST["usuario"]."') AND confirmado IS NULL");
				}

				while($depositos = $this->Conexion->Recorrido($consular)){
					$retorno .= '{"cantidad":"'.$depositos["cantidad"].'","banco":"'.$depositos["banco"].'","tipodecuenta":"'.$depositos["tipodecuenta"].'","cuenta":"'.$depositos["cuenta"].'","nombre":"'.$depositos["nombre"].'","identificacion":"'.$depositos["identificacion"].'","directorio":"'.$depositos["directorio"].'","pais":"'.$depositos["pais"].'","moneda":"'.$depositos["moneda"].'","momento":"'.$depositos["momento"].'","usuario":"'.$depositos["usuario"].'","usuariocuenta":"'.$depositos["usuariocuenta"].'","estado":"'.$depositos["confirmado"].'"},';
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