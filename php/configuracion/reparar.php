<?php
	Class Reparar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			 $this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $cantidad = 0;
            $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisorigen");
            while($solicitudes = $this->Conexion->Recorrido($consultar)){
                 $consultar1 = $this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                 $monto = $this->Conexion->Recorrido($consultar1)[0];
                 
                if($monto!=$solicitudes["cantidadaenviar"] and $monto!=""){
                    $cantidad ++;
                    $this->Conexion->Consultar("UPDATE operaciones SET monedaintercambio=NULL,montointercambio=0,paisintercambio=NULL WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                    $montoenvio = $solicitudes["cantidadaenviar"];
                    $montorecibir = $solicitudes["cantidadarecibir"];
                    $moneda = $solicitudes["monedadestino"];
                    $consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                    $tasa =  $montorecibir/$montoenvio;
                    
                    while($operaciones = $this->Conexion->Recorrido($consulta)){

                        if($operaciones["tasa"]=="1"){
                            $montoenviado = $operaciones["monto"];
                            $sql = "SELECT *,ABS (".$montoenviado."-cantidad) AS diferencia FROM operaciones LEFT JOIN screenshot ON registro=solicitud AND cantidad=monto WHERE operaciones.usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."' AND monedaintercambio IS NULL ORDER BY diferencia ASC LIMIT 1";
                        }else{
                            $montoenviado = $operaciones["monto"]*$operaciones["tasa"];
                            $sql = "SELECT *,ABS (".$montoenviado."-cantidad) AS diferencia FROM operaciones LEFT JOIN screenshot ON registro=solicitud WHERE operaciones.usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."' AND monedaintercambio IS NULL ORDER BY diferencia ASC LIMIT 1";
                        }
                        
                        $consultas = $this->Conexion->Consultar($sql);
                        if($monto = $this->Conexion->Recorrido($consultas)){
                            $envio = floatval($monto["cantidad"]/$tasa);
                            $envio = number_format($envio,floatval($solicitudes["decimalesmoneda"]),".","");
                            $this->Conexion->Consultar("UPDATE operaciones SET monedaintercambio='".$solicitudes["monedaorigen"]."',montointercambio='".$envio."',paisintercambio='".$solicitudes["paisorigen"]."' WHERE momento='".$operaciones["momento"]."' AND usuario='".$operaciones["usuario"]."'");
                            
                        }
                    }
                }
                /*
                   $cantidad ++;
                }*/
            }
            
            return  $cantidad;
        }
	}
	new Reparar();
?>