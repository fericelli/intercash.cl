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
				
				$sql = "";
                $retorno = "[";
				if(isset($_POST["moneda"])){
					$sql = "SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE iso2='".$_POST["pais"]."' AND iso_moneda='".$_POST["moneda"]."'";
				}else{
					$sql = "SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE receptor IS NOT NULL";
				}
				
				//return "SELECT DISTINCT(monedacompra),paises.*,devaluacion.porcentaje as porcentajedeva FROM tasas LEFT JOIN paises ON iso_moneda=monedacompra LEFT JOIN davaluacion ON moneda=monedacompra";
				$consulta = $this->Conexion->Consultar($sql);
                while($tasa = $this->Conexion->Recorrido($consulta)){
					$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$tasa["iso_moneda"]))->{'data'};
					$usdtorigen = number_format($usdorigen*floatval("1.".str_replace(".","",$tasa["porcentajedeva"])),$tasa["decimalesmoneda"],".","");
					
					$retorno .= '{"iso_pais":"'.$tasa["iso2"].'","moneda":"'.$tasa["iso_moneda"].'","nombre":"'.$tasa["nombre"].'","usd":"'.$usdorigen.'","usdt":"'.$usdtorigen.'","devaluacion":"'.$tasa["porcentajedeva"].'","decimalesmoneda":"'.$tasa["decimalesmoneda"].'"},';
                
				}
				if(strlen($retorno)>1){
					$retorno = substr($retorno,0,strlen($retorno)-1)."],[";
				}else{
					
					$retorno = "],[";
				}
				if(isset($_POST["moneda"])){
					$sql = "SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE iso2='".$_POST["pais"]."' AND iso_moneda='".$_POST["moneda"]."'";
				}else{
					$sql = "SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE receptor IS NOT NULL";
				}
				//$sql = "SELECT paises.*,devaluacion.porcentaje as porcentajedeva FROM paises LEFT JOIN devaluacion ON moneda=iso_moneda WHERE receptor IS NOT NULL";
				$consulta = $this->Conexion->Consultar($sql);
                if($tasa = $this->Conexion->Recorrido($consulta)){
					$usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$tasa["iso_moneda"]))->{'data'};
					
					$devaluacionorigen = 0;
					$consultardevaluacion = $this->Conexion->Consultar("SELECT porcentaje FROM devaluacion WHERE moneda='".$tasa["iso_moneda"]."'");
					if($devaluacion = $this->Conexion->Recorrido($consultardevaluacion)){
						$devaluacionorigen = $devaluacion[0];
					}
					$usdtorigen = number_format($usdorigen*floatval("1.".str_replace(".","",$devaluacionorigen)),$tasa["decimalesmoneda"],".","");
					//return "SELECT * FROM tasas LEFT JOIN pais ON iso2=paisorigen AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'";
					//return "SELECT * FROM tasas LEFT JOIN paises ON iso2=paisorigen AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'";
					$consultar = $this->Conexion->Consultar("SELECT * FROM tasas LEFT JOIN paises ON iso2=paisdestino AND iso_moneda=monedaventa LEFT JOIN devaluacion ON devaluacion.moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'");
                    $cantidad = 0;
					while($tasas = $this->Conexion->Recorrido($consultar)){
                        $cantidad ++;
						$usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$tasas["monedaventa"]))->{'data'};
						//echo floatval("1.".str_replace(".","",strval($tasas["porcentaje"])))."\n";
						//return $tasas["porcentaje"];
						$usdtdestino = number_format($usddestino*floatval("1.".str_replace(".","",strval($tasas["porcentaje"]))),$tasas["decimalesmoneda"],".","");
			            $tasasugerida = number_format((floatval($usdtdestino)/floatval($usdtorigen))/floatval("1.".str_replace(".","",$tasas["tasasporcentaje"])),$tasas["decimalestasa"],".","");

						//number_format($número, 2, ',', ' ');
                        //echo $usddestino."  ";//* floatval("1.0".$tasas["porcentaje"])."   ";
                        $retorno .= '{"iso_pais":"'.$tasas["paisdestino"].'","moneda":"'.$tasas["monedaventa"].'","decimales":"'.$tasas["decimalesmoneda"].'","tasasugerida":"'.$tasasugerida.'","usd":"'.$usddestino.'","usdt":"'.$usdtdestino.'","tasa":"'.$tasas["tasa"].'","tasasporcentaje":"'.$tasas["tasasporcentaje"].'","decimalestasa":"'.$tasas["decimalestasa"].'"},';
                    
					}
                
                
                }
				if($cantidad>0){
					return substr($retorno,0,strlen($retorno)-1)."]";
				}else{
					return  $retorno ."]";
				}

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new Datos();
?>