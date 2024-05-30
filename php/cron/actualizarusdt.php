<?php
	Class ActualizarUsdt{
		private $Conexion;
		function __construct(){
			include("/home/u956446715/public_html/public_html/php/conexion.php");
			$this->Conexion = new Conexion();
			$values = "";
			$update = "";
			$consultar = $this->Conexion->Consultar("SELECT monedaventa FROM tasas GROUP BY monedaventa");
			while($monedas = $this->Conexion->Recorrido($consultar)){
				$moneda = $monedas[0];
				if($monedas[0]=="VED"){
					$moneda = "VES";
				}
				try{
					$informacion = json_decode(file_get_contents("https://criptoya.com/api/binancep2p/USDT/".$moneda."/"), true);
					$preciocompra = $informacion["totalAsk"];
					$precioventa = $informacion["totalBid"];
					$momento = date("Y-m-d H:i:s",$informacion["time"]);
					
					if(!is_null($precioventa) AND $monedas[0]<>"USD"){
						$values .=  "('".$moneda."','".$preciocompra."','".$precioventa."','".$momento."','CPTY'),";
						//$this->Conexion->Consultar("UPDATE tasas SET anunciocompra='".$preciocompra."' WHERE monedacompra='".$monedas[0]."'");
						//$this->Conexion->Consultar("UPDATE tasas SET anuncioventa='".$precioventa."' WHERE monedaventa='".$monedas[0]."'");
					}
				}catch(Exception $e){
					
				}
			} 
			$values = substr($values,0,strlen($values)-1);
			$this->Conexion->Consultar("INSERT INTO precios (moneda,compra,venta,momento,api) VALUES ".$values."");
			$this->Conexion->CerrarConexion();
		}
		
	}
	new ActualizarUsdt();
?>