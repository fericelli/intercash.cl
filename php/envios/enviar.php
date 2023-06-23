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
                        $carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/".$registro;
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                       $imagen = $carpeta."/capture".$total_imagenes.$ext;
                         $directorio = "imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/".$registro."/capture".$total_imagenes.$ext;
                        move_uploaded_file($NombreTmpArchivo,$imagen);
                        
                       $tasa = number_format($_GET["cantidad"]/$_GET["cambio"],$_GET["decimal"],".","");


                       
                         $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$_GET["cantidad"]."','envios','capture".$total_imagenes.$ext."','".$_GET["registro"]."','".$_GET["usuario"]."')");
                        $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,solicitud,tasa) VALUES ('".$_GET["monedacambio"]."','".$_GET["cambio"]."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasa."')");
                        $total = 0;
                        $pendiente = 0;
                        $moneda = "";
                        
                        

                        if($_GET["cantidad"]>=$_GET["pendiente"]){
                            
                            if($_GET["cantidad"]>$_GET["pendiente"]){
                                $total = $_GET["cantidad"]-$_GET["pendiente"]+$_GET["total"];
                            }else{
                                $total = $_GET["total"];
                            }
                            
                            $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON iso_moneda=monedaorigen  WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                            if($solicitudes = $this->Conexion->Recorrido($consultar)){
                                $montoenvio = $solicitudes["cantidadaenviar"];
                                $montorecibir = $solicitudes["cantidadarecibir"];
                                
                                $moneda = $solicitudes["monedadestino"];
                                $consulta = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE usuario='".$_GET["usuario"]."' AND solicitud='".$_GET["registro"]."'");
                                $tasa =  $montorecibir/$montoenvio;
                                while($operaciones = $this->Conexion->Recorrido($consulta)){
                                    $montoenviado = $operaciones["monto"]*$operaciones["tasa"];
                                    $consultas = $this->Conexion->Consultar("SELECT *,(".$montoenviado."-cantidad) diferencia FROM operaciones LEFT JOIN `screenshot` ON registro=solicitud WHERE operaciones.usuario='".$_GET["usuario"]."' AND solicitud='".$_GET["registro"]."' AND monedaintercambio IS NULL ORDER BY diferencia ASC LIMIT 1");
                                    if($monto = $this->Conexion->Recorrido($consultas)){
                                        $dinero = $monto["cantidad"]/$tasa;
                                        $this->Conexion->Consultar("UPDATE operaciones SET monedaintercambio='".$solicitudes["monedaorigen"]."',montointercambio='".$dinero."',paisintercambio='".$solicitudes["iso2"]."' WHERE momento='".$monto["momento"]."'");
                                    }

                                }
                                $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$solicitudes["momento"]."'");
                                $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,solicitud) VALUES ('".$solicitudes["cantidadarecibir"]."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".date("Y-m-d H:i:s")."','".$solicitudes["momento"]."')");
                            }
                            
                            
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