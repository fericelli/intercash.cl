<?php
	error_reporting(E_ALL);
    Class Confirmar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
				
                $this->Conexion->Consultar("UPDATE depositos SET confirmado=1 WHERE usuariocuenta='".$_POST["usuariocuenta"]."' AND momento='".$_POST["registro"]."'");
				$consultar = $this->Conexion->Consultar("SELECT SUM(cantidad) FROM depositos WHERE depositos.confirmado IS NOT NULL");
                $consultarsolicitudcantidad = $this->Conexion->Consultar("SELECT SUM(cantidadaenviar) FROM solicitudes WHERE estado IS NULL AND usuario='".$_POST["usuario"]."'");
                $consultarsolicitudcantidad2 = $this->Conexion->Consultar("SELECT SUM(cantidadaenviar) FROM solicitudes WHERE estado IS NOT NULL AND usuario='".$_POST["usuario"]."'");
				$totalsolicitudespendientes = 0;
				$totalsolicitudespagadas = 0;
				$totaldeposito = 0;
				if($total = $this->Conexion->Recorrido($consultar)){
					if(!is_null($total[0])){
						$totaldeposito = $total[0];	
					 }
				}
				if($solicitudescantidadpendientes = $this->Conexion->Recorrido($consultarsolicitudcantidad) AND $solicitudescantidadpagados = $this->Conexion->Recorrido($consultarsolicitudcantidad2)){
					 $totalsolicitudespendientes = $solicitudescantidadpendientes[0];
					 if(!is_null($solicitudescantidadpagados[0])){
						$totalsolicitudespagadas = $solicitudescantidadpagados[0];
					 }
					
					
				}
				$totaldeposito -= $totalsolicitudespagadas;
				$consultarsolicitud = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE estado IS NULL AND usuario='".$_POST["usuario"]."'");
				while($solicitudes = $this->Conexion->Recorrido($consultarsolicitud)){
					
					if($solicitudes[2]<=$totaldeposito){
						$this->Conexion->Consultar("UPDATE solicitudes SET estado='procesando' WHERE momento='".$solicitudes["momento"]."'");
						$totaldeposito -= $solicitudes[2];
					}
				}
				return '"correcto","correcto"';
				

				
            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Confirmar();
?>