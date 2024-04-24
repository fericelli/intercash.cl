<?php
	Class ActualizarBtc{
		private $Conexion;
		function __construct(){
            include("../conexion.php");
			$this->Conexion = new Conexion();
			$informacion = json_decode(file_get_contents("https://criptoya.com/api/binancep2p/btc/usd/"), true);
            $preciousdbtc = $informacion["totalBid"];
			$momento = date("Y-m-d H:i:s",$informacion["time"]);

            echo $this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento) VALUES ('BTC','".$preciousdbtc."','".$preciousdbtc."','".$momento."')");
            $this->Conexion->CerrarConexion();
        }
		
	}
	new ActualizarBtc();
?>