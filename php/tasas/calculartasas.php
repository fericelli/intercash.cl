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
                $retorno = "[";
				$json = json_decode($_POST["datos"],TRUE);
				//var_dump($json);

                echo $usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$json["monedaenvio"]["moneda"]))->{'data'};
                echo $usdtorigen = $json["monedaenvio"]["tasausdt"];

                $json["monedasdestino"];
				if(strlen($retorno)>1){
					return substr($retorno,0,strlen($retorno)-1)."]";
				}else{
					return "]";
				}

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Datos();
?>