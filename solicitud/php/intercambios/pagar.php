<?php
	error_reporting(E_ALL);
    Class Pagar{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion("intercash.cl");
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
                        $registro = base64_encode($_GET["usuario"].$_GET["registro"]);
                        //solicitud es el codigo imagen
                        $carpeta = "./../../../imagenes/intercambios/pagos/".$registro;
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        //return $NombreArchivo;
                        //return base64_decode($registro);
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                        $directorio = $carpeta."/capture".$total_imagenes.$ext;
                    
                        move_uploaded_file($NombreTmpArchivo,$directorio);

                        
                        $this->Conexion->Consultar("INSERT INTO screenshot (solicitud,cantidad,tipo,nombre) VALUES ('".$registro."','".$_GET["cantidad"]."','pagos','capture".$total_imagenes.$ext."')");
                        return '"Screenshot Enviado","success"';
                    }catch(Exception $e){
                        return '"Error","error"';
                    }
                    
               }
           }else{
               return '"Formatos Permitidos Solo jpg y png","error"';
           }
        } 
	}
	new Pagar();
?>