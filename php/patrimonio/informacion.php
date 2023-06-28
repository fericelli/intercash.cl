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
			$selectormonedas = [];
			$monedas = [];
			$consultar = $this->Conexion->Consultar("SELECT *  FROM monedas");
			$cantidad =0;
			while($moneda = $this->Conexion->Recorrido($consultar)){
				array_push($selectormonedas,'"'.$moneda["iso_moneda"].'"');
				$monedas[$moneda["iso_moneda"]]["moneda"] = $moneda["iso_moneda"];
				$consultar1 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$moneda["iso_moneda"]."' AND operacion='venta'");
				$consultar2 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$moneda["iso_moneda"]."' AND operacion='compra'");
				if($cantidad1 = $this->Conexion->Recorrido($consultar1) OR $cantidad2 = $this->Conexion->Recorrido($consultar2)){
					$monedas[$moneda["iso_moneda"]]["decimales"] = $moneda["decimales"];
					$monedas[$moneda["iso_moneda"]]["operaciones"]["venta"] = number_format($cantidad1[0],$moneda["decimales"],".","");
					$monedas[$moneda["iso_moneda"]]["operaciones"]["compra"] = number_format($cantidad2[0],$moneda["decimales"],".","");;
				}
				$cantidad++;
			}
			//var_dump($monedas);exit;
			$fiats = [];
			$cantidad = 0;
			$consultar3 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
			while($fiat = $this->Conexion->Recorrido($consultar3)){
				
				//$consultar4 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$fiat["iso2"]."' AND operacion='venta'");
				$consultar5 = $this->Conexion->Consultar("SELECT DISTINCT monedaintercambio FROM operaciones WHERE paisintercambio='".$fiat["iso2"]."' AND operacion='venta'");
				
				
				while($monedaintercaionbio = $this->Conexion->Recorrido($consultar5)){
					$consultar6 = $this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE paisintercambio='".$fiat["iso2"]."' AND monedaintercambio='".$monedaintercaionbio[0]."' AND operacion='venta'");
					$consultar7 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$monedaintercaionbio[0]."' AND operacion='venta'");
					
					$cantidadmoneda = floatval($this->Conexion->Recorrido($consultar6)[0])-floatval($this->Conexion->Recorrido($consultar7)[0]);
					
					$consulta8 = $this->Conexion->Consultar("SELECT porcentaje FROM devaluacion WHERE moneda='".$monedaintercaionbio[0]."'");
					$devaluacion =  floatval($this->Conexion->Recorrido($consulta8)[0]);
					$usdtpendientes = 0;
					
					if($cantidadmoneda<=0){
						$fiats[$fiat["iso2"]][$monedaintercaionbio[0]]["cantidadinvertida"] = abs(floatval($cantidadmoneda));
						$fiats[$fiat["iso2"]][$monedaintercaionbio[0]]["USDT"] = 0;
					}else{
						$fiats[$fiat["iso2"]][$monedaintercaionbio[0]]["cantidadinvertida"] = abs(floatval($cantidadmoneda));
						$usd = json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$monedaintercaionbio[0]))->{'data'};
						$usdtpendientes += floatval($cantidadmoneda/$usd);
						$fiats[$fiat["iso2"]][$monedaintercaionbio[0]]["USDT"] = floatval($cantidadmoneda/$usd);
					}

				}

				$cantidad++;
			}
			
			var_dump($fiats);
			//foreach()
		} 
	}
	new Informacion();
?>