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
					$sql = "SELECT * FROM paises WHERE iso2='".$_POST["pais"]."' AND iso_moneda='".$_POST["moneda"]."'";
					
				}else{
					$sql = "SELECT * FROM paises WHERE receptor IS NOT NULL";
				}
				$consulta = $this->Conexion->Consultar($sql);
				
				
                while($tasa = $this->Conexion->Recorrido($consulta)){
					$usdtorigen = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anunciocompra) FROM tasas WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'"))[0];
					
					$retorno .= '{"iso_pais":"'.$tasa["iso2"].'","moneda":"'.$tasa["iso_moneda"].'","nombre":"'.$tasa["nombre"].'","usd":"'.$usdtorigen.'","usdt":"'.$usdtorigen.'","devaluacion":"'.$tasa["porcentajedeva"].'","decimalesmoneda":"'.$tasa["decimalesmoneda"].'"},';
                
				}
				
				if(strlen($retorno)>1){
					$retorno = substr($retorno,0,strlen($retorno)-1)."],[";
				}else{
					
					$retorno = "],[";
				}
				
				
				$consulta = $this->Conexion->Consultar($sql);
                if($tasa = $this->Conexion->Recorrido($consulta)){
					
					$consultar = $this->Conexion->Consultar("SELECT * FROM tasas LEFT JOIN paises ON iso2=paisdestino AND iso_moneda=monedaventa  WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'");
                    $cantidad = 0;
					$usdorigen = number_format($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anunciocompra) FROM tasas WHERE monedacompra='".$tasa["iso_moneda"]."' AND paisorigen='".$tasa["iso2"]."'"))[0],$tasa["decimalesmoneda"],".","");
					
					while($tasas = $this->Conexion->Recorrido($consultar)){
                        $cantidad ++;
						$usddestino =  number_format($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$tasas["monedaventa"]."' AND paisdestino='".$tasas["paisdestino"]."'"))[0],$tasas["decimalesmoneda"],".","");
						$tasasugerida = number_format((floatval($usddestino)/floatval($usdorigen))/floatval("1.".str_replace(".","",$tasas["tasasporcentaje"])),$tasas["decimalestasa"],".","");
						$retorno .= '{"iso_pais":"'.$tasas["paisdestino"].'","moneda":"'.$tasas["monedaventa"].'","decimales":"'.$tasas["decimalesmoneda"].'","tasasugerida":"'.$tasasugerida.'","usd":"'.$usddestino.'","usdt":"'.$usddestino.'","tasa":"'.$tasas["tasa"].'","tasasporcentaje":"'.$tasas["tasasporcentaje"].'","decimalestasa":"'.$tasas["decimalestasa"].'"},';
                    
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