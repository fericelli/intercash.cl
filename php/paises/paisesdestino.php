<?php
	Class Paisesdestino{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consulta = $this->Conexion->Consultar("SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE iso2='".$_POST["pais"]."' AND iso_moneda='".$_POST["moneda"]."'");

			//$consultar = $this->Conexion->Consultar("SELECT iso2,iso_moneda,decimalesmoneda,nombre FROM tasas LEFT JOIN paises ON iso_moneda=monedaventa WHERE monedacompra='".$_POST["moneda"]."' AND paisorigen='".$tasa["iso2"]."'");
            $retorno = "";
			if($tasa = $this->Conexion->Recorrido($consulta)){
				$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$tasa["iso_moneda"]))->{'data'};
				
				$devaluacionorigen = 0;
				$consultardevaluacion = $this->Conexion->Consultar("SELECT porcentaje FROM devaluacion WHERE moneda='".$tasa["iso_moneda"]."'");
				if($devaluacion = $this->Conexion->Recorrido($consultardevaluacion)){
					$devaluacionorigen = $devaluacion[0];
				}
				$usdtorigen = number_format($usdorigen*floatval("1.".str_replace(".","",$devaluacionorigen)),$tasa["decimalesmoneda"],".","");
				//return "SELECT * FROM tasas LEFT JOIN paises ON iso2=paisdestino AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'";
				$consultar = $this->Conexion->Consultar("SELECT paisdestino,iso_moneda,decimalesmoneda,nombre,receptor,nombremoneda	 FROM tasas LEFT JOIN paises ON iso2=paisdestino AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'");
				$cantidad = 0;
				while($datos = $this->Conexion->Recorrido($consultar)){
					$retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[1].'","decimales":"'.$datos[2].'","nombre":"'.$datos[3].'","receptor":"'.$datos[4].'","nombremoneda":"'.$datos[5].'"},';
            
				}
			}
            /*while($datos = $this->Conexion->Recorrido($consultar)){

                }*/
            
			return $retorno = substr($retorno,0,strlen($retorno)-1);
            
		} 
	}
	new Paisesdestino();
?>

