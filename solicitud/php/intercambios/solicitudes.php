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
            if($_POST["tipodeusuario"]=="administrador"){
                $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LIMIT 0,".$cantidad."");
            
            }else{
                $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE usuario='".$_POST["usuario"]."' LIMIT 0,".$cantidad."");
            
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
                    "datos":"'.$datos.'",
                    "estado":';

                if($solicitudes[11]==NULL){
                    $retorno .= '"pendiente",';
                }else{
                    $retorno .= '"procesando",';
                }
                $registro = base64_encode($_POST["usuario"].$solicitudes[10]);


                $retorno .= '"envios":[';
                $validar = 0;
                $consulaimagen = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE solicitud='".$registro."' AND tipo='envios'");
                while($envios = $this->Conexion->Recorrido($consulaimagen)){
                    $validar ++;
                    $retorno .= '"envios/'.$registro.'/'.$envios[3].'",';
                }
                if($validar<1){
                    $retorno .= "],";
                }else{
                    $retorno = substr($retorno,0,strlen($retorno)-1).'],';
                }
                $retorno .= '"pagos":[';
                $validar = 0;
                $consulaimagen = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE solicitud='".$registro."' AND tipo='pagos'");
                while($envios = $this->Conexion->Recorrido($consulaimagen)){
                    $validar ++;
                    $retorno .= '"pagos/'.$registro.'/'.$envios[3].'",';
                }
                if($validar<1){
                    $retorno .= "],";
                }else{
                    $retorno = substr($retorno,0,strlen($retorno)-1).'],';
                }

                $retorno .= '"momento":"'.$solicitudes[10].'",
                "usuario":"'.$solicitudes[9].'"},';
            }
            
            
            
           return substr($retorno,0,strlen($retorno)-1)."";
        } 
	}
	new Solicitudes();
?>