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
				$sql = "";
                $retorno = "";
				$json = json_decode($_POST["datos"],TRUE);
				$usdtorigen = $json["monedaenvio"]["tasausdt"];
                for($i=0;$i<count($json["monedasdestino"]);$i++){
                    $retorno .= '"'.number_format(($json["monedasdestino"][$i]["tasausdt"]/$usdtorigen)/floatval("1.".str_replace(".","",$json["monedasdestino"][$i]["ganancia"])),$json["monedasdestino"][$i]["decimalestasa"],".","").'",';
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