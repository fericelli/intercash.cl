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
			
            $preciobtcdestino = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]."*btc_in_usd"))->{'data'};
            $consultar = $this->Conexion->Consultar("SELECT tasasporcentaje,decimalestasa,anuncioventa,anunciocompra,devaluacion FROM tasas WHERE monedaventa='".$_POST["monedadestino"]."' AND monedacompra='".$_POST["monedaorigen"]."'");
            $porcentaje = "1.10";
            $decimalestasa = "0";
            $tasa = 0;
            $usddestino = 0;
            $usdorigen = 0;
            $davaludacion = 0;
            $tasausddestino=0;
            if($porcentajes = $this->Conexion->Recorrido($consultar)){
                
                $usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedadestino"]))->{'data'};
			    $usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedaorigen"]))->{'data'};
			    $btcprecio =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/BTC_in_USD"))->{'data'};
                $btcorigen = $usdorigen*$btcprecio;
                $btcdestino = $usddestino*$btcprecio;
                
                if(strlen($porcentajes[0])==1){
                    $porcentaje = "1.0".$porcentajes[0];
                }else{
                    $porcentaje = "1.".$porcentajes[0];
                }
                if($porcentajes[2]>="0"){
                    if($porcentajes[2]<10){
                        $btcdestino = $btcdestino*(floatval("1.0".abs($porcentajes[2])));
                    }else{
                        $btcdestino = $btcdestino*(floatval("1.".abs($porcentajes[2]))); 
                    }
                }else{
                    if($porcentajes[2]<10){
                        $btcdestino = $btcdestino/(floatval("1.0".abs($porcentajes[2]))); 
                    }else{
                        $btcdestino = $btcdestino/(floatval("1.".abs($porcentajes[2]))); 
                    }
                    
                }
                if($porcentajes[3]>="0"){
                    if($porcentajes[3]<10){
                        $btcorigen = $btcorigen*(floatval("1.0".abs(($porcentajes[3]))));
                    }else{
                        $btcorigen = $btcorigen*(floatval("1.".abs($porcentajes[3])));
                    }
                }else{
                    if($porcentajes[3]<10){
                        $btcorigen = $btcorigen/(floatval("1.0".abs($porcentajes[3])));
                    }else{
                        $btcorigen = $btcorigen/(floatval("1.".abs($porcentajes[3])));
                    } 
                    
                }
                $tasa = (($btcdestino)/$btcorigen)/$porcentaje;
                $decimalestasa = $porcentajes[1];
                $tasausddestino = $usddestino + (($usddestino*$porcentajes[4])/100);
            }
            
            if(isset($_POST["cantidadenviar"])){
                $tasa = round($tasa,$decimalestasa);
                $dinerorecibir = round($tasa*$_POST["cantidadenviar"], $_POST["decimaldestino"]);
                $dineroenviar=round($_POST["cantidadenviar"],$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
                
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
                 $tasa = round($tasa,$decimalestasa);
                $dinerorecibir = round($_POST["cantidadrecibir"],$_POST["decimaldestino"]);
                $dineroenviar = round($dinerorecibir/$tasa,$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
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