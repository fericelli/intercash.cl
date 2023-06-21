<?php
	error_reporting(E_ALL);
    Class ActualizarTasas{
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
                $retorno = "";
				$json = json_decode($_POST["datos"],TRUE);
				

                $usdorigen =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$json["monedaenvio"]["moneda"]))->{'data'};
                $usdtorigen = $json["monedaenvio"]["tasausdt"];
                 
                $devaluacion = number_format(((($usdtorigen-$usdorigen)*100)/$usdorigen)*pow("10","-1"),3,".","");
                //echo "UPDATE devaluacion SET pocentaje=".$devaluacion." WHERE moneda='".$json["monedaenvio"]["moneda"]."'";exit;
                $this->Conexion->Consultar("UPDATE devaluacion SET porcentaje=".$devaluacion." WHERE moneda='".$json["monedaenvio"]["moneda"]."'");
                for($i=0;$i<count($json["monedasdestino"]);$i++){
                    $usddestino =  json_decode(file_get_contents("https://localbitcoins.com/api/equation/USD_in_".$json["monedasdestino"][$i]["moneda"]))->{'data'};
                    $usdtdestino = $json["monedasdestino"][$i]["tasausdt"];
                    $devaluaciondestino = 0;
                    if($usdtdestino-$usddestino>0){
                        $devaluaciondestino = number_format(((($usdtdestino-$usddestino)*100)/$usddestino)*pow("10","-1"),3,".","");
                    }
                    $consulta = $this->Conexion->Consultar("SELECT * FROM  devaluacion WHERE moneda='".$json["monedasdestino"][$i]["moneda"]."'");
                    if($this->Conexion->Recorrido($consulta)){
                        $this->Conexion->Consultar("UPDATE devaluacion SET porcentaje='".$devaluaciondestino."' WHERE moneda='".$json["monedasdestino"][$i]["moneda"]."'");
                    }else{
                        $this->Conexion->Consultar("INSERT INTO devaluacion (moneda,porcentaje) VALUES ('".$json["monedasdestino"][$i]["moneda"]."','".$devaluaciondestino."')");
                    }
                    //echo "UPDATE tasas SET tasaporcentaje=".$json["monedasdestino"][$i]["ganancia"].",decimalestasa=".$json["monedasdestino"][$i]["decimalestasa"].",tasa=".$json["monedasdestino"][$i]["tasa"]." WHERE monedacompra='".$json["monedaenvio"]["moneda"]."' AND monedaventa='".$json["monedasdestino"][$i]["moneda"]."'\n";
                    $this->Conexion->Consultar("UPDATE tasas SET tasasporcentaje=".$json["monedasdestino"][$i]["ganancia"].",decimalestasa=".$json["monedasdestino"][$i]["decimalestasa"].",tasa=".$json["monedasdestino"][$i]["tasa"]." WHERE monedacompra='".$json["monedaenvio"]["moneda"]."' AND monedaventa='".$json["monedasdestino"][$i]["moneda"]."'");
                    //$retorno .= '"'.number_format(($json["monedasdestino"][$i]["tasausdt"]/$usdtorigen)/floatval("1.".str_replace(".","",$json["monedasdestino"][$i]["ganancia"])),$json["monedasdestino"][$i]["decimalestasa"],".","").'",';
                }
                //echo $json["monedasdestino"][0]["tasausdt"];
                return '"Tasas actualizadas","correcto"';
				/*if(strlen($retorno)>1){
					return substr($retorno,0,strlen($retorno)-1)."";
				}else{
					return "";
				}*/

            }catch(Exception $e){
                    return '"Error","error"';
            }
        }	
	}
	new ActualizarTasas();
?>