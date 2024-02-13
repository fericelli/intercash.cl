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
			$cantidadcompradaUSDTBTC =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='USDT' AND operacion='compra'"))[0]);
			$cantidadinvertidoBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND tipo='envios'"))[0]);
			$cantidadcompradaBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND operacion='compra'"))[0]);
			$decimalesusdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='USDT'"))[0];
			$decimalesbtc = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='BTC'"))[0];
			
			 $usdtporcomprar = number_format(floatval($cantidadinvertidaUSDT-$cantidadcompradaUSDT),$decimalesusdt,".","");
			   $btcporcomprar = number_format(floatval($cantidadinvertidoBTC-$cantidadcompradaBTC),$decimalesbtc,".","");
			 
			 
			$btc = number_format(floatval($cantidadcompradaBTC-$cantidadinvertidoBTC),$decimalesbtc,".","");
			$usdt = number_format(floatval($cantidadcompradaUSDT - $cantidadinvertidaUSDT-$cantidadcompradaUSDTBTC),$decimalesusdt,".","");
			$tasa = number_format(floatval($usdt/$btc),$decimalesusdt,".","");
			//
			//return "     ".$btc."  ".$usdt."  ".$tasa;

			/*if($gananciaUSDT<0){
				$gananciaUSDT = 0;
			}
			if($gananciaBTC<0){
				$gananciaBTC = 0;
			}*/

			
			$btcusdt = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$gananciaUSDT."");
			
			$preciobtc = number_format(floatval($btc/$btcusdt),$decimalesusdt,".","");

			$usdtbtc = number_format(floatval($usdt*$btcusdt),$decimalesusdt,".","");

			$monedas[0] = [];
			$monedas[1] = [];
			
			

			array_push($monedas[0],"BTC",number_format($btc,$decimalesbtc,".",""));
			$cantidad ++;
			array_push($monedas[1],"USDT",number_format($usdt,$decimalesusdt,".",""));
			$cantidad ++;
			//return $monedas;
			
			
			//var_dump($monedas);exit;
			$totalusdt = $usdt;
			$consultar1 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
			while($paises = $this->Conexion->Recorrido($consultar1)){
				$cantidadadquirida = number_format(floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND operacion='venta' AND tipo='envios' "))[0]),$paises["decimalesmoneda"],".","");
				$cantidadcambiada = number_format(floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND operacion='compra' "))[0]),$paises["decimalesmoneda"],".","");
				//echo  $paises["iso_moneda"]."--".$cantidadadquirida."  ".$cantidadcambiada."<br>";

				$totalmoneda = number_format(floatval($cantidadadquirida-$cantidadcambiada),$paises["decimalesmoneda"],".","");
				
				$monedas[$cantidad] = [];
				if($totalmoneda>0){
					$tasa = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anunciocompra) FROM tasas WHERE monedacompra='".$paises["iso_moneda"]."'"))[0];
					$valorusdt = number_format(floatval($totalmoneda/$tasa),2,".","");
					array_push($monedas[$cantidad],$paises["iso_moneda"],$totalmoneda,$valorusdt);
				}else{
					$tasa = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT tasa FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND operacion='compra' ORDER BY momento DESC LIMIT 1"))[0];
					$valorusdt = number_format(floatval($totalmoneda/$tasa),2,".","");
					array_push($monedas[$cantidad],$paises["iso_moneda"],$totalmoneda,$valorusdt);
				}
				$cantidad++;
				$totalusdt += $valorusdt;
				
			}
			
			

			
			
			
			$gastos = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND tipo='gastos'"))[0]);
			$pagos = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND tipo='pagos'"))[0]);

			//$gastosusdt = $this->GatosUSDT();
			
			$totalusdt -= $this->GatosUSDT(); 

			$debito = [];
			$debito[0]= [];
			$debito[1]= [];
			
			$totalusdt = number_format(floatval($totalusdt),$decimalesusdt,".","");

			$btcusdt =number_format(floatval($totalusdt/$preciobtc),$decimalesbtc,".",""); //;file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$totalusdt."");
			
			$totalbtc =  number_format(floatval($btcusdt+$gananciaBTC),$decimalesbtc,".","");
			
			

			array_push($debito[0],"USDT","gastos",number_format(floatval($gastos),$decimalesusdt,".",""));
			array_push($debito[1],"USDT","pagos",number_format(floatval($pagos),$decimalesusdt,".",""));
			
			$monedas[$cantidad]=[];
			array_push($monedas[$cantidad],"USDTAproximado",number_format($totalusdt,$decimalesusdt,".",""));
			return [$monedas,$debito];

			
			
			
			
		} 
		private function GatosUSDT(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM gastos");
			$total = 0;
			while($gastos = $this->Conexion->Recorrido($consultar)){
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$gastos["momento"]."' AND tipo='gastos'");
				if($usdt = $this->Conexion->Recorrido($consulta) AND $this->Conexion->NFilas($consulta)==1){
					$total += $usdt["monto"];
				}
			}
			return $total;
		}
		private function PagosUSDT(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM pagos");
			$total = 0;
			while($pagos = $this->Conexion->Recorrido($consultar)){
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$pagos["momento"]."' AND tipo='pagos'");
				while($operaciones = $this->Conexion->Recorrido($consulta)){
					$this->Conexion->Consultar("");
					
					//$total += $usdt["monto"];
				}
			}
			return $total;
		}
		private function deuda(){
			$total = 0;

		}
	}
	new Informacion();
?>