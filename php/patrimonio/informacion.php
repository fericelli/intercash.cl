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
			$envios = [];
			

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
				$totalcapital += $valorusdt;
				
			}

			$cantidad =0;
			$decimalesusdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='USDT'"))[0];
			$decimalesbtc = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='BTC'"))[0];
			

			$enviosUSDT = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND tipo='envios' AND operacion='venta'"))[0]);
			$compradoUSDT =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND operacion='compra'"))[0]);
			$usdtenvios = number_format(floatval($compradoUSDT-$enviosUSDT),$decimalesusdt,".","");

			$enviosUSDE = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDE' AND tipo='envios' AND operacion='venta'"))[0]);
			$compradoUSDE =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDE' AND operacion='compra'"))[0]);
			$usdeenvios = number_format(floatval($cantidadcompradaUSDE - $cantidadinvertidaUSDE),$decimalesusdt,".","");
			
			$enviosBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND tipo='envios'"))[0]);
			$compradoBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND operacion='compra'"))[0]);
			$btc = number_format(floatval($compradoBTC-$enviosBTC),$decimalesbtc,".","");
			
			$enviosETH = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='ETH' AND tipo='envios' AND operacion='venta'"))[0]);
			$compradoETH =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='ETH' AND operacion='compra'"))[0]);
			$eth = number_format(floatval($compradoETH - $enviosETH),$decimalesbtc,".","");
			
			
			
			$cantidadcompradaUSDTBTC =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='USDT' AND operacion='compra'"))[0]);
			
			$usdtporcomprar = number_format(floatval($cantidadinvertidaUSDT-$cantidadcompradaUSDT),$decimalesusdt,".","");
			$btcporcomprar = number_format(floatval($cantidadinvertidoBTC-$cantidadcompradaBTC),$decimalesbtc,".","");
			
			
			

			 
			
			$tasa = number_format(floatval($usdt/$btc),$decimalesusdt,".","");
			
			//
			//return "     ".$btc."  ".$usdt."  ".$tasa;

			/*if($gananciaUSDT<0){
				$gananciaUSDT = 0;
			}
			if($gananciaBTC<0){
				$gananciaBTC = 0;
			}*/

			
			//$btcusdt = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$gananciaUSDT."");
			
			//$preciobtc = number_format(floatval($btc/$btcusdt),$decimalesusdt,".","");

			//$usdtbtc = number_format(floatval($usdt*$btcusdt),$decimalesusdt,".","");

			$monedas[0] = [];
			$monedas[1] = [];
			$monedas[2] = [];
			$monedas[3] = [];
			
			

			array_push($monedas[0],"BTC",number_format($btc,$decimalesbtc,".",""));
			$cantidad ++;
			array_push($monedas[1],"USDT",number_format($usdt,$decimalesusdt,".",""));
			$cantidad ++;
			array_push($monedas[2],"USDE",number_format($usde,$decimalesusdt,".",""));
			$cantidad ++;
			
			array_push($monedas[3],"ETH",number_format($eth,$decimalesbtc,".",""));
			$cantidad ++;
			//return $monedas;
			
			
			//var_dump($monedas);exit;
			$totalcapital = $usdt + $usde;
			

			
			
			
			

			
			
			
			$gastos = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE tipo='gastos'"))[0]);
			$pagos = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE tipo='pagos'"))[0]);

			//$gastosusdt = $this->GatosUSDT();
			
			

			$debito = [];
			$debito[0]= [];
			$debito[1]= [];
			$debito[2]= [];
			
			$totalcapital = number_format(floatval($totalcapital),$decimalesusdt,".","");

			$btcusdt =number_format(floatval($totalcapital/$preciobtc),$decimalesbtc,".",""); //;file_get_contents("https://blockchain.info/tobtc?currency=USD&value=".$totalusdt."");
			
			$totalbtc =  number_format(floatval($btcusdt+$gananciaBTC),$decimalesbtc,".","");
			
			
			
			array_push($debito[0],"USDT","gastos",number_format(floatval($gastos),$decimalesusdt,".",""));
			array_push($debito[1],"USDT","pagos",number_format(floatval($pagos),$decimalesusdt,".",""));
			array_push($debito[2],"USDT","deuda",number_format(floatval($this->deuda()),$decimalesusdt,".",""));
			
			$monedas[$cantidad]=[];
			array_push($monedas[$cantidad],"CapitalAproximada",number_format($totalcapital,$decimalesusdt,".",""));
			
			return [$monedas,$debito];

			
			
			
			
		} 
		private function GatosUSDT(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM gastos");
			$total = 0;
			while($gastos = $this->Conexion->Recorrido($consultar)){
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$gastos["momento"]."' AND moneda='USDT' AND tipo='gastos'");
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
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$pagos["momento"]."' AND moneda='USDT' AND tipo='pagos'");
				while($operaciones = $this->Conexion->Recorrido($consulta)){
					$this->Conexion->Consultar("");
					
					//$total += $usdt["monto"];
				}
			}
			return $total;
		}
		private function GatosUSDE(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM gastos");
			$total = 0;
			while($gastos = $this->Conexion->Recorrido($consultar)){
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$gastos["momento"]."' AND moneda='USDE' AND tipo='gastos'");
				if($usdt = $this->Conexion->Recorrido($consulta) AND $this->Conexion->NFilas($consulta)==1){
					$total += $usdt["monto"];
				}
			}
			return $total;
		}
		private function PagosUSDE(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM pagos");
			$total = 0;
			while($pagos = $this->Conexion->Recorrido($consultar)){
				$consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$pagos["momento"]."' AND moneda='USDE' AND tipo='pagos'");
				while($operaciones = $this->Conexion->Recorrido($consulta)){
					$this->Conexion->Consultar("");
					
					//$total += $usdt["monto"];
				}
			}
			return $total;
		}
		private function deuda(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE receptor IS NOT NULL AND tipodeusuario<>'administrador'");
			$montousdt=0;
			while($usuarios = $this->Conexion->Recorrido($consultar)){
				
				//echo "<br>";
				$sql = "SELECT * FROM paises WHERE receptor IS NOT NULL GROUP BY iso_moneda";
				$consultar2 =  $this->Conexion->Consultar($sql);
				$dauda = 0;
				while($datos = $this->Conexion->Recorrido($consultar2)){
					$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM pagos WHERE usuario='".$usuarios["usuario"]."' AND monedascambiadas LIKE '%".$datos["iso_moneda"]."%' ORDER BY momento DESC LIMIT 1"))[0];

					if($fecha==""){
						$fecha = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT momento FROM operaciones WHERE tipo='envios' AND usuario='".$usuarios["usuario"]."' AND monedaintercambio='".$datos["iso_moneda"]."'"))[0];
					}

					if($fecha==""){
						$fecha = date("Y-m-d 00:00:00");
					}
					$consultar1 = $this->Conexion->Consultar("SELECT monedaintercambio,SUM(montointercambio) FROM operaciones WHERE tipo='envios' AND usuario='".$usuarios["usuario"]."' AND momento>='".$fecha."' AND monedaintercambio='".$datos["iso_moneda"]."'");
					$cantidad = $this->Conexion->Recorrido($consultar1);
					$cantidad[1];
					$pago = $cantidad[1]*$usuarios["porcentaje"];
					$pago = $pago/100;
					$tasas = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$datos["iso_moneda"]."'"))[0];
					$montousdt = $montousdt + floatval($pago/$tasas);
				}
			}
			
			return $montousdt;
		}
	}
	new Informacion();
?>