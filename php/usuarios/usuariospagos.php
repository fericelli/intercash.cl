<?php
    Class Usuariospagos{
        private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
        private function retorno(){
            $retorno = "";
           	$deuda = 0;
			$consultar = $this->Conexion->Consultar("SELECT * FROM pagos LEFT JOIN paises ON paises.iso2=pagos.pais LEFT JOIN usuarios ON usuarios.usuario=pagos.usuario WHERE pagos.estado IS NULL");
			while($pagos = $this->Conexion->Recorrido($consultar)){
				$consultar1 = $this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE registro='".$pagos["momento"]."' AND usuario='".$pagos["usuario"]."' AND tipo='pagos'");
				$monto = $this->Conexion->Recorrido($consultar1)[0];
				$pagado = 0;
				if($monto!=NULL){
					$pagado = $monto;
				}
				$pendiente = $pagos["cantidad"]-$pagado;
				$retorno .= '{"momento":"'.$pagos["momento"].'","cantidad":"'.$pagos["cantidad"].'","pendiente":"'.$pendiente.'","usuario":"'.$pagos["usuario"].'","cuenta":["'.$pagos["cuenta"].'","'.$pagos["banco"].'","'.$pagos["tipocuenta"].'","'.$pagos["nombres"].'","'.$pagos["identificacion"].'"],"moneda":"'.$pagos["moneda"].'","pais":"'.$pagos[8].'","decimales":"'.$pagos["decimalesmoneda"].'","nombremoneda":"'.$pagos["nombremoneda"].'","nombrepais":"'.$pagos[15].'","nombreusuario":"'.$pagos[21].'"},';
			}
            return substr($retorno,0,strlen($retorno)-1);
            
        }
    }
	new Usuariospagos();
?>