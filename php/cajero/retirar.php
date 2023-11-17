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
				$consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais='".$_POST["pais"]."' AND usuario='".$_POST["usuario"]."' AND cuenta='".$_POST["cuenta"]."'");
				if(!$this->Conexion->Recorrido($consultar)){
					$this->Conexion->Consultar("INSERT INTO cuentas (pais,banco,cuenta,tipodecuenta,nombres,identificacion,usuario,tipo) VALUES ('".$_POST["pais"]."','".$_POST["banco"]."','".$_POST["cuenta"]."','".$_POST["tipocuenta"]."','".$_POST["nombre"]."','".$_POST["identificacion"]."','".$_POST["usuario"]."','retiro')");
				}
                $this->Conexion->Consultar("INSERT INTO pagos(momento,cantidad,cuenta,banco,tipocuenta,nombres,identificacion,usuario,pais,moneda,monedascambiadas) VALUES ('".date("Y-m-d H:i:s")."','".$_POST["cantidad"]."','".$_POST["cuenta"]."','".$_POST["banco"]."','".$_POST["tipocuenta"]."','".$_POST["nombre"]."','".$_POST["identificacion"]."','".$_POST["usuario"]."','".$_POST["pais"]."','".$_POST["moneda"]."','".$_POST["monedas"]."')");
                
				
				return '"Solicitud enviada","correcto"';
            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Retirar();
?>