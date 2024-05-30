<?php
    Class FinalizarSolicitudes{
        private $Conexion;
		function __construct(){
			//include("/home/u956446715/public_html/public_html/php/conexion.php");
			include("./php/conexion.php");
			$this->Conexion = new Conexion();
            try{
				$consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisdestino WHERE estado IS NULL");
				while($solicitudes = $this->Conexion->Recorrido($consultar)){
					
                    $tasausd = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$solicitudes[0]."'"))[0];
					$cantidadusd = number_format($solicitudes["cantidadarecibir"]/$tasausd,2,".","");
                    $momento = date('Y-m-d H:i:s', strtotime("+90 Minute",strtotime($solicitudes["momento"])));
					//echo $solicitudes["momento"]." ".$momento."\n";
                    //echo $solicitudes[0]." ".number_format($tasausd,$solicitudes["decimalesmoneda"],".","")."   ".$cantidadusd."<br>";
					$this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$solicitudes["momento"]."'");
					$this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$solicitudes["cantidadarecibir"]."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".date("Y-m-d H:i:s")."','".$solicitudes["momento"]."')");
					$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('BTC',0,'venta','".$momento."','".$solicitudes["usuario"]."','javier','".$solicitudes["momento"]."',0,'".$solicitudes["monedaorigen"]."','".$solicitudes["paisorigen"]."','".$solicitudes["cantidadaenviar"]."','envios',".$cantidadusd.")");
					
				}
			}catch(Exception $e){
				return  $e;
			}
        }
    }
    new FinalizarSolicitudes();
?>