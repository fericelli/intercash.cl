<?php
	Class Agregarbancos{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			 $this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
             $json = json_decode($_POST["datos"],TRUE);
             $cantidad = 1;
             foreach($json as $datos){
                /*$codigo="";
                if(strlen($cantidad)==1){
                    $codigo = "00".strval($cantidad);
                }else if(strlen($cantidad>1)){
                    $codigo = "0".strval($cantidad);
                }*/
                if(strpos($datos, "(aún no redactado)")){
                    $nombre = trim(substr($datos,0,strpos($datos, "(aún no redactado)")));
                    $consultar = $this->Conexion->Consultar("SELECT * FROM bancos WHERE nombre LIKE '".$nombre."%' AND pais='".$_POST["pais"]."'");
                    if($this->Conexion->Recorrido($consultar)){
                        $sql = "UPDATE bancos SET nombre='".$nombre."' WHERE nombre LIKE '".$nombre."%' AND pais='".$_POST["pais"]."'";
                        //echo $sql = "INSERT INTO bancos (codigo,nombre,pais) VALUES ('".$codigo."','".trim($datos)."','".$_POST["pais"]."')\n";
                        echo $this->Conexion->Consultar($sql);
                    }
                }
                /*
               
                $cantidad ++;*/
                
             }
        }
	}
	new Agregarbancos();
?>