<?php
	error_reporting(E_ALL);
    Class ActualizarTasas{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
				
				$sql = "";
                $retorno = "";
				$json = json_decode($_POST["datos"],TRUE);
				for($i=0;$i<count($json["monedasdestino"]);$i++){
                    $this->Conexion->Consultar("UPDATE tasas SET tasasporcentaje=".$json["monedasdestino"][$i]["ganancia"].",decimalestasa=".$json["monedasdestino"][$i]["decimalestasa"].",tasa=".$json["monedasdestino"][$i]["tasa"]." WHERE monedacompra='".$json["monedaenvio"]["moneda"]."' AND monedaventa='".$json["monedasdestino"][$i]["moneda"]."'");
                    $this->Conexion->Consultar("UPDATE tasas SET anuncioventa='".$json["monedasdestino"][$i]["tasausdt"]."' WHERE monedaventa='".$json["monedasdestino"][$i]["moneda"]."'");
                }
                $this->Conexion->Consultar("UPDATE tasas SET anunciocompra='".$json["monedaenvio"]["tasausdt"]."' WHERE monedacompra='".$json["monedaenvio"]["moneda"]."'");
               
                return '"Tasas actualizadas","correcto"';
				

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new ActualizarTasas();
?>