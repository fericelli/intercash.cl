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
				//var_dump($json);

                 $usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$json["monedaenvio"]["moneda"]))->{'data'};
                 $usdtorigen = $json["monedaenvio"]["tasausdt"];
                 
                //echo (($usdtorigen-$usdorigen)*100)/$usdorigen;exit;
                //var_dump($json["monedasdestino"]);
                for($i=0;$i<count($json["monedasdestino"]);$i++){
                    $retorno .= '"'.number_format(($json["monedasdestino"][$i]["tasausdt"]/$usdtorigen)/floatval("1.".str_replace(".","",$json["monedasdestino"][$i]["ganancia"])),$json["monedasdestino"][$i]["decimalestasa"],".","").'",';
                }
                //echo $json["monedasdestino"][0]["tasausdt"];

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