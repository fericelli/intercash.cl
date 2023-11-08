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
			$consultar = $this->Conexion->Consultar("SELECT paisdestino,iso_moneda,decimalesmoneda,nombre,receptor,nombremoneda	 FROM tasas LEFT JOIN paises ON iso2=paisdestino AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$_POST["moneda"]."' AND paisorigen='".$_POST["pais"]."'");
			$cantidad = 0;
			while($datos = $this->Conexion->Recorrido($consultar)){
				$retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[1].'","decimales":"'.$datos[2].'","nombre":"'.$datos[3].'","receptor":"'.$datos[4].'","nombremoneda":"'.$datos[5].'"},';
            
			}
            
			return $retorno = substr($retorno,0,strlen($retorno)-1);
            
		} 
	}
	new Paisesdestino();
?>

