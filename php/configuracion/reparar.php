<?php
	Class Reparar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			 $this->Conexion = new Conexion();
			 echo $this->finalizarsolicitudes();
			// echo $this->operaciones();
			//echo $this->intercambios();
			$this->Conexion->CerrarConexion();
		}
		private function operaciones(){
            
			try{
				$cantidad = 0;
				$sql1 =  "SELECT *,SUM(montointercambio) AS suma FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.solicitud LEFT JOIN tasas ON tasas.paisorigen=solicitudes.paisorigen AND tasas.paisdestino=solicitudes.paisdestino AND tasas.monedacompra=solicitudes.monedaorigen AND tasas.monedaventa=solicitudes.monedadestino LEFT JOIN paises ON paises.iso2=operaciones.paisintercambio WHERE operaciones.montointercambio<>solicitudes.cantidadaenviar  GROUP BY solicitud ORDER BY operaciones.montointercambio ASC";
				$consultar = $this->Conexion->Consultar($sql1);
				echo "operaciones<br>";
				while($datos = $this->Conexion->Recorrido($consultar)){
					$tasa = number_format($datos["cantidadarecibir"]/$datos["cantidadaenviar"],$datos["decimalestasa"],".","");
										
					if($datos["suma"]<>$datos["cantidadaenviar"]){
								
						$consultar2 = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE usuario='".$datos[21]."' AND solicitud='".$datos[6]."' GROUP BY operaciones.momento");
						$cantidadregistro = $this->Conexion->NFilas($consultar2);
						/*if($cantidadregistro>1){
							while($operacion = $this->Conexion->Recorrido($consultar2)){
								$consultar3 = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE momento='".$operacion["momento"]."' AND usuario='".$datos[21]."' AND solicitud='".$datos[6]."'");
								$cantida = $this->Conexion->NFilas($consultar3);
								if($cantida>1){

									$this->Conexion->Consultar("DELETE FROM operaciones WHERE momento='".$operacion["momento"]."' LIMIT ".($cantida-1)."");
									//$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,solicitud,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('".$operacion["moneda"]."','".$operacion["monto"]."','".$operacion["operacion"]."','".$operacion["momento"]."','".$operacion["usuario"]."','".$operacion["operador"]."','".$operacion["solicitud"]."','".$operacion["tasa"]."','".$operacion["monedaintercambio"]."','".$operacion["paisintercambio"]."','".$operacion["montointercambio"]."','envio')");
									echo $operacion["momento"]."<br>";
								}
							}
						}*/
						
						if($cantidadregistro==1){
							if($operaciones = $this->Conexion->Recorrido($consultar2)){
								$this->Conexion->Consultar("UPDATE operaciones SET montointercambio=".$datos["cantidadaenviar"]." WHERE usuario='".$datos[21]."' AND solicitud='".$datos[6]."'")."<br>";
											
									//echo $datos["cantidadaenviar"]."-------------------".$cantidadtotal."---------------------".$cantidadregistro."<br>";
								
							}
						}else{
							$total = 0;
							$totalintercambio = 0;
							$not = [];
							while($operaciones = $this->Conexion->Recorrido($consultar2)){

								$total ++;
								if(count($not)>0){
									$and =  "AND momento NOT IN (".implode(",",$not).")";
								}else{
									$and =  "";

								}
								if($operaciones["tasa"]==1){
									$sql = "SELECT *,ABS(operaciones.monto-screenshot.cantidad) AS diferencia FROM `operaciones` LEFT JOIN screenshot ON operaciones.usuario=screenshot.usuario AND (screenshot.registro=operaciones.solicitud OR screenshot.solicitud=operaciones.solicitud) LEFT JOIN paises ON paises.iso2=operaciones.paisintercambio WHERE operaciones.momento='".$operaciones[3]."' AND operaciones.solicitud='".$datos[6]."' AND operaciones.usuario='".$datos[21]."' ".$and." ORDER BY diferencia ASC LIMIT 1";
								}else{
									$sql = "SELECT *,ABS((operaciones.monto*operaciones.tasa)-screenshot.cantidad) AS diferencia FROM `operaciones` LEFT JOIN screenshot ON operaciones.usuario=screenshot.usuario AND (screenshot.registro=operaciones.solicitud OR screenshot.solicitud=operaciones.solicitud)  LEFT JOIN paises ON paises.iso2=operaciones.paisintercambio WHERE operaciones.momento='".$operaciones[3]."' AND operaciones.solicitud='".$datos[6]."' AND operaciones.usuario='".$datos[21]."' ".$and." ORDER BY diferencia ASC LIMIT 1";
								}
								//echo $sql."<br>";
								$consultar3 = $this->Conexion->Consultar($sql);
								if($operacion5 = $this->Conexion->Recorrido($consultar3)){

									$montointercambio = 0;
									if($operaciones["tasa"]==1){
										$montointercambio = $operacion5["monto"]/$tasa; 
									}else{
										$montointercambio = ($operacion5["monto"]*$operaciones["tasa"])/$tasa;
									}
									$montointercambio = number_format($montointercambio,$datos["decimalesmoneda"],".","");
									$modificar = $montointercambio;
									array_push($not,"'".$operacion5[3]."'");
									$totalintercambio += $montointercambio;
									if($total==$cantidadregistro){
										$modificar =  number_format($montointercambio+($datos["cantidadaenviar"]-$totalintercambio),$datos["decimalesmoneda"],".","") ."<br>";
										//$totalintercambio += $datos["cantidadaenviar"]-$totalintercambio;
									}
									$this->Conexion->Consultar("UPDATE operaciones SET montointercambio='".$modificar."' WHERE momento='".$operacion5["momento"]."' AND usuario='".$datos[21]."' AND solicitud='".$datos[6]."'");
								}
								
							}
									
						}
						echo $datos["cantidadaenviar"]."-------------------".$cantidadtotal."---------------------".$cantidadregistro."----------------------".$datos[6]."<br>";
									
					} 
					
            	}
			}catch(Exception $e){
				
			}
			
            
           // return  $cantidad;
        }
		private function intercambios(){
			
			try{

				$consultar = $this->Conexion->Consultar("SELECT * FROM intercambios LEFT JOIN solicitudes ON solicitudes.momento=intercambios.solicitud GROUP BY intercambios.solicitud");
				echo "intercambios<br>";
				while($datos = $this->Conexion->Recorrido($consultar)){
					$consultar1 = $this->Conexion->Consultar("SELECT * FROM intercambios WHERE solicitud='".$datos["solicitud"]."'");
					$cantidadregistro = $this->Conexion->NFilas($consultar1);	
					if($datos["cantidadaenviar"]!=$datos["montocompra"]){
						
						if($cantidadregistro==1){
							//echo "UPDATE intercambios SET montocompra='".$datos["cantidadaenviar"]."' WHERE solicitud='".$datos["solicitud"]."'";exit;
							//"UPDATE intercambios SET montocompra='".$datos["cantidadaenviar"]."' WHERE solicitud='".$datos["solicitud"]."'";
							//$datos["cantidadaenviar"]."-------------------".$datos["montocompra"]."-------------".$datos["solicitud"]."<br>";
							$this->Conexion->Consultar("UPDATE intercambios SET montocompra='".$datos["cantidadaenviar"]."' WHERE solicitud='".$datos["solicitud"]."'")."<br>";
						}
						if($cantidadregistro>1){
							$this->Conexion->Consultar("DELETE FROM intercambios WHERE solicitud='".$datos["solicitud"]."'");
							$this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,solicitud) VALUES ('".$datos["montoventa"]."','".$datos["monedaventa"]."','".$datos["montocompra"]."','".$datos["monedacompra"]."','".$datos["intermediario"]."','".$datos["momento"]."','".$datos["solicitud"]."')");
						}
						
						echo $datos["cantidadaenviar"]."-------------------".$datos["montocompra"]."-------------".$cantidadregistro."---------------------".$datos["solicitud"]."<br>";
					
					}else{
						if($cantidadregistro>1){
							$this->Conexion->Consultar("DELETE FROM intercambios WHERE solicitud='".$datos["solicitud"]."'");
							$this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,solicitud) VALUES ('".$datos["cantidadarecibir"]."','".$datos["monedaventa"]."','".$datos["cantidadaenviar"]."','".$datos["monedacompra"]."','".$datos["intermediario"]."','".$datos["momento"]."','".$datos["solicitud"]."')");
						}
					}
				}
			}catch(Exception $e){
				
			}
		}
		private function deudahonorarios(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM usuarios LEFT JOIN paises ON paises.iso2=usuarios.pais WHERE tipodeusuario='sociocomercial'");
			while($usuarios = $this->Conexion->Recorrido($consultar)){
				
				$consultar1 = $this->Conexion->Consultar("SELECT * FROM pagos WHERE usuario='".$usuarios["usuario"]."' ORDER BY momento DESC LIMIT 1");
				 $fecha = $this->Conexion->Recorrido($consultar1)[0];
				$dauda = 0; 
				if($fecha!=NULL){
					echo $usuarios["usuario"]."   ".$usuarios["iso_moneda"]."   ".$fecha."<br>";
					  $sql = "SELECT monedacompra,SUM(montocompra),usuarios.porcentaje,decimalesmoneda FROM intercambios LEFT JOIN usuarios ON usuario=intermediario LEFT JOIN paises ON paises.iso_moneda=intercambios.monedacompra AND paises.receptor IS NOT NULL WHERE intermediario='".$usuarios["usuario"]."' AND solicitud>'".$fecha."' GROUP BY monedacompra";
					
					$consultar2 =  $this->Conexion->Consultar($sql);
					$cantidad = $this->Conexion->NFilas($consultar2)."<br>";
					$pago = 0;
					while($datos = $this->Conexion->Recorrido($consultar2)){
						$pago = $datos[1]*$usuarios["porcentaje"];
						$pago = $pago/100;
						if($datos[0]==$usuarios["iso_moneda"]){
							$dauda += $pago;
						}else{
							$consultar3 = $this->Conexion->Consultar("SELECT AVG(tasa) FROM tasas WHERE monedaventa='".$usuarios["iso_moneda"]."' AND monedacompra='".$datos[0]."'");
							$tasa = $this->Conexion->Recorrido($consultar3)[0];
							$pago = $pago*$tasa;
							$dauda += $pago;
							
							
							//return "SELECT * FROM tasas WHERE monedaventa='".$usuarios["iso_moneda"]."' AND monedacompra='".$datos[0]."'";
						}

						echo $datos[1]."-----".$usuarios["iso_moneda"]."----".$tasa."-------------".$pago."<br>";

						
					}
					
					echo $dauda."<br>";
				}
				
				
			}
		}
		private function actualizarmonedapago(){
			$monedas = "";
			$consultar = $this->Conexion->Consultar("SELECT iso_moneda FROM paises WHERE receptor IS NOT NULL");
			while($datos = $this->Conexion->Recorrido($consultar)){
				$monedas .= $datos[0]." ";
			}
			$this->Conexion->Consultar("UPDATE pagos SET monedascambiadas='".$monedas."'") ;
		}
		private function simplificarregistroa(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes");
			while($solicitudes = $this->Conexion->Recorrido($consultar)){
				$consultar1 = $this->Conexion->Consultar("SELECT * FROM screenshot WHERE solicitud='".$solicitudes["momento"]."'");
				
				//echo "solicitud ".$solicitudes["momento"]." ".$solicitudes["usuario"]."<br>";
				if($screenshot = $this->Conexion->Recorrido($consultar1)){
					echo "screnshoot ".$this->Conexion->Consultar("UPDATE screenshot SET registro='".$solicitudes["momento"]."',tipo='envios' WHERE solicitud='".$solicitudes["momento"]."' ")."<br>";
				}
				$this->Conexion->Consultar("UPDATE operaciones SET tipo='envios' WHERE solicitud='".$solicitudes["momento"]."'");
				/*$consultar2 = $this->Conexion->Consultar("SELECT * FROM intercambios WHERE solicitud='".$solicitudes["momento"]."'");
				if($intercambios = $this->Conexion->Recorrido($consultar2)){
					echo "intercambios ".$this->Conexion->Consultar("UPDATE intercambios SET registro='".$solicitudes["momento"]."' WHERE solicitud='".$solicitudes["momento"]."'")."<br>";

				}*/
				
			}
		}
		private function asignartasaenvio(){
			
			//return "SELECT * FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro LEFT JOIN paises ON paises.iso2=solicitudes.paisdestino AND paises.iso_moneda=operaciones.moneda WHERE tasa='1' AND tipo='envios' ORDER BY operaciones.momento ASC";
			$operaciones = $this->Conexion->Consultar("SELECT * FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro LEFT JOIN paises ON paises.iso2=solicitudes.paisdestino AND paises.iso_moneda=operaciones.moneda WHERE tasa='1' AND tipo='envios' AND iso2 IS NOT NULL ORDER BY operaciones.momento ASC");
			//print_r($this->Conexion->Recorrido($operaciones));exit;
			while($operacion = $this->Conexion->Recorrido($operaciones)){
				
				$montousdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT * FROM operaciones  WHERE moneda='USDT' AND monedaintercambio='".$operacion[0]."' AND paisintercambio='".$operacion["iso2"]."' AND tipo='envios' AND tasa<>1 AND operaciones.momento>'".$operacion[3]."'  ORDER BY operaciones.momento ASC LIMIT 1"));
				$porcentajes = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT * FROM tasas WHERE paisorigen='".$operacion["paisorigen"]."' AND paisdestino='".$operacion["paisdestino"]."' LIMIT 1"));
				//print_r($porcentajes);exit;
				$porcentaje=$porcentajes[4]*10;
				if($montousdt[10]==NULL){
					$tasa = number_format($porcentajes[1],$operacion[30],".","");
					
				}else{
					$tasa = number_format($montousdt[10]/$montousdt[1],$operacion[30],".","");
					$tasa -= ($tasa*$porcentaje)/100;
					if($tasa<1){
						$tasa=1;
					}
				}
				
				
				
				 
				
				$tasausdt = number_format(floatval($tasa),$operacion[30],".","");
				$usdt = number_format(floatval($operacion[1]/$tasausdt),2,".","");
				
				//echo $operacion[3]."  ".$usdt."  ".date("Y-m-d H:i:s",strtotime($operacion[3]."+1 second"))."<br>";

				$this->Conexion->Consultar("UPDATE operaciones SET monto='".$usdt."',moneda='USDT',tasa='".$tasausdt."' WHERE momento='".$operacion[3]."' AND usuario='".$operacion[4]."'");
				$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,operador,tasa,monedaintercambio,paisintercambio,montointercambio) VALUES ('USDT','".$usdt."','compra','".date("Y-m-d H:i:s",strtotime($operacion[3]."+1 second"))."','javier','".$tasausdt."','".$operacion[29]."','".$operacion[26]."','".$operacion[1]."')");
				
			}
		}
		private function reiniciar(){
			$cantidadinvertidaUSDT = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND tipo='envios' AND registro>'2023-12-31 23:59:59'"))[0]);
			$cantidadcompradaUSDT =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='USDT' AND operacion='compra' AND momento>'2023-12-31 23:59:59'"))[0]);
			 $cantidadcompradaUSDTBTC =  floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='USDT' AND operacion='compra' AND momento>'2023-12-31 23:59:59'"))[0]);
			$cantidadinvertidoBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND tipo='envios' AND momento>'2023-12-31 23:59:59'"))[0]);
			 $cantidadcompradaBTC = floatval($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND operacion='compra' AND momento>'2023-12-31 23:59:59'"))[0]);
			$decimalesusdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='USDT'"))[0];
			 $decimalesbtc = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT decimales FROM monedas WHERE iso_moneda='BTC'"))[0];
			
			 $usdtporcomprar = number_format(floatval($cantidadinvertidaUSDT-$cantidadcompradaUSDT),$decimalesusdt,".","");
			 $btcporcomprar = number_format(floatval($cantidadinvertidoBTC-$cantidadcompradaBTC),$decimalesbtc,".","");
			 
			 
			$btc = number_format(floatval($cantidadinvertidoBTC - $cantidadcompradaBTC),$decimalesbtc,".","");
			$usdt = number_format(floatval($cantidadcompradaUSDT - $cantidadinvertidaUSDT-$cantidadcompradaUSDTBTC),$decimalesusdt,".","");
			$tasa = number_format(floatval($usdt/$btc),$decimalesusdt,".","");
			//
			return "     ".$btcporcomprar."  ".$usdt."  ".$tasa;
			//$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,montointercambio) VALUES ('BTC','".$btc."','compra','2023-12-31 23:59:33','javier','javier','".$tasa."','USDT','".$usdt."')");
			
			//if()
			//return $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,montointercambio) VALUES ('BTC','".$btc."','compra','".$fecha."','javier','javier','".$tasa."','USDT','".$usdt."')");
			$fecha = "2023-12-31 23:59:59";
			$monedas = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");

			$cantidad = 0;
			while($moneda=$this->Conexion->Recorrido($monedas)){
				//return "SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$moneda["iso_moneda"]."' AND paisintercambio='".$moneda["iso2"]."' AND moneda='USDT' AND  tipo='envios' AND registro>'".$fecha."'";
				$cantidadmonedainvertida = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$moneda["iso_moneda"]."' AND paisintercambio='".$moneda["iso2"]."' AND  tipo='envios' AND registro>'".$fecha."'"))[0];
				$cantidadcomprada = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(montointercambio) FROM operaciones WHERE monedaintercambio='".$moneda["iso_moneda"]."' AND paisintercambio='".$moneda["iso2"]."' AND operacion='compra' AND momento>'".$fecha."'"))[0];
				echo $moneda["iso_moneda"]."  ".$cantidadmonedainvertida."---".$cantidadcomprada."<br>";
				$porcomprar = number_format(floatval($cantidadmonedainvertida-$cantidadcomprada),$moneda["decimalesmoneda"],".","");
				

				/*if($porcomprar > 0){
					$tasa = number_format($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(tasa) FROM operaciones WHERE monedaintercambio='".$moneda["iso_moneda"]."' AND paisintercambio='".$moneda["iso2"]."' AND moneda='USDT' AND operacion='compra' AND momento>'".$fecha."'"))[0],$moneda["decimalesmoneda"],".","");
					$usdt = number_format(floatval($porcomprar/$tasa),$decimalesusdt,".","");                                               
					sleep(1);
					
					$momento = date("Y-m-d H:i:s",strtotime($fecha."-1 second"));
					echo  $moneda["iso_moneda"]."-------------- ".$porcomprar." ------------".$tasa." ---------------  ".$usdt."----------------- ".$usdtporcomprar."<br>";
					$fecha = $momento ;
					$cantidad += $usdt;
					 //$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,tasa,monedaintercambio,paisintercambio,montointercambio) VALUES ('USDT','".$usdt."','compra','".$momento."','javier','javier','".$tasa."','".$moneda["iso_moneda"]."','".$moneda["iso2"]."','".$porcomprar."')");
				}*/
				
			
			}
			//return $cantidad;*/

			$usdtporcomprar =  number_format(floatval($cantidadinvertidaUSDT-$cantidadcompradaUSDT),$decimalesusdt,".","");
			$btcporcomprar = number_format(floatval($cantidadinvertidoBTC - $cantidadcompradaBTC),$decimalesbtc,".","");
			
		}
		private function repararintercambio(){
			try{
				$consultar = $this->Conexion->Consultar("SELECT * FROM intercambios ORDER BY registro DESC LIMIT 1");
				if($intercambio = $this->Conexion->Recorrido($consultar)){
					$consultar2 = $this->Conexion->Consultar("SELECT * FROM solicitudes WHERE momento>'".$intercambio["registro"]."' AND estado='finalizado'");
					while($solicitudes = $this->Conexion->Recorrido($consultar2)){
						
						$momento = date("Y-m-d H:i:s",strtotime($solicitudes["momento"]."+1 second"));
						echo $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$solicitudes["cantidadarecibir"]."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."', '".$solicitudes["usuario"]."', '".$momento."', '".$solicitudes["momento"]."')")."<br>";
						
					}
					//echo $momento
					//return $intercambio["registro"]; 
					$this->Conexion->Consultar("DELETE FROM intercambios WHERE montoventa=0");
				}
			}catch(Exception $e){
				
			}
		}
		private function finalizarsolicitudes(){
			try{
				$consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisdestino WHERE momento NOT LIKE '2024-05%' AND  estado IS NULL");
				while($solicitudes = $this->Conexion->Recorrido($consultar)){
					$tasausd = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$solicitudes[0]."'"))[0];
					$cantidadusd = number_format($solicitudes["cantidadarecibir"]/$tasausd,2,".","");
					echo $solicitudes[0]." ".number_format($tasausd,$solicitudes["decimalesmoneda"],".","")."   ".$cantidadusd."<br>";
					$this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$solicitudes["momento"]."'");
					$this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$solicitudes["cantidadarecibir"]."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".date("Y-m-d H:i:s")."','".$solicitudes["momento"]."')");
					$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('USDT',".$cantidadusd.",'venta','".date("Y-m-d H:i:s")."','".$solicitudes["usuario"]."','javier','".$solicitudes["momento"]."','".$tasausd."','".$solicitudes["monedaorigen"]."','".$solicitudes["paisorigen"]."','".$solicitudes["cantidadaenviar"]."','envios',".$cantidadusd.")");
					
				}
			}catch(Exception $e){
				return  $e;
			}
			
		}
	}
	new Reparar();
?>