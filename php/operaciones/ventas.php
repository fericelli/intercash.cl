<?php
    Class Ventas{
        private $Conexion;
        function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
        private function retorno(){
            $consultar = $this->Conexion->Consultar("SELECT DISTINC(paisintercacion),* FROM operaciones");
            while($pais = $this->Conexion->Recorrido($consultar)){
                echo $pais[0]."\n";
            }

        }
    }
    new Ventas();
?>