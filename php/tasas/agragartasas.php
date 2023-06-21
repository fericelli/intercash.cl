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
                    
                    //return "SELECT * FROM paises LEFT JOIN tasas ON monedacompra=iso_moneda WHERE iso_moneda='".$_POST["moneda"]."'";
                    $consultar = $this->Conexion->Consultar("SELECT monedaventa FROM paises LEFT JOIN tasas ON monedacompra=iso_moneda WHERE iso_moneda='".$_POST["moneda"]."'");
                    //echo "SELECT monedaventa FROM paises LEFT JOIN tasas ON monedacompra=iso_moneda WHERE iso_moneda='".$_POST["moneda"]."'";
                    $retorno = "";
                    $ventas = [];
                    ///var_dump($this->Conexion->Recorrido($consultar));exit;
                    while($monedaventa = $this->Conexion->Recorrido($consultar)){
                        array_push($ventas,"'".$monedaventa[0]."'");
                        //$retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';  
                    }
                    //return "SELECT * FROM paises WHERE iso_moneda IN NOT (".implode(",",$ventas).")";
                    $consulta = $this->Conexion->Consultar("SELECT * FROM paises WHERE iso_moneda NOT IN (".implode(",",$ventas).")");
                    while($datos = $this->Conexion->Recorrido($consulta)){
                        $retorno .= '{"iso_pais":"'.$datos[0].'","moneda":"'.$datos[3].'","decimales":"'.$datos[4].'","nombre":"'.$datos[2].'"},';
                    }
                    return $retorno = substr($retorno,0,strlen($retorno)-1);
                }else if(isset($_POST["datos"])){
                    $json = json_decode($_POST["datos"],TRUE);
                    //var_dump($json);
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