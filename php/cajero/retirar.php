<?php
	error_reporting(E_ALL);
    Class Retirar{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                $this->Conexion->Consultar("INSERT INTO pagos(momento,cantidad,cuenta,banco,tipocuenta,nombres,identificacion,usuario,pais,moneda) VALUES ('".date("Y-m-d H:i:s")."','".$_POST["cantidad"]."','".$_POST["cuenta"]."','".$_POST["banco"]."','".$_POST["tipocuenta"]."','".$_POST["nombre"]."','".$_POST["identificacion"]."','".$_POST["usuario"]."','".$_POST["pais"]."','".$_POST["moneda"]."')");
                return '"Solicitud enviada","correcto"';
            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Retirar();
?>