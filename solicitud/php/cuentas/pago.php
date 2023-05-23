<?php
	Class Pago{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            
            //return "SELECT iso2 FROM paises INNER JOIN cuentas ON pais=iso2 WHERE iso_moneda='".$_POST["monedaorigen"]."' AND tipo='pago'";
            $consultar = $this->Conexion->Consultar("SELECT * FROM paises INNER JOIN cuentas ON pais=iso2 WHERE iso_moneda='".$_POST["monedaorigen"]."' AND tipo='pago'");
            $retorno = '{"cuentas":[';
            $validador = 0;
            while($datos = $this->Conexion->Recorrido($consultar )){
                $validador ++;
                $retorno .= '{"banco":"'.$datos[8].'","cuenta":"'.$datos[9].'","tipo":"'.$datos[10].'","nombres":"'.$datos[11].'","identificacion":"'.$datos[12].'","usuario":"'.$datos[13].'"},';
            }
            if($validador==0){
                $retorno.= "]";
            }else{
                $retorno = substr($retorno,0,strlen($retorno)-1)."]";
            }
            return $retorno."}";
        }
	}
	new Pago();
?>