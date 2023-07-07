<?php
    Class ComprarCripto{
        private $Conexion;
        function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
        private function retorno(){
            try{
                
                $preciocompra = number_format(floatval($_POST["usdt"]/$_POST["cantidadcripto"]),$_POST["decimales"],".","");

                $this->Conexion->Consultar("INSERT INTO operacionescripto (moneda,cantidad,cantidadusdt,tasa,usuario,momento) VALUES ('".$_POST["cripto"]."','".$_POST["cantidadcripto"]."','".$_POST["usdt"]."','".$preciocompra."','".$_POST["usuario"]."','".date("Y-m-d H:i:s")."')");
                return '["Operacion agregada","success"]';
            }catch(Exception $e){
                return '["Error","error"],["'.$e.'"]';
            }

        }
    }
    new ComprarCripto();
?>