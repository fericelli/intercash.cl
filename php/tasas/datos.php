<?php
	error_reporting(E_ALL);
    Class Datos{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            try{
                $retorno = "";
				$consulta = $this->Conexion->Consultar("SELECT DISTINCT(monedacompra),paises.* FROM tasas LEFT JOIN paises ON iso_moneda=monedacompra");
                if($tasa = $this->Conexion->Recorrido($consulta)){
                
                    $retorno .= '{"iso_pais":"'.$tasa[1].'","moneda":"'.$tasa[0].'","decimales":"'.$tasa[5].'","nombre":"'.$tasa[3].'"},';
                    return "SELECT * FROM tasas LEFT JOIN devaluacion ON devaluacion.moneda=tasas.monedacompra WHERE monedacompra='".$tasa[0]."'";
                    $consultar = $this->Conexion->Consultar("SELECT * FROM tasas LEFT JOIN devaluacion ON devaluacion.moneda=tasas.monedacompra WHERE monedacompra='".$tasa[0]."'");
                    while($tasas = $this->Conexion->Recorrido($consultar)){
                        $usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$tasas["monedaventa"]))->{'data'};
			            $usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$_POST["monedacompra"]))->{'data'};
			
                        var_dump($usddestino);
                        $tasas["monedacompra"];
                        
                        return;
                        $retorno .= '{"iso_pais":"'.$tasa[1].'","moneda":"'.$tasa[0].'","decimales":"'.$tasa[5].'","nombre":"'.$tasa[3].'"},';
                    }
                
                
                }
                
				if(strlen($retorno)>1){
					return substr($retorno,0,strlen($retorno)-1)."";
				}else{
					return "";
				}

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Datos();
?>