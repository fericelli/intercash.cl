<?php
	Class Finalizados{
		private $Conexion;
		function __construct(){
			include("../../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $retorno = "";
            $consultar =  $this->Conexion->Consultar("SELECT * FROM intercambios WHERE intermediario='".$_POST["usuario"]."' OR usuario='".$_POST["usuario"]."' LIMIT 0,10");
            while($intercambios = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{
                    "monedadestino":"'.$intercambios["monedaventa"].'",
                    "monedaorigen":"'.$intercambios["monedacompra"].'",
                    "cantidadenviar":"'.$intercambios["montoventa"].'",
                    "cantidadrecibir":"'.$intercambios["montocompra"].'",
                    "intermadiario":"'.$intercambios["intermediario"].'",';
                    $retorno .= '"imegen":[';
                    $consultarscreensho = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE registro='".$intercambios["momento"]."' AND usuario='".$intercambios["usuario"]."'");
                    $cantidad = 0;
                    while($screenshot = $this->Conexion->Recorrido($consultarscreensho)){
                        $cantidad ++;
                        $retorno .= '"'.$screenshot["directorio"].'",';
                    }
                    if($cantidad==0){
                        $retorno .= "],";
                    }else{
                        $retorno = substr($retorno,0,strlen($retorno)-1)."],";
                    }
                    $retorno .= '"momento":"'.$intercambios["momento"].'"},';
            }
            
            return substr($retorno,0,strlen($retorno)-1);
        } 
	}
	new Finalizados();
?>

