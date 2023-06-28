<?php
	Class Solicitudes{
		private $Conexion;
		function __construct(){
			
			include("../../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           $cantidad = 5;
            if(isset($_POST["cantidad"])){
                $cantidad = $_POST["cantidad"]+5;
                $inicio = $_POST["cantidad"]-1;
            }else{
                $inicio=0;
            }
            if($_POST["tipodeusuario"]=="administrador"){
                $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE estado<>'finalizado' OR estado IS NULL  LIMIT 0,".$cantidad."");
            
            }else{
                $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE usuario='".$_POST["usuario"]."' AND (estado<>'finalizado' OR estado IS NULL) LIMIT 0,".$cantidad."");
            
            }
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
                    "datos":"'.$datos.'",';
                

                $retorno .= '"envios":[';
                $validar = 0;
                $pagado = 0;

                $registro = base64_encode($solicitudes["usuario"].$solicitudes["momento"]);
                //echo "SELECT * FROM screenshot WHERE directorio='".$registro."' AND tipo='envios'";
                return "SELECT * FROM screenshot WHERE registo='".$solicitudes["momento"]."' AND usuario='".$solicitudes["usuario"]."' AND tipo='envios'";
                $consulaimagen = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE directorio='".$registro."' AND tipo='envios'");
                while($envios = $this->Conexion->Recorrido($consulaimagen)){
                    $pagado += $envios["cantidad"];
                    $validar ++;
                    $retorno .= '"'.$registro.'/'.$envios[3].'",';
                }
                if($validar<1){
                    $retorno .= "],";
                }else{
                    $retorno = substr($retorno,0,strlen($retorno)-1).'],';
                }
                
                
                $retorno .= '"estado":';
                
                    
                if($solicitudes[11]==NULL){
                    $retorno .= '"pendiente",';
                }else{
                    $retorno .= '"'.$solicitudes[11].'",';
                }
                $retorno .= '"momento":"'.$solicitudes[10].'",
                "usuario":"'.$solicitudes[9].'",
                "pagado":"'.$pagado.'"},';
            }
            
            
            
           return substr($retorno,0,strlen($retorno)-1)."";
        } 
	}
	new Solicitudes();
?>