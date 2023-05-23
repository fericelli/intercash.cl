<?php
	Class Finalizados{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $retorno = "";
            $consultar =  $this->Conexion->Consultar("SELECT * FROM intercambios WHERE intermediario='".$_POST["usuario"]."' OR usuario='".$_POST["usuario"]."' LIMIT 0,10");
            while($intercambios = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{
                    "monedadestino":"'.$intercambios[1].'",
                    "monedaorigen":"'.$intercambios[1].'",
                    "cantidadenviar":"'.$intercambios[2].'",
                    "cantidadrecibir":"'.$intercambios[0].'",
                    "intermadiario":"'.$intercambios[0].'",
                    "imegen":"../imagenes",
                    "momento":"'.$intercambios[0].'"},';
            }
            
            return substr($retorno,0,strlen($retorno)-1)."";
        } 
	}
	new Finalizados();
?>

