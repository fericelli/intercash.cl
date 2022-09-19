<?php
	Class Bancos{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo $this->retorno();
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $retorno = '{"bancos":[';
            $consultar = $this->Conexion->Consultar("SELECT * FROM bancos WHERE pais='".$_POST["pais"]."'");
            $contador = 0;
            while($banco = $this->Conexion->Recorrido($consultar)){
                $retorno .= '{"codigo":"'.trim($banco[0]).'","nombre":"'.trim($banco[1]).'"},';
                $contador ++;
            }
            if($contador==0){
                $retorno .= "{}],";
            }else{
                $retorno = substr($retorno,0,strlen($retorno)-1)."],";
            }
            $retorno .= '"tiposdecuenta":[';
            $consultar1 = $this->Conexion->Consultar("SELECT * FROM tiposdecuentas WHERE pais='".$_POST["pais"]."'");
            $contador = 0;
            while($tiposdecuentas = $this->Conexion->Recorrido($consultar1)){
                $retorno .= '"'.$tiposdecuentas[1].'",';
                $contador ++;
            }
            if($contador==0){
                $retorno .= "]";
            }else{
                $retorno = substr($retorno,0,strlen($retorno)-1)."]";
            }
            return $retorno."}";
        } 
	}
	new Bancos();
?>