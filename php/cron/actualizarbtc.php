<?php
error_reporting(E_ALL);
	Class ActualizarBtc{
		private $Conexion;
		function __construct(){
            include("/home/u956446715/public_html/public_html/php/conexion.php");
			$this->Conexion = new Conexion();
            $api = "";
             $consultarapi = $this->Conexion->Consultar("SELECT codigo FROM apis ORDER BY solicitudes ASC");
            if ($apis = $this->Conexion->Recorrido($consultarapi)) {
                $api = $apis[0];
                $this->Conexion->Consultar("UPDATE apis SET solicitudes=solicitudes+1 WHERE codigo='".$apis[0]."'");
            }
            if($api=="CGK"){
                $url = "https://api.coingecko.com/api/v3/simple/price";
                $parameters = [
                    'ids' => 'bitcoin',
                    'vs_currencies' => 'usd'
                ];
                $headers = [
                    "accept: application/json",
                    "x-cg-demo-api-key: CG-cqVau314h2vrVuPBwRWTNA2y"
                ];
            }
            if($api == "CMP"){
                $url = 'https://pro-api.coinmarketcap.com/v2/tools/price-conversion';
                $parameters = [
                    'amount' => '1',
                    'symbol' => 'BTC',
                    'convert' => 'USD'
                ];
                $headers = [
                    'Accepts: application/json',
                    'X-CMC_PRO_API_KEY: cf040e5c-1049-4d6c-870d-08e1dd063018'
                ];
            }

            if($api!=""){
                $qs = http_build_query($parameters); 
                $request = "{$url}?{$qs}"; 
                $curl = curl_init(); 

                // Set cURL options
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $request,            
                    CURLOPT_HTTPHEADER => $headers,      
                    CURLOPT_RETURNTRANSFER => 1         
                ));

                $response = curl_exec($curl); // Send the request, save the response
                curl_close($curl); // Close request
            }
            
            
            $preciobtc = 0;
            if($api=="CGK"){
                $preciobtc = number_format(json_decode($response)->{"bitcoin"}->{"usd"},2,".","");
            }
            if($api == "CMP"){
                $preciobtc = number_format(json_decode($response)->{"data"}[0]->{"quote"}->{"USD"}->{"price"},2,".","");
            }
            echo $preciobtc." ";
            $momento = date("Y-m-d H:i:s");
            if($api!=""){
                echo $this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento,api) VALUES ('BTC','".$preciobtc."','".$preciobtc."','".$momento."','".$api."')");
            }
            $this->Conexion->CerrarConexion();
			/* = json_decode(file_get_contents("https://criptoya.com/api/binancep2p/btc/usd/"), true);
            $preciousdbtc = $informacion["totalBid"];
			

             */
        }
		
	}
	new ActualizarBtc();
?>