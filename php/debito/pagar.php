<?php
	error_reporting(E_ALL);
    Class Pagar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // return var_dump($_GET);
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
						$tasa = number_format($_GET["gastopais"]/$_GET["cantidadgasto"],$_GET["decimalgasto"],".","");
                        if($tasa==1){
							$tasa = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$_GET["monedagasto"]."'"))[0];
							$tasa = number_format($tasa,$_GET["decimalgasto"],".","");
							$cantidad = number_format($_GET["gastopais"]/$tasa,2,".","");

							$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('USDT','".$cantidad."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuariocobro"]."','".$_GET["usuario"]."','".$_GET["momento"]."','".$tasa."','".$_GET["monedapago"]."','".$_GET["pais"]."','".$_GET["gastopais"]."','pagos')");
							
						}else{
							$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('".$_GET["monedagasto"]."','".$_GET["cantidadgasto"]."','venta','".date("Y-m-d H:i:s")."','".$_GET["usuariocobro"]."','".$_GET["usuario"]."','".$_GET["momento"]."','".$tasa."','".$_GET["monedapago"]."','".$_GET["pais"]."','".$_GET["gastopais"]."','pagos')");
                        }
						
						
						
                        $carpeta = "./../../imagenes/debito/pagos/".str_replace(' ','',strtolower($_GET["usuariocobro"]));
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        $carpeta = "./../../imagenes/debito/pagos/".str_replace(' ','',strtolower($_GET["usuariocobro"]))."/".date("Y-m-d");
                        if(!file_exists($carpeta)){
                            mkdir($carpeta,0777, true);
                        }
                        
                        
                        $total_imagenes = count(glob($carpeta.'/{*.jpg,*.gif,*.png,*.jpeg}',GLOB_BRACE));
                        $total_imagenes ++;
                        $imagen = $carpeta."/capture".$total_imagenes.$ext;
                        $directorio = "imagenes/debito/pagos/".str_replace(' ','',strtolower($_GET["usuariocobro"]))."/".date("Y-m-d")."/capture".$total_imagenes.$ext;
                        move_uploaded_file($NombreTmpArchivo,$imagen);
                        
                        $this->Conexion->Consultar("INSERT INTO screenshot (directorio,cantidad,tipo,nombre,registro,usuario) VALUES ('".$directorio."','".$_GET["gastopais"]."','pagos','capture".$total_imagenes.$ext."','".$_GET["momento"]."','".$_GET["usuariocobro"]."')");
                        
						if($_GET["pendiente"]<=$_GET["gastopais"]){
							$this->Conexion->Consultar("UPDATE pagos SET estado='1' WHERE usuario='".$_GET["usuariocobro"]."' AND momento='".$_GET["momento"]."'");
							$consultar = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE registro='".$_GET["momento"]."' AND usuario='".$_GET["usuariocobro"]."'");
							
						}
						return '"Pago registrado","finalizar"';

                        
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