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
                        $tasa = number_format($_GET["cantidad"]/$_GET["cambio"],$_GET["decimal"],".","");
                        $cambio = number_format($_GET["cambio"],$_GET["decimal"],".","");
                        $cantidad = number_format($_GET["cantidad"],$_GET["decimal"],".","");

                        $operacion = date("Y-m-d H:i:s");

                        
                        $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario,solicitud) VALUES ('".$directorio."','".$cantidad."','envios','capture".$total_imagenes.$ext."','".$operacion."','".$_GET["usuario"]."','".$_GET["registro"]."')");
                        $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,solicitud,tasa,montointercambio) VALUES ('".$_GET["monedacambio"]."','".$cambio."','venta','".$operacion."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasa."',0)");
                       
                       
                        $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisorigen   LEFT JOIN tasas ON monedaventa=monedadestino AND monedacompra=monedaorigen AND tasas.paisorigen=solicitudes.paisorigen AND tasas.paisdestino = solicitudes.paisdestino WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                        if($solicitudes = $this->Conexion->Recorrido($consultar)){
                            $montoenvio = $solicitudes["cantidadaenviar"];
                            $montorecibir = $solicitudes["cantidadarecibir"];
                            $tasaenvio = number_format($montorecibir/$montoenvio,$solicitudes["decimalestasa"],".","");

                            $cantidadenviada = number_format($cantidad/$tasaenvio,$solicitudes["decimalesmoneda"],".","");
                            $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,solicitud,operacion) VALUES ('".$cantidad."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".date("Y-m-d H:i:s")."','".$solicitudes["momento"]."','".$operacion."')");
                            $this->Conexion->Consultar("UPDATE operaciones SET monedaintercambio='".$solicitudes["monedaorigen"]."',paisintercambio='".$solicitudes["paisorigen"]."',montointercambio=".$solicitudes["cantidadaenviar"]." WHERE solicitud='".$solicitudes["momento"]."' AND usuario='".$solicitudes["usuario"]."' AND momento='".$operacion."'");
                            
                        }
                        if($_GET["cantidad"]>=$_GET["pendiente"]){
                            $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$solicitudes["momento"]."'");
                            return '["Remesa finalizada","finalizar"],["0"],["0"]';
                        }else{
                            $pendiente = $_GET["total"]-$_GET["cantidad"];
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