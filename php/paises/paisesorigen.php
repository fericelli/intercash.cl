<?php
	Class Paisesorigen{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
            
            $retorno = "[";

			while($datos = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
            }

			$retorno = substr($retorno,0,strlen($retorno)-1)."],[";
			return "SELECT DISTINCT(monedacompra),paises.* FROM tasas LEFT JOIN paises ON iso_moneda=monedacompra WHERE recertor IS NOT NULL";
            $consulta2 = $this->Conexion->Consultar("SELECT DISTINCT(monedacompra),paises.* FROM tasas LEFT JOIN paises ON iso_moneda=monedacompra WHERE recertor IS NOT NULL");
            while($datos1 = $this->Conexion->Recorrido($consulta2)){
                
                $retorno .= '{"iso_pais":"'.$datos1[1].'","moneda":"'.$datos1[0].'","decimales":"'.$datos1[5].'","nombre":"'.$datos1[3].'"},';
            }
            return substr($retorno,0,strlen($retorno)-1)."]";
		} 
	}
	new Paisesorigen();
?>

