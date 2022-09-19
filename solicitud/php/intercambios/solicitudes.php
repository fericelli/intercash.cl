<?php
	Class Solicitudes{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // return var_dump($_POST);
            $cantidad = 5;
            if(isset($_POST["cantidad"])){
                $cantidad = $_POST["cantidad"]+5;
            }
			$consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE usuario='".$_POST["usuario"]."' LIMIT 0,".$cantidad."");
            $retorno = "";
            while($solicitudes = $this->Conexion->Recorrido($consultar)){
                $datos = $solicitudes[5].".".
                        $solicitudes[6]." ".$solicitudes[4].".". 
                        $solicitudes[7].".".  
                        $solicitudes[8];
                $retorno .= '{
                    "monedadestino":"'.$solicitudes[0].'",
                    "monedaorigen":"'.$solicitudes[1].'",
                    "cantidadenviar":"'.$solicitudes[2].'",
                    "cantidadrecibir":"'.$solicitudes[3].'",
                    "datos":"'.$datos.'",
                    "estado":';

                if($solicitudes[11]==NULL){
                    $retorno .= '"pendiente",';
                }else{
                    $retorno .= '"../imagenes",';
                }
                $retorno .= '"momento":"'.$solicitudes[10].'"},';
            }
            
            
            
           return substr($retorno,0,strlen($retorno)-1)."";
        } 
	}
	new Solicitudes();
?>