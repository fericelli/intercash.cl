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

            
            $consultapais =$this->Conexion->Consultar("SELECT nombremoneda,iso_moneda,decimalesmoneda FROM paises WHERE iso_moneda='".$_POST["moneda"]."'");
            while($moneda = $this->Conexion->Recorrido($consultapais)){
                
                $retorno .= '{"nombre":"'.$moneda[0].'","moneda":"'.$moneda[1].'","decimales":"'.$moneda[2].'"},';
            }
            if(isset($_POST["moneda"])){
                return substr($retorno,0,strlen($retorno)-1)."]";
            }else{
                return $retorno."]";
            }
            
           
        } 
	}
	new MonedaEnvios();
?>