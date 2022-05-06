<?php
	Class Informacion{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]))->{'data'};
			$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedaorigen"]))->{'data'};
            $preciobtcdestino = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]."*btc_in_usd"))->{'data'}*1.1;
            $consultar = $this->Conexion->Consultar("SELECT tasasporcentaje,decimalestasa FROM tasas WHERE monedaventa='".$_POST["monedadestino"]."' AND monedacompra='".$_POST["monedaorigen"]."'");
            $porcentaje = "1.10";
            $decimalestasa = "0";
            if($porcentajes = $this->Conexion->Recorrido($consultar)){
                if(strlen($porcentajes[0])==1){
                    $porcentaje = "1.0".$porcentajes[0];
                    
                }else{
                    $porcentaje = "1.".$porcentajes[0];
                }
                $decimalestasa = $porcentajes[1];
            }
            
            if(isset($_POST["cantidadenviar"])){
                $tasa = round((($usddestino/$usdorigen))/$porcentaje,$decimalestasa);
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
                "tasa":"'.$tasa.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }else{
                 $tasa = round((($usddestino/$usdorigen))/$porcentaje,$decimalestasa);
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
                "tasa":"'.$tasa.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }
            

		} 
	}
	new Informacion();
?>