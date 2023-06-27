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
			while($moneda = $this->Conexion->Recorrido($consultar)){
				array_push($selectormonedas,'"'.$moneda["iso_moneda"].'"');
				$monedas[$moneda["iso_moneda"]]["moneda"] = $moneda["iso_moneda"];
				$consultar1 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$moneda["iso_moneda"]."' AND operacion='venta'");
				$consultar2 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='".$moneda["iso_moneda"]."' AND operacion='compra'");
				if($cantidad1 = $this->Conexion->Recorrido($consultar1)){
					$monedas[$moneda["iso_moneda"]]["decimales"] = $moneda["decimales"];
					$monedas[$moneda["iso_moneda"]]["operaciones"]["venta"] = number_format($cantidad1[0],$moneda["decimales"],".","");
					$monedas[$moneda["iso_moneda"]]["operaciones"]["compra"] = number_format($cantidad2[0],$moneda["decimales"],".","");;
				}
			}
			$fiats = [];
			$consultar3 = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
			while($fiat = $this->Conexion->Recorrido($consultar3)){
				$fiats[$fiat["iso_moneda"]]["moneda"] = $fiat["iso2"];
			}
			
			var_dump($fiats);
		} 
	}
	new Informacion();
?>