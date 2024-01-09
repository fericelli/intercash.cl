<?php
	error_reporting(E_ALL);
    Class Gastar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                $gastos = date("Y-m-d H:i:s");
                $tasa = number_format($_GET["gastopais"]/$_GET["cantidadgasto"],2,".","");
                $this->Conexion->Consultar("INSERT INTO gastos (momento,moneda,cantidad,descripcion,usuario,pais) VALUES ('".$gastos."','".$_GET["monedapago"]."',".$_GET["gastopais"].",'".$_GET["descripcion"]."','".$_GET["usuario"]."','".$_GET["pais"]."')");
                if($tasa==1){
                    $tasa = number_format($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$_GET["monedagasto"]."'"))[0],2,".","");
                    $cantidad = number_format($_GET["gastopais"]/$tasa,2,".","");
                    $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('".$_GET["monedagasto"]."','".$cantidad."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["usuario"]."','".$gastos."','".$tasa."','".$_GET["monedapago"]."','".$_GET["pais"]."','".$_GET["gastopais"]."','gastos')");
                }else{
                    $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('".$_GET["monedagasto"]."','".$_GET["cantidadgasto"]."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuario"]."','".$_GET["usuario"]."','".$gastos."','".$tasa."','".$_GET["monedapago"]."','".$_GET["pais"]."','".$_GET["gastopais"]."','gastos')");
                }
                
            }catch(Exception $e){
                return '"Error","error"';
            }
            if(count($_FILES)>0){
                $formatos = array(".jpg",".png",".jepg",".jpeg");
                $momento = date("Y-m-d H:i:s");
                $ErrorArchivo = $_FILES['imagen']['error'];
                $NombreArchivo = $_FILES['imagen']['name'];
                $NombreTmpArchivo = $_FILES['imagen']['tmp_name'];
                $ext = substr($_FILES['imagen']['name'],strrpos($_FILES['imagen']['name'], '.'));
                if(in_array($ext, $formatos)){
                    $carpeta = "./../../imagenes/debito/gastos/".str_replace(' ','',strtolower($_GET["usuario"]));
                    if(!file_exists($carpeta)){
                        mkdir($carpeta,0777, true);
                    }
                    $carpeta = "./../../imagenes/debito/gastos/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d");
                    if(!file_exists($carpeta)){
                        mkdir($carpeta,0777, true);
                    }
                    $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                    $total_imagenes ++;
                    $imagen = $carpeta."/capture".$total_imagenes.$ext;
                    $directorio = "imagenes/debito/gastos/".str_replace(' ','',strtolower($_GET["usuario"]))."/".date("Y-m-d")."/capture".$total_imagenes.$ext;
                    move_uploaded_file($NombreTmpArchivo,$imagen);
                    $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$_GET["gastopais"]."','gastos','capture".$total_imagenes.$ext."','".$gastos."','".$_GET["usuario"]."')");
                }
            }
            
            return '"Gasto registrado","finalizar"';

        }	
	}
	new Gastar();
?>