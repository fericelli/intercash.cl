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
                        $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,solicitud,tasa,montointercambio,tipo) VALUES ('".$_GET["monedacambio"]."','".$cambio."','venta','".$operacion."','".$_GET["usuario"]."','".$_GET["operador"]."','".$_GET["registro"]."','".$tasa."',0,'envio')");
                       
                        $retorno = "" ;
                        if($_GET["cantidad"]>=$_GET["pendiente"]){
                            $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                                
                            $totalintercambio = 0;
                            $consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisorigen   LEFT JOIN tasas ON monedaventa=monedadestino AND monedacompra=monedaorigen AND tasas.paisorigen=solicitudes.paisorigen AND tasas.paisdestino = solicitudes.paisdestino WHERE momento='".$_GET["registro"]."' AND usuario='".$_GET["usuario"]."'");
                            if($solicitudes = $this->Conexion->Recorrido($consultar)){
                                $tasa = number_format($solicitudes["cantidadarecibir"]/$solicitudes["cantidadaenviar"],$solicitudes["decimalestasa"],".","");
					
                                $cantidadenviar = 0;
                                //return "SELECT * FROM operaciones WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'";
                                $consultar2 = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                                $cantidadregistro = $this->Conexion->NFilas($consultar2);
                                if($cantidadregistro==1){
                                    if($operaciones = $this->Conexion->Recorrido($consultar2)){
                                        $this->Conexion->Consultar("UPDATE operaciones SET montointercambio=".$solicitudes["cantidadaenviar"]." WHERE usuario='".$operaciones["usuario"]."' AND solicitud='".$operaciones["solicitud"]."'");
                                        $totalintercambio = $solicitudes["cantidadarecibir"];
                                    }
                                }else{
                                    $total = 0;
                                    $not = [];
                                    $montoventa = 0;
                                    while($operaciones = $this->Conexion->Recorrido($consultar2)){
        
                                        $total ++;
                                        if(count($not)>0){
                                            $and =  "AND momento NOT IN (".implode(",",$not).")";
                                        }else{
                                            $and =  "";
        
                                        }
                                        if($operaciones["tasa"]==1){
                                            $sql = "SELECT *,ABS(operaciones.monto-screenshot.cantidad) AS diferencia FROM `operaciones` LEFT JOIN screenshot ON operaciones.usuario=screenshot.usuario AND (screenshot.registro=operaciones.solicitud OR screenshot.solicitud=operaciones.solicitud) LEFT JOIN paises ON paises.iso2=operaciones.paisintercambio WHERE operaciones.momento='".$operaciones["momento"]."' AND operaciones.solicitud='".$operaciones["solicitud"]."' AND operaciones.usuario='".$operaciones["usuario"]."' ".$and." ORDER BY diferencia ASC LIMIT 1";
                                        }else{
                                            $sql = "SELECT *,ABS((operaciones.monto*operaciones.tasa)-screenshot.cantidad) AS diferencia FROM `operaciones` LEFT JOIN screenshot ON operaciones.usuario=screenshot.usuario AND (screenshot.registro=operaciones.solicitud OR screenshot.solicitud=operaciones.solicitud)  LEFT JOIN paises ON paises.iso2=operaciones.paisintercambio WHERE operaciones.momento='".$operaciones["momento"]."' AND operaciones.solicitud='".$operaciones["solicitud"]."' AND operaciones.usuario='".$operaciones["usuario"]."' ".$and." ORDER BY diferencia ASC LIMIT 1";
                                        }
                                        //echo $sql."<br>";
                                        $consultar3 = $this->Conexion->Consultar($sql);
                                        if($operacion5 = $this->Conexion->Recorrido($consultar3)){
                                            $montointercambio = 0;
                                            
                                            if($operaciones["tasa"]==1){
                                                $montointercambio = $operacion5["monto"]/$tasa; 
                                            }else{
                                                $montointercambio = ($operacion5["monto"]*$operaciones["tasa"])/$tasa;
                                            }
                                            $montointercambio = number_format($montointercambio,$datos["decimalesmoneda"],".","");
                                            $modificar = $montointercambio;
                                            array_push($not,"'".$operacion5[3]."'");
                                            $totalintercambio += $montointercambio;
                                            $montoventa += $operacion5["cantidad"];
                                            if($total==$cantidadregistro){
                                                $modificar =  number_format($montointercambio+($solicitudes["cantidadaenviar"]-$totalintercambio),$solicitudes["decimalesmoneda"],".","") ."<br>";
                                                //$totalintercambio += $datos["cantidadaenviar"]-$totalintercambio;
                                            }
                                            $this->Conexion->Consultar("UPDATE operaciones SET montointercambio='".$modificar."' WHERE momento='".$operacion5["momento"]."' AND usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                                        }
                                        
                                    }
                                    
                                }

                                $consultar4 = $this->Conexion->Consultar("SELECT decimalesmoneda WHERE paises WHERE iso2='".$solicitudes["paisdestino"]."'");
                                if($paisdestino = $this->Conexion->Recorrido($consultar4)){
                                    $montoventa = number_format($montoventa,$paisdestino["decimalesmoneda"],".","");

                                }
                                $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,solicitud) VALUES ('". $montoventa."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".date("Y-m-d H:i:s")."','".$solicitudes["momento"]."')");
                                $this->Conexion->Consultar("UPDATE operaciones SET paisintercambio='".$solicitudes["paisorigen"]."',monedaintercambio='".$solicitudes["monedaorigen"]."' WHERE usuario='".$solicitudes["usuario"]."' AND solicitud='".$solicitudes["momento"]."'");
                                
                               
                                
                            }
                            
                            return '["Remesa Finalizada","finalizar"],["0"],["0"]';
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