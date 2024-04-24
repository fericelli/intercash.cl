<?php
error_reporting(E_ALL);
	Class ActualizarBtc{
		private $Conexion;
		function __construct(){
            include("/home/u956446715/public_html/public_html/php/conexion.php");
			$this->Conexion = new Conexion();
            //$url = "https://api.coingecko.com/api/v3/simple/price";
           
            /*$parameters = [
            'ids' => 'bitcoin',
            'vs_currencies' => 'usd'
            ];*/

            /*$headers = [
                "accept: application/json",
                "x-cg-demo-api-key: CG-cqVau314h2vrVuPBwRWTNA2y"
            ];*/
            
            echo $this->CoinMarketCap();
			/* = json_decode(file_get_contents("https://criptoya.com/api/binancep2p/btc/usd/"), true);
            $preciousdbtc = $informacion["totalBid"];
			$momento = date("Y-m-d H:i:s",$informacion["time"]);

             $this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento) VALUES ('BTC','".$preciousdbtc."','".$preciousdbtc."','".$momento."')");
            $this->Conexion->CerrarConexion();*/
        }
        private function CoinMarketCap(){
            $url = 'https://pro-api.coinmarketcap.com/v2/tools/price-conversion';
            $parameters = [
                'amount' => '1',
                'symbol' => 'BTC',
                'convert' => 'USD'
            ];
            $qs = http_build_query($parameters); // query string encode the parameters
            $request = "{$url}?{$qs}"; // create the request URL

            $curl = curl_init(); // Get cURL resource
            // Set cURL options
            curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,     // set the headers 
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
            ));

            $response = curl_exec($curl); // Send the request, save the response
            //print_r(json_decode($response));
            curl_close($curl); // Close request
            echo json_decode($response)->{"data"}[0]->{"quote"}->{"USD"}->{"price"}; // print json decoded response
        }
		
	}
	new ActualizarBtc();
?>