<?php
    error_reporting(E_ALL);
    Class Eliminar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
            $this->eliminar();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
        private function eliminar(){
            try{
                
                $consular = $this->Conexion->Consultar("SELECT directorio FROM screenshot WHERE tipo='".$_POST["tipo"]."' AND registro='".$_POST["registro"]."'");
                while($screenshot = $this->Conexion->Recorrido($consular)){
                    unlink("../../".$screenshot[0]);
                }
                
                $this->Conexion->Consultar("DELETE FROM screenshot WHERE tipo='".$_POST["tipo"]."' AND registro='".$_POST["registro"]."'");
                $this->Conexion->Consultar("DELETE FROM operaciones WHERE usuario='".$_POST["usuarioregistro"]."' AND registro='".$_POST["registro"]."'");
                if($_POST["tipo"]=="gastos"){
                    $this->Conexion->Consultar("DELETE FROM gastos WHERE momento='".$_POST["registro"]."'");
                
                }else if($_POST["tipo"]=="pagos"){
                    $this->Conexion->Consultar("DELETE FROM pagos WHERE momento='".$_POST["registro"]."'");
                }


            }catch(Exception $e){
                return '"Error","error"';
            }
        }
		private function retorno(){
            try{
				$datos = [];
				$consultar = $this->Conexion->Consultar("SELECT * FROM gastos LEFT JOIN paises ON paises.iso2=gastos.pais WHERE momento LIKE '".$_POST["fecha"]."%'");
				while($gastos = $this->Conexion->Recorrido($consultar)){
					$dato = array("gastos",$gastos["moneda"],number_format($gastos["cantidad"], $gastos["decimalesmoneda"], '.', ''),$gastos["pais"]);
					$datos[$gastos["momento"]] = $dato;
				}
				$consular = $this->Conexion->Consultar("SELECT * FROM pagos LEFT JOIN paises ON paises.iso2=pagos.pais WHERE momento LIKE '".$_POST["fecha"]."%'");
				while($pagos = $this->Conexion->Recorrido($consular)){
					$dato = array("pagos",$pagos["moneda"],number_format($pagos["cantidad"],$pagos["decimalesmoneda"], '.', ''),$pagos["pais"]);
					$datos[$pagos["momento"]]=$dato;
				}

				ksort($datos);
				$retorno = [];
				foreach($datos as $clave => $dato){
					array_push($retorno,array($clave,$dato[0],$dato[1],$dato[2],$dato[3]));
				}
				return json_encode($retorno);
				

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Eliminar();
?>