<?php
	Class Usuarios{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE usuario='".$_POST["usuario"]."' AND pais='".$_POST["pais"]."' AND tipo='pago'");
            $retorno = '{"cuentas":[';
            $validador = 0;
            while($datos = $this->Conexion->Recorrido($consultar )){
                $validador ++;
                $retorno .= '{"banco":"'.$datos[1].'","cuenta":"'.$datos[2].'","tipo":"'.$datos[3].'","nombres":"'.$datos[4].'","identificacion":"'.$datos[5].'"},';
            }
            if($validador==0){
                $retorno.= "]";
            }else{
                $retorno = substr($retorno,0,strlen($retorno)-1)."]";
            }
            return $retorno."}";
        }
	}
	new Usuarios();
?>