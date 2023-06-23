<?php
	error_reporting(E_ALL);
    Class MonedaOperaciones{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            
            $consultar = $this->Conexion->Consultar("SELECT * FROM monedas");
            $retorno = '[';
            while($moneda = $this->Conexion->Recorrido($consultar)){
                 $retorno .= '{"nombre":"'.$moneda[0].'","moneda":"'.$moneda[1].'","decimales":"'.$moneda[2].'"},';
                 
            }
            $retorno = substr($retorno,0,strlen($retorno)-1)."],[";

            if(isset($_POST["moneda"])){
                $consultapais =$this->Conexion->Consultar("SELECT nombremoneda,iso_moneda,decimalesmoneda FROM paises WHERE iso_moneda='".$_POST["moneda"]."'");
            }else{
                $consultapais =$this->Conexion->Consultar("SELECT DISTINCT monedacompra,nombremoneda,decimalesmoneda,iso2 FROM tasas LEFT JOIN paises ON iso_moneda=monedacompra WHERE paises.receptor IS NOT NULL");
            }
            while($moneda = $this->Conexion->Recorrido($consultapais)){
                
                $retorno .= '{"nombre":"'.$moneda[1].'","moneda":"'.$moneda[0].'","decimales":"'.$moneda[2].'","pais":"'.$moneda[3].'"},';
            }
            
            return substr($retorno,0,strlen($retorno)-1)."]";
            
           
        } 
	}
	new MonedaOperaciones();
?>