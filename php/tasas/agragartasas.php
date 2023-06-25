<?php
	Class AgregarTasas{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                if(isset($_POST["pais"])){
                    $consultar = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
                    $retorno = "";
                    while($datos = $this->Conexion->Recorrido($consultar)){
                        $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
                    }
                    
                    return $retorno = substr($retorno,0,strlen($retorno)-1);
                }else if(isset($_POST["paisorigen"])){
                    
                    $retorno = "";
                    $ventas = [];
                    $consulta = $this->Conexion->Consultar("SELECT * FROM paises WHERE iso2 NOT IN ('".$_POST["paisorigen"]."')");
                    while($datos = $this->Conexion->Recorrido($consulta)){
                        $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
                    }
                    return $retorno = substr($retorno,0,strlen($retorno)-1);
                }else if(isset($_POST["datos"])){
                    $json = json_decode($_POST["datos"],TRUE);
                    $this->Conexion->Consultar("INSERT INTO tasas(monedaventa,monedacompra,tasasporcentaje,decimalestasa,tasa,paisorigen,paisdestino) VALUES ('".$json["monedadestino"]."','".$json["monedaorigen"]."','".$json["porcentaje"]."','".$json["decimalestasa"]."','".$json["tasa"]."','".$json["paisorigen"]."','".$json["paisdestino"]."') ");
                    
                    return '"Tasa agregada","correcto"';
                }
            }catch(Exception $e){
                return '"Error","error"';
            }
			
		} 
	}
	new AgregarTasas();
?>