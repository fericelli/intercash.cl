<?php
	Class Operar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // var_dump($_GET);exit;
             $formatos = array("image/jpeg","image/png","application/pdf");
            $momento = date("Y-m-d H:i:s");
            $ErrorArchivo1 = $_FILES[0]['error'];
            $NombreArchivo1 = $_FILES[0]['name'];
            $NombreTmpArchivo1 = $_FILES[0]['tmp_name'];
            $tipoarchivo1 = $_FILES[0]['type'];
            $ErrorArchivo2 = $_FILES[1]['error'];
            $NombreArchivo2 = $_FILES[1]['name'];
            $NombreTmpArchivo2 = $_FILES[1]['tmp_name'];
            $tipoarchivo2 = $_FILES[1]['type'];
            //$ext = substr($_FILES['imagen']['name'],strrpos($_FILES['imagen']['name'],'.'));
            if(in_array($tipoarchivo1, $formatos) AND in_array($tipoarchivo2, $formatos)){
                if($tipoarchivo1>0 AND $tipoarchivo2>0){
                    return '"Intente Subir la Foto Nuevamente","error"';
                }else{
                    try{
                        
                        $cantidadmoneda = number_format($_GET["cantidadmoneda"],$_GET["decimalmoneda"],".","");
                        $cantidadcripto = number_format($_GET["cantidadcripto"],$_GET["decimalcripto"],".","");
                        $arraymontos = ["pagado","recibido"];
                        $momento = date("Y-m-d H:i:s");

                        $tasa = number_format($_GET["cantidadmoneda"]/$_GET["cantidadcripto"],$_GET["decimalcripto"],".","");
                        $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,paisintercambio,montointercambio) VALUES ('".$_GET["cripto"]."','".$cantidadcripto."','compra','".$momento."','".str_replace(" ", "", $_GET["usuario"])."','".str_replace(" ", "", $_GET["usuario"])."','".$tasa."','".$_GET["moneda"]."','".$_GET["pais"]."','".$cantidadmoneda."')");
                        

                        
                        //$registro = str_replace(" ", "", $_GET["registro"]);
                        //$registro = base64_encode(str_replace(' ','',strtolower($_GET["usuario"])).$_GET["registro"]);
                        //solicitud es el codigo imagen

                        $carpeta = "./../../imagenes/operaciones/compra/".str_replace(' ','',strtolower($_GET["usuario"]));
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpeta = "./../../imagenes/operaciones/compra/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d");
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpetas = "operacion".intval(count(scandir($carpeta))-1);
                        $carpeta = "./../../imagenes/operaciones/compra/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/".$carpetas;
                        
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        for($i=0;$i<count($_FILES);$i++){
                             $ext = substr($_FILES[$i]['name'],strrpos($_FILES[$i]['name'],'.'));
                            $imagen = $carpeta."/".$arraymontos[$i].$ext;
                            $directorio = "imagenes/operaciones/compra/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/".$carpetas."/".$arraymontos[$i].$ext;
                            move_uploaded_file($_FILES[$i]['tmp_name'],$imagen);
                            $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$_GET["cantidadmoneda"]."','compra','".$arraymontos[$i].$ext."','".$momento."','".$_GET["usuario"]."')");
                        }
                        

                        return '"Compra realizada","success"';
                    }catch(Exception $e){
                        return '"'.$e.'","error"';
                    }
                    
               }
           }else{
               return '"Formatos Permitidos Solo jpg, png o pdf","error"';
           }
		} 
	}
	new Operar();
?>