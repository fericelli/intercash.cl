<?php
	error_reporting(E_ALL);
    Class Enviar{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           try{
                $formatos = array(".jpg",".png",".jepg",".jpeg");
                $momento = date("Y-m-d H:i:s");
                $ErrorArchivo = $_FILES['imagen']['error'];
                $NombreArchivo = $_FILES['imagen']['name'];
                $NombreTmpArchivo = $_FILES['imagen']['tmp_name'];
                $ext = substr($_FILES['imagen']['name'],strrpos($_FILES['imagen']['name'], '.'));
                $directorio = "";
                
                if(in_array($ext, $formatos)){
                    if($ErrorArchivo>0){
                       
                    }else{
                        $carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]));
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d");
                            if(!file_exists($carpeta)){
                                mkdir($carpeta,0777, true);
                        }
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                        $imagen = $carpeta."/capture".$total_imagenes.$ext;
                        $directorio = "imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/capture".$total_imagenes.$ext;
                        move_uploaded_file($NombreTmpArchivo,$imagen);
                        /*$url =  "https://api.apichat.io/v1/sendImage";
                        $parameters = [
                            'phone' => '584149192168',
                            'url' => 'httsp://intercash.cl/'.$directorio,
                            'caption' => ' '
                        ];
                        $headers = [
                            "accept: application/json",
                            "token: kMOMQkkx1Zxn",
                            "client-id: 26892"
                        ];
                        $qs = http_build_query($headers); 
                        $request = "{$url}?{$qs}"; 
                        $curl = curl_init(); 
                         // Set cURL options
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $request,            
                            CURLOPT_POSTFIELDS => $parameters, 
                            CURLOPT_POST => 1,     
                            CURLOPT_RETURNTRANSFER => 1         
                        ));

                        $response = curl_exec($curl); // Send the request, save the response
                        print_r($response);
                        curl_close($curl); // Close request*/
                    }        
                            
                }
                $informacion = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT cantidadaenviar,tasa,decimalesmoneda,solicitudes.paisorigen,solicitudes.monedaorigen,anuncioventa,solicitudes.cantidadarecibir,solicitudes.monedadestino,solicitudes.paisdestino,solicitudes.momento FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisorigen LEFT JOIN tasas ON monedaventa=monedadestino AND monedacompra=monedaorigen AND tasas.paisorigen=solicitudes.paisorigen AND tasas.paisdestino = solicitudes.paisdestino WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'"));  
                $decimalesdestino = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimalesmoneda FROM paises WHERE iso_moneda='".$informacion[7]."' AND iso2='".$informacion[8]."'"))[0];
                $monedacambio = "";
                $cambio = 0;
                $tasa = number_format(floatval($_GET["cantidad"]/$_GET["cambio"]),$decimalestasa,".","");
                $cantidadusdt = number_format($_GET["cantidad"]/abs($informacion[5]),2,".","");
                $tasaenvio = $informacion[5];
                $cantidadusdtcompra = 0;
                if($_GET["moneda"]==$_GET["monedacambio"]){
                    $cambio = number_format($cantidadusdt,2,".","");
                    $tasausd = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anunciocompra) FROM tasas WHERE monedaventa='".$informacion[7]."'"))[0];
					$cantidadusdtcompra = number_format($_GET["cambio"]/$tasausd,2,".","");
                    $monedacambio = "USDT";
                    $this->Conexion->Consultar("INSERT INTO operaciones(moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('USDT','".$cantidadusdtcompra."','compra','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasausd."','".$informacion[7]."','".$informacion[8]."','".$_GET["cambio"]."','envios','".$cantidadusdtcompra."')");
                    
                }else{
                    $tasaenvio = 0;
                   if($_GET["monedacambio"]=='USDT' OR $_GET["monedacambio"]=='USDE'){
                        $cambio = number_format($cantidadusdt,2,".","");
                        $tasaenvio = $informacion[5];  
                    }
                    $monedacambio = $_GET["monedacambio"];
                }
                $cantidad = number_format($_GET["cantidad"],$_GET["decimal"],".","");
                //$cantidadusdt = number_format($_GET["cantidad"]/$informacion[5],2,".",""); 

                $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$cantidad."','envios','capture".$total_imagenes.$ext."','".$_GET["registro"]."','".$_GET["usuario"]."')");                    
                $cantidadrecibida = number_format(floatval($_GET["cantidad"]/$informacion[1]),$informacion[2],".","");
                if($_GET["cantidad"]>=$_GET["pendiente"]){
                    $cantidadderegistro = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT COUNT(*) FROM operaciones WHERE registro='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."' AND tipo='envios' AND operacion='venta'"))[0];
                    $cantidadcambiada = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE registro='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."' AND tipo='envios' AND operacion='venta'"))[0];
                    if($cantidadderegistro==0){
                        $cantidadrecibida = $informacion[0];
                    }else{
                       $cantidadrecibida = number_format(floatval($informacion[0]-$cantidadcambiada),$informacion[2],".","");
                    }
                    $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                    $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedacambio."','".$cambio."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasaenvio."','".$informacion[4]."','".$informacion[3]."','".$cantidadrecibida."','envios','".$cantidadusdt."')");
                    $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$informacion[6]."','".$informacion[7]."','".$informacion[0]."','".$informacion[4]."','".$_GET["usuario"]."','".date("Y-m-d H:i:s")."','".$_GET["registro"]."')");
                   return '["Remesa Finalizada","finalizar","'.$directorio.'"],["0"],["0"]';
                }else{
                    $pendiente = $_GET["pendiente"]-$_GET["cantidad"];
                    $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedacambio."','".$cambio."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasaenvio."','".$informacion[4]."','".$informacion[3]."','".$cantidadrecibida."','envios','".$cantidadusdt."')");
                    $this->Conexion->Consultar("UPDATE solicitudes SET estado='procesando' WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                    
                }
                return '["Screenshot Enviado","success","'.$directorio.'"],["'.number_format($pendiente,$decimalesdestino,".","").'"],["'.$_GET["moneda"].'"]';
                         
            }catch(Exception $e){
                return '["Error","error"],["'.$e.'"]';
            }
        } 
	}
	new Enviar();
?>