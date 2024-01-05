<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
				
				$datos = [];
				$consultar = $this->Conexion->Consultar("SELECT * FROM gastos LEFT JOIN paises ON paises.iso2=gastos.pais WHERE momento LIKE '".$_POST["fecha"]."%'");
				while($gastos = $this->Conexion->Recorrido($consultar)){
					$dato = array("gastos",$gastos["moneda"],number_format($gastos["cantidad"], $gastos["decimalesmoneda"], '.', ''),$gastos["pais"],$gastos["usuario"]);
					$datos[$gastos["momento"]] = $dato;
				}
				$consular = $this->Conexion->Consultar("SELECT * FROM pagos LEFT JOIN paises ON paises.iso2=pagos.pais WHERE momento LIKE '".$_POST["fecha"]."%'");
				while($pagos = $this->Conexion->Recorrido($consular)){
					$dato = array("pagos",$pagos["moneda"],number_format($pagos["cantidad"],$pagos["decimalesmoneda"], '.', ''),$pagos["pais"],$pagos["pagos.usuario"]);
					$datos[$pagos["momento"]]=$dato;
				}

				ksort($datos);
				$retorno = [];
				foreach($datos as $clave => $dato){
					array_push($retorno,array($clave,$dato[0],$dato[1],$dato[2],$dato[3],$dato[4]));
				}
				return json_encode($retorno);
				

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Datos();
?>