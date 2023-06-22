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
			
           /* $preciobtcdestino = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]."*btc_in_usd"))->{'data'};
            $consultar = $this->Conexion->Consultar("SELECT tasasporcentaje,decimalestasa,anuncioventa,anunciocompra FROM tasas WHERE monedaventa='".$_POST["monedadestino"]."' AND monedacompra='".$_POST["monedaorigen"]."'");
            $porcentaje = "1.10";
            $decimalestasa = "0";
            $tasa = 0;
            $usddestino = 0;
            $usdorigen = 0;
            $davaluacionorigen = 0;
            $davaluaciondestino = 0;
            $tasausddestino=0;
            $usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]))->{'data'};
			$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedaorigen"]))->{'data'};
			$btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
            */

            $usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]))->{'data'};
			$tasa = 0;
            $tasausddestino=0;
            $decimalestasa = 0;
           $consultar = $this->Conexion->Consultar("SELECT tasa,porcentaje,decimalestasa FROM tasas LEFT JOIN devaluacion ON monedaventa=moneda WHERE monedaventa='".$_POST["monedadestino"]."' AND monedacompra='".$_POST["monedaorigen"]."' AND paisorigen='".$_POST["paisorigen"]."' AND paisdestino='".$_POST["paisdestino"]."'");
            if($tasas = $this->Conexion->Recorrido($consultar)){
                $tasa = $tasas[0];
                $decimalestasa = $tasas[2];
                if($tasas[1]>0){
                    $tasausddestino = $usddestino*floatval("1.".str_replace(".","",$tasas[1]));//(($usddestino*$tasas[1])/100)+$usddestino;
                }else{
                    $tasausddestino = $usddestino;
                }
            }else{
                $tasausddestino = $usddestino;
            }
            
            if(isset($_POST["cantidadenviar"])){
                
                $tasa = round($tasa,$decimalestasa);
                $dinerorecibir = round($tasa*$_POST["cantidadenviar"], $_POST["decimaldestino"]);
                $dineroenviar=round($_POST["cantidadenviar"],$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
                
                if($preciobtcdestino*0.00001<$dinerorecibir){
                    $disponiblidad = "si";
                }else{
                    $disponiblidad = "si";
                }
                return '{"dineroenviar":"'.$dineroenviar.'",
                "dinerorecibir":"'.$dinerorecibir.'",
                "usd":"'.round($usd, 2).'",
                "tasa":"'.$tasa.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }else{
                
                $dinerorecibir = round($_POST["cantidadrecibir"],$_POST["decimaldestino"]);
                $dineroenviar = round($dinerorecibir/$tasa,$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
                if($preciobtcdestino*0.0001<$dinerorecibir){
                    $disponiblidad = "si";
                }else{
                    $disponiblidad = "si";
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