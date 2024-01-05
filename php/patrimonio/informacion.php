<?php
	Class Informacion{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo json_encode($this->retorno());
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$criptos=[];
			$monedas = [];

			$cantidad =0;

			$cantidadinvertidaUSDT = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND tipo='envios'"))[0]);
			$cantidadcompradaUSDT =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND operacion='compra'"))[0]);
			$cantidadinvertidoBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND tipo='envios'"))[0]);
			$cantidadcompradaBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND operacion='compra'"))[0]);
			$decimalesusdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='USDT'"))[0];
			$decimalesbtc = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='BTC'"))[0];
			$gananciaUSDT = number_format($cantidadcompradaUSDT-$cantidadinvertidaUSDT,$decimalesusdt,".","");
			$gananciaBTC = number_format($cantidadcompradaBTC-$cantidadinvertidoBTC,$decimalesbtc,".","");
			
			

			if($gananciaUSDT<0){
				$gananciaUSDT = 0;
			}
			if($gananciaBTC<0){
				$gananciaBTC = 0;
			}

			
			$btcusdt = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$gananciaUSDT."");
			
			$preciobtc = number_format(floatval($gananciaUSDT/$btcusdt),$decimalesusdt,".","");

			$usdtbtc = number_format(floatval($gananciaUSDT*$btcusdt),$decimalesusdt,".","");

			$monedas[0] = [];
			$monedas[1] = [];
			
			

			array_push($monedas[0],"BTC",number_format($cantidadinvertidoBTC,$decimalesbtc,".",""),$usdtbtc);
			$cantidad ++;
			array_push($monedas[1],"USDT",number_format($cantidadinvertidaUSDT,$decimalesusdt,".",""),$btcusdt);
			$cantidad ++;
			
			
			
			//var_dump($monedas);exit;
			$totalusdt = $gananciaUSDT;
			$consultar1 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
			while($paises = $this->Conexion->Recorrido($consultar1)){
				
				$cantidadadquirida = number_format(floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND operacion='venta' AND tipo='envios'"))[0]),$paises["decimalesmoneda"],".","");
				$cantidadcambiada = number_format(floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND operacion='compra'"))[0]),$paises["decimalesmoneda"],".","");

				$totalmoneda = number_format(floatval($cantidadadquirida-$cantidadcambiada),$paises["decimalesmoneda"],".","");
				if($totalmoneda>0){
					$tasa = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anunciocompra) FROM tasas WHERE monedacompra='".$paises["iso_moneda"]."'"))[0];
					$valorusdt = number_format(floatval($totalmoneda/$tasa),2,".","");
					$monedas[$cantidad] = [];
					array_push($monedas[$cantidad],$paises["iso_moneda"],$totalmoneda,$valorusdt);
					$cantidad++;
					$totalusdt += $valorusdt;
				}
			}
			return $monedas;
			$btcusdt = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$totalusdt."");
			
			$totalbtc =  number_format(floatval($btcusdt+$gananciaBTC),$decimalesbtc,".","");

			$totalusdt = number_format(floatval($totalusdt),$decimalesusdt,".","");
			
			 
			return $totalusdt."  ".$totalbtc;
			
			
		} 
	}
	new Informacion();
?>