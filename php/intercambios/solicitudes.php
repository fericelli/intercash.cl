<?php
	Class Solicitudes{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
           // return var_dump($_POST);
			$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios WHERE usuario='".$_POST["usuario"]."' OR correo='".$_POST["usuario"]."'");
            $retorno = "{";
            $correo = "";
            if($usaurio = $this->Conexion->Recorrido($consultar)){
                if($usaurio[3]){
                    $correo = $usaurio[1];
                }/*else{
                    $retorno .= '"mensausuario":"Usuario no verificado",';
                }*/
            }else{
                $validad = explode("@",$_POST["usuario"]);
                if(count($validad)==2){
                    if(trim($validad[1]) == "gmail.com" ){
                        $correo = $_POST["usuario"];
                    }else{
                        $retorno .= '"mensausuario":["Debe ser un correo Gmal","error"],';
                    }
                }else{
                    $retorno .= '"mensausuario":["Debe ser un correo Gmal","error"],';
                }
            }
            if(strlen($retorno)>0){
                $message = '<p style="left:0"><h4>Solicistaste cambiar</h4> '.$_POST["cantidadrecibir"].' '.$_POST["monedaorigen"].'</p>
                <p style="left:0"><h4>Por</h4> '.$_POST["cantidadenviar"].' '.$_POST["monedadestino"].'</p>
                <p style="left:0"><h4>Informacion de pago</h4></p>
                <p style="left:0">'.$_POST["cuenta"]."</p>"; 
                $headers = "From: solicitud@intercash.cl" . "\r\n" ."Content-type:text/html;chrarset=UTF-8";
                $correo =  mail($correo, 'Solicitud de Intercambio', $message,$headers);
                //if($correo){
                    $solicitar = $this->Conexion->Consultar("INSERT INTO solicitudes(monedadestino,monedaorigen,cantidadaenviar,cantidadarecibir,cuenta,banco,tipocuenta,nombres,identificacion,usuario,momento) VALUES ('".$_POST["monedadestino"]."','".$_POST["monedaorigen"]."','".$_POST["cantidadenviar"]."','".$_POST["cantidadrecibir"]."','".$_POST["cuenta"]."','".$_POST["banco"]."','".$_POST["tipodecuenta"]."','".$_POST["nombres"]."','".$_POST["identificacion"]."','".$_POST["usuario"]."','".date("Y-m-d H:i:s")."')");
            // }
                $retorno .= '"mensausuario":["Solicitud enviada","correcto"]';
            }
            

           return $retorno."}";
        } 
	}
	new Solicitudes();
?>