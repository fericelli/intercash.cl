<?php
	Class Deuda{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			 $this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $consultar = $this->Conexion->Consultar("SELECT DISTINCT monedaintercambio FROM `operaciones` WHERE usuario='tito' AND operacion='venta'");
            while($monedacompro = $this->Conexion->Recorrido($consultar)){
                $this->Conexion->Consultar("SELECT SUM()");

                return "";
            }
        }
	}
	new Deuda();
?>