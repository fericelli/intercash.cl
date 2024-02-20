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
           //return var_dump($_FILES['imagen']);
           
            $formatos = array(".jpg",".png",".jepg",".jpeg");
            $momento = date("Y-m-d H:i:s");
            $ErrorArchivo = $_FILES['imagen']['error'];
            $NombreArchivo = $_FILES['imagen']['name'];
            $NombreTmpArchivo = $_FILES['imagen']['tmp_name'];
            $ext = substr($_FILES['imagen']['name'],strrpos($_FILES['imagen']['name'], '.'));
            if(in_array($ext, $formatos)){
               if($ErrorArchivo>0){
                   return '"Intente Subir la Foto Nuevamente","error"';
               }else{
                    try{
                        //$registro = str_replace(" ", "", $_GET["registro"]);
                        $registro = base64_encode(str_replace(' ','',strtolower($_GET["usuario"])).$_GET["registro"]);
                        //solicitud es el codigo imagen

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
                        

                        $operacion = date("Y-m-d H:i:s");
                        sleep(1);
                        
                        $informacion = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT cantidadaenviar,tasa,decimalesmoneda,solicitudes.paisorigen,solicitudes.monedaorigen,anuncioventa,solicitudes.cantidadarecibir,solicitudes.monedadestino,solicitudes.paisdestino,solicitudes.momento FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisorigen LEFT JOIN tasas ON monedaventa=monedadestino AND monedacompra=monedaorigen AND tasas.paisorigen=solicitudes.paisorigen AND tasas.paisdestino = solicitudes.paisdestino WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'"));  
                        
                       $cantidadrecibida = number_format(floatval($_GET["cantidad"]/$informacion[1]),$informacion[2],".","");
                        $monedacambio = "";
                        $tasa = number_format(floatval($_GET["cantidad"]/$_GET["cambio"]),$informacion[2],".","");
                        $cantidadusdt = 0; 
                        if($_GET["moneda"]==$_GET["monedacambio"]){
                            $cantidadusdt = number_format($_GET["cambio"]/abs($informacion[5]),2,".","");
                            $monedacambio = "USDT";
                            $this->Conexion->Consultar("INSERT INTO operaciones(moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedacambio."','".$cantidadusdt."','compra','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".abs($informacion[5])."','".$informacion[7]."','".$informacion[8]."','".$_GET["cambio"]."','envios','".$cantidadusdt."')");
                            $cambio = number_format($cantidadusdt,2,".","");
                            $tasa = abs($informacion[5]);
                        }else{
                            if($_GET["monedacambio"]=='USDT'){
                                $cantidadusdt = number_format($_GET["cambio"],2,".","");
                                $cambio = number_format($_GET["cambio"],2,".","");
                            }else{
                                $cambio = number_format($_GET["cambio"],$_GET["decimal"],".","");
                                $cantidadusdt = number_format($_GET["cantidad"]/$informacion[5],2,".",""); 
                            }
                            $monedacambio = $_GET["monedacambio"];
                        }

                        
                        $cantidad = number_format($_GET["cantidad"],$_GET["decimal"],".","");

                        

                        $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$cantidad."','envios','capture".$total_imagenes.$ext."','".$_GET["registro"]."','".$_GET["usuario"]."')");
                        
                        
                        
                        
                        $retorno = "" ;
                        if($_GET["cantidad"]>=$_GET["pendiente"]){

                            $cantidadcambiada = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE registro='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."' AND tipo='envios'"))[0];
                            
                            $cantidadrecibida = number_format(floatval($informacion[0]-$cantidadcambiada),$informacion[2],".","");
                            $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                            $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedacambio."','".$cambio."','venta','".$operacion."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasa."','".$informacion[4]."','".$informacion[3]."','".$cantidadrecibida."','envios','".$cantidadusdt."')");
                            $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$informacion[6]."','".$informacion[7]."','".$informacion[0]."','".$informacion[4]."','".$_GET["usuario"]."','".date("Y-m-d H:i:s")."','".$_GET["registro"]."')");
                            return '["Remesa Finalizada","finalizar"],["0"],["0"]';
                        }else{
                            $pendiente = $_GET["total"]-$_GET["pendiente"]-$_GET["cantidad"];
                            $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedacambio."','".$cambio."','venta','".$operacion."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasa."','".$informacion[4]."','".$informacion[3]."','".$cantidadrecibida."','envios','".$cantidadusdt."')");
                        
                        }
                        return '["Screenshot Enviado","success"],["'.$pendiente.'"],["'.$_GET["moneda"].'"]';
                    }catch(Exception $e){
                        return '["Error","error"],["'.$e.'"]';
                    }
                }
            }else{
               return '["Formatos Permitidos Solo jpg y png","error"],["'.$_GET["pendiente"].'"]';
           }
        } 
	}
	new Enviar();
?>