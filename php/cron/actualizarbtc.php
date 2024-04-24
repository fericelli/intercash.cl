<?php
error_reporting(E_ALL);
	Class ActualizarBtc{
		private $Conexion;
		function __construct(){
            include("../../conexion.php");
			$this->Conexion = new Conexion();
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://criptoya.com/api/binancep2p/btc/usd');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $informacion = json_decode(curl_exec($ch), true);
            $preciousdbtc = $informacion["totalBid"];
			$momento = date("Y-m-d H:i:s",$informacion["time"]);

            echo $this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento) VALUES ('BTC','".$preciousdbtc."','".$preciousdbtc."','".$momento."')");
            $this->Conexion->CerrarConexion();
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            //print_r($result);
            curl_close($ch);
			/* = json_decode(file_get_contents("https://criptoya.com/api/binancep2p/btc/usd/"), true);
            $preciousdbtc = $informacion["totalBid"];
			$momento = date("Y-m-d H:i:s",$informacion["time"]);

             $this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento) VALUES ('BTC','".$preciousdbtc."','".$preciousdbtc."','".$momento."')");
            $this->Conexion->CerrarConexion();*/
        }
		
	}
	new ActualizarBtc();
?>