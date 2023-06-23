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
            
            if($_POST["tipodeusuario"]=="administrador"){

                $consultar =  $this->Conexion->Consultar("SELECT * FROM intercambios WHERE momento LIKE '".$_POST["fecha"]."%'");
            }else{
                $consultar =  $this->Conexion->Consultar("SELECT * FROM intercambios WHERE momento LIKE '".$_POST["fecha"]."%' AND intermediario='".$_POST["usuario"]."'");
            }
            

            while($intercambios = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{
                    "monedadestino":"'.$intercambios["monedaventa"].'",
                    "monedaorigen":"'.$intercambios["monedacompra"].'",
                    "cantidadenviar":"'.$intercambios["montoventa"].'",
                    "cantidadrecibir":"'.$intercambios["montocompra"].'",
                    "intermadiario":"'.$intercambios["intermediario"].'",';
                    $retorno .= '"imegen":[';
                    $consultarscreensho = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE registro='".$intercambios["solicitud"]."' AND usuario='".$intercambios["intermediario"]."'");
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
