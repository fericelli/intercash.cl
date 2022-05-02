<?php
	Class Solicitudes{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion("intercash.cl");
			echo "{".$this->retorno()."}";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios WHERE usuario='".$_POST["usuario"]."' OR correo='".$_POST["usuario"]."'");
            $retorno = "";
            $correo = "";
            if($usaurio = $this->Conexion->Recorrido($consultar)){
                if($usaurio[3]){
                    $correo = $usaurio[1];
                }else{
                    $retorno .= '"mensausuario":"Usuario no verificado",';
                }
            }else{
                $validad = explode("@",$_POST["usuario"]);
                if(count($validad)==2){
                    if(trim($validad[1]) == "gmail.com" ){
                        $correo = $_POST["usuario"];
                    }else{
                        $retorno .= '"mensausuario":"Debe ser un correo Gmal",';
                    }
                }else{
                    $retorno .= '"mensausuario":"Debe ser un correo Gmal",';
                }
            }
            $to = "fericelli.garcia@gmail.com";
            $subject = "Asunto del email";
            $message = "Este es mi primer envío de email con PHP";
            
            mail($to, $subject, $message);

            return substr($retorno,0,strlen($retorno)-1);


        } 
	}
	new Solicitudes();
?>