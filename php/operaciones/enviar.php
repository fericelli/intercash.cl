<?php
	error_reporting(E_ALL);
    Class Enviar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           
            

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
                        $operacion = date("Y-m-d H:i:s");
                        //$solicitar = $this->Conexion->Consultar("INSERT INTO solicitudes(monedadestino,monedaorigen,cantidadaenviar,cantidadarecibir,cuenta,banco,tipocuenta,nombres,identificacion,usuario,momento,estado,paisorigen,paisdestino) VALUES ('".$_GET["monedadestino"]."','".$_GET["monedaorigen"]."','".$_GET["cantidadenviar"]."','".$_GET["cantidadrecibir"]."','".$_GET["cuenta"]."','".$_GET["banco"]."','".$_GET["tipodecuenta"]."','".$_GET["nombres"]."','".$_GET["identificacion"]."','".str_replace(' ','',strtolower($_GET["usuario"]))."','".$momento."','nosolicitada','".$_GET["paisorigen"]."','".$_GET["paisdestino"]."')");
                        $consultar = $this->Conexion->Consultar("SELECT * FROM cuentas WHERE pais='".$_GET["paisdestino"]."' AND banco='".$_GET["banco"]."' AND cuenta='".$_GET["cuenta"]."' AND usuario='".str_replace(' ','',strtolower($_GET["usuario"]))."'");
                        if(!$this->Conexion->Recorrido($consultar)){
                            $this->Conexion->Consultar("INSERT INTO cuentas (pais,banco,cuenta,tipodecuenta,nombres,identificacion,usuario,tipo) VALUES ('".$_GET["paisdestino"]."','".$_GET["banco"]."','".$_GET["cuenta"]."','".$_GET["tipodecuenta"]."','".$_GET["nombres"]."','".$_GET["identificacion"]."','".str_replace(' ','',strtolower($_GET["usuario"]))."','envio')");
                        }
                        //$operacion = base64_encode(str_replace(' ','',strtolower($_GET["usuario"])).$momento);
                        
                        $carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]));
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d");
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        /*$carpeta = "./../../imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/".$registro;
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }*/
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                        $imagen = $carpeta."/capture".$total_imagenes.$ext;
                        $directorio = "imagenes/intercambios/envios/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/capture".$total_imagenes.$ext;
                        move_uploaded_file($NombreTmpArchivo,$imagen);

                        $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,operacion) VALUES ('".$_GET["cantidadrecibir"]."','".$_GET["monedadestino"]."','".$_GET["cantidadenviar"]."','".$_GET["monedaorigen"]."','".$_GET["usuario"]."','".date("Y-m-d H:i:s")."','".$operacion."')");
                       
                        $tasa = number_format($_GET["cantidadrecibir"]/$_GET["cantidadvendidaenvio"],$_GET["decimal"],".","");
                        $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$_GET["cantidadrecibir"]."','envios','capture".$total_imagenes.$ext."','".$operacion."','".$_GET["usuario"]."')");
                        $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,paisintercambio,montointercambio) VALUES ('".$_GET["monedaintercambio"]."','".$_GET["cantidadvendidaenvio"]."','venta','".$operacion."','".$_GET["usuario"]."','".$_GET["operador"]."','".$tasa."','".$_GET["monedaorigen"]."','".$_GET["paisorigen"]."','".$_GET["cantidadenviar"]."')");
                        
                        
                        return '"Opercion agregada","finalizar"';
                    }catch(Exception $e){
                        return '"Error","error"';
                    }
                    
               }
           }else{
               return '"Formatos Permitidos Solo jpg y png","error"';
           }


        } 
	}
	new Enviar();
?>