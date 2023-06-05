<?php
	error_reporting(E_ALL);
    Class Depositar{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			//var_dump($_GET);
            //var_dump($_FILES['file']);
            $formatos = array(".jpg",".png",".jepg",".jpeg");
            $momento = date("Y-m-d H:i:s");
            $ErrorArchivo = $_FILES['file']['error'];
            $NombreArchivo = $_FILES['file']['name'];
            $NombreTmpArchivo = $_FILES['file']['tmp_name'];
            $ext = substr($_FILES['file']['name'],strrpos($_FILES['file']['name'], '.'));
                
            if(in_array($ext, $formatos)){
                if($ErrorArchivo>0){
                    return '"Intente Subir la Foto Nuevamente","error"';
                }else{
                    try{
                        $registro = date("Y-m-d H:i:s");
                        //$registro = str_replace(" ", "", $_GET["registro"]);
                        //$registro = base64_encode($_GET["usuariocuenta"]);
                        //solicitud es el codigo imagen
                       // $carpeta = "./../../imagenes/depositos/".base64_encode($_GET["usuariocuenta"].explode(" ", $registro)[0]);
                        $carpeta = "./../../imagenes/depositos/".$_GET["usuario"];
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpeta = "./../../imagenes/depositos/".$_GET["usuario"]."/".explode(" ", $registro)[0];
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        /*$carpeta = "./../../imagenes/depositos/".$_GET["usuariocuenta"]."/";
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }*/
                        //return $NombreArchivo;
                        //return base64_decode($registro);
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                        $directorio = $carpeta."/capture".$total_imagenes.$ext;
                    
                        move_uploaded_file($NombreTmpArchivo,$directorio);
                        //$imagen = "/imagenes/depositos/".base64_encode($_GET["usuariocuenta"].explode(" ", $registro)[0])."/capture".$total_imagenes.$ext;
                        $imagen = "imagenes/depositos/".$_GET["usuario"]."/".explode(" ", $registro)[0]."/capture".$total_imagenes.$ext;
                        
                        $this->Conexion->Consultar("INSERT INTO depositos (momento,cantidad,banco,tipodecuenta,cuenta,nombre,identificacion,usuariocuenta,usuario,directorio,pais,moneda) VALUES ('".$registro."','".$_GET["cantidad"]."','".$_GET["banco"]."','".$_GET["tipodecuenta"]."','".$_GET["cuenta"]."','".$_GET["nombre"]."','".$_GET["identificacion"]."','".$_GET["usuariocuenta"]."','".$_GET["usuario"]."','".$imagen."','".$_GET["pais"]."','".$_GET["moneda"]."')");
                        
                        return '"Deposito enviado","success"';
                    }catch(Exception $e){
                        return '"Error","error"';
                    }
                }
            }else{
                return '"Formatos Permitidos Solo jpg , jepg y  png","error"';
            }
        } 

		
	}
	new Depositar();
?>