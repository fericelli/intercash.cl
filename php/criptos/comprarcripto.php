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
                
                $preciocompra = number_format(floatval($_POST["usdt"]/$_POST["cantidadcripto"]),2,".","");
                //return "INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,montointercambio) VALUES ('".$_POST["cripto"]."','".number_format(floatval($_POST["cantidadcripto"]),$_POST["decimales"],".","")."','compra'.'".date("Y-m-d H:i:s")."','".$_POST["usuario"]."','".$_POST["usuario"]."','".$preciocompra."','USDT','".$_POST["usdt"]."')";
                $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,montointercambio) VALUES ('".$_POST["cripto"]."','".number_format(floatval($_POST["cantidadcripto"]),$_POST["decimales"],".","")."','compra','".date("Y-m-d H:i:s")."','".$_POST["usuario"]."','".$_POST["usuario"]."','".$preciocompra."','USDT','".$_POST["usdt"]."')");
                return '["Operacion agregada","success"]';
            }catch(Exception $e){
                return '["Error","error"],["'.$e.'"]';
            }

        }
    }
    new ComprarCripto();
?>