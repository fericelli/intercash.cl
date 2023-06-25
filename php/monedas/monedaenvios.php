<?php
	error_reporting(E_ALL);
    Class MonedaEnvios{
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
           return  "SELECT nombremoneda,iso_moneda,decimalesmoneda,paisorigen,paisdestino FROM solicitudes LEFT JOIN paises ON iso2=paisdestino WHERE momento='".$_POST["registro"]."' AND usuario='".$_POST["usuario"]."'";
            $consultapais =$this->Conexion->Consultar("SELECT nombremoneda,iso_moneda,decimalesmoneda,paisorigen,paisdestino FROM solicitudes LEFT JOIN paises ON iso2=paisdestino WHERE momento='".$_POST["registro"]."' AND usuario='".$_POST["usuario"]."'");
            while($moneda = $this->Conexion->Recorrido($consultapais)){
                
                $retorno .= '{"nombre":"'.$moneda["nombremoneda"].'","moneda":"'.$moneda["iso_moneda"].'","decimales":"'.$moneda["decimalesmoneda"].'","pais":"'.$moneda["paisdestino"].'"},';
            }
            
            return substr($retorno,0,strlen($retorno)-1)."]";
            
           
        } 
	}
	new MonedaEnvios();
?>