<?php
	Class Informacion{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion("intercash.cl");
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]))->{'data'};
			$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedaorigen"]))->{'data'};
            $preciobtcdestino = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]."*btc_in_usd"))->{'data'}*1.1;
            
            
            if(isset($_POST["cantidadenviar"])){
                $tasa = (($usddestino/$usdorigen))/1.03;
                $dinerorecibir = round($tasa*$_POST["cantidadenviar"], $_POST["decimaldestino"]);
                $dineroenviar=round($_POST["cantidadenviar"],$_POST["decimalorigen"]);
                $usd = $dinerorecibir/$usddestino;
                if($preciobtcdestino*0.0001<$dinerorecibir){
                    $disponiblidad = "si";
                }else{
                    $disponiblidad = "no";
                }
                return '{"dineroenviar":"'.$dineroenviar.'",
                "dinerorecibir":"'.$dinerorecibir.'",
                "usd":"'.round($usd, 2).'",
                "tasa":"'.$dinerorecibir/$dineroenviar.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }else{
                $tasa = (($usddestino/$usdorigen))/1.03;
                $dinerorecibir = round($_POST["cantidadrecibir"],$_POST["decimaldestino"]);
                $dineroenviar = round($dinerorecibir/$tasa,$_POST["decimalorigen"]);
                $usd = $dinerorecibir/$usddestino;
                if($preciobtcdestino*0.0001<$dinerorecibir){
                    $disponiblidad = "si";
                }else{
                    $disponiblidad = "no";
                }
                return '{"dineroenviar":"'.$dineroenviar.'",
                "dinerorecibir":"'.$dinerorecibir.'",
                "usd":"'.round($usd, 2).'",
                "tasa":"'.$dinerorecibir/$dineroenviar.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }
            

		} 
	}
	new Informacion();
?>