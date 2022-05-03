<?php
	Class Solicitudes{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion("u956446715_intercash");
			echo $this->retorno();
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
            
             $headers = "From: mi@cuentadeemail.com" . "\r\n" . "CC: destinatarioencopia@email.com";
            $subject = 'the subject';
            $message = 'Solicistaste cambiar '.$_POST["cantidadrecibir"].' '.$_POST["monedaorigen"].' 
            por '.$_POST["cantidadrecibir"].' '.$_POST["monedadestino"].'
            Informacion de pago
            '.$_POST["cuenta"]; 
            $headers = "From: solicitud@intercash.cl";
            echo mail($correo, 'Solicitud de Intercambio', $message,$headers);
        } 
	}
	new Solicitudes();
?>