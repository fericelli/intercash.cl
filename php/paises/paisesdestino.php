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
			$consultar = $this->Conexion->Consultar("SELECT iso2,iso_moneda,decimalesmoneda,nombre FROM tasas LEFT JOIN paises ON iso_moneda=monedaventa WHERE monedacompra='".$_POST["moneda"]."'");
            $retorno = "";
            while($datos = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[1].'","decimales":"'.$datos[2].'","nombre":"'.$datos[3].'"},';
            }
            
			return $retorno = substr($retorno,0,strlen($retorno)-1);
            
		} 
	}
	new Paisesdestino();
?>

