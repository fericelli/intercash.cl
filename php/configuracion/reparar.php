<?php
	Class Reparar{
		private $Conexion;
		function __construct(){
			include("../../php/conexion.php");
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
				$retorno = "";
				$consultar = $this->Conexion->Consultar("SELECT * FROM intercambios WHERE momento BETWEEN '2024-04-01 19:43:28' AND '2024-05-03 17:11:47'");
				$consultar1 = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE momento BETWEEN '2024-04-01 19:43:28' AND '2024-05-03 17:11:47'");
				
				while($intercambio = $this->Conexion->Recorrido($consultar)){
					$momento = date('Y-m-d H:i:s', strtotime("+30 Minutes",strtotime($intercambio["registro"])));
					//$intercambio["registro"]." ".$momento."<br>";
					echo $this->Conexion->Consultar("UPDATE solicitudes SET estado=null WHERE momento='".$intercambio["registro"]."' AND usuario='".$intercambio["intermediario"]."'");
					echo $this->Conexion->Consultar("DELETE FROM intercambios   WHERE registro='".$intercambio["registro"]."' AND intermediario='".$intercambio["intermediario"]."'");
					echo $this->Conexion->Consultar("DELETE FROM operaciones   WHERE registro='".$intercambio["registro"]."' AND usuario='".$intercambio["intermediario"]."'");
					echo "<br>";
				}
			}catch(Exception $e){
				
			}
		}
		private function finalizarsolicitudes(){
			try{
				$consultar = $this->Conexion->Consultar("SELECT * FROM solicitudes LEFT JOIN paises ON paises.iso2=solicitudes.paisdestino WHERE estado IS NULL LIMIT 20");
				$cantidad =0;
				$retorno ="";
				while($solicitudes = $this->Conexion->Recorrido($consultar)){
					$cantidad ++;
					$momento = date('Y-m-d H:i:s', strtotime("+45 Minutes",strtotime($solicitudes["momento"])));
					//echo $momento."  ". date('Y-m-d',strtotime($solicitudes["momento"]))."<br>";
					$tasausd = 0;
					$tasausdt = 0;
					if(date('Y-m-d')==date('Y-m-d',strtotime($solicitudes["momento"]))){
						$tasausd = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$solicitudes[0]."' LIMIT 1"))[0];
					}else{
						
						$tasainicial = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT venta FROM precios WHERE moneda='".$solicitudes[0]."' AND momento<='".$momento."' LIMIT 1"))[0];
						if($tasainicial==null){
							$consultatasainicial = $this->Conexion->Consultar("SELECT solicitudes.cantidadarecibir,solicitudes.cantidadaenviar,operaciones.montointercambio,operaciones.cantidadusdt,tasas.decimalestasa FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro LEFT JOIN tasas ON tasas.monedaventa=solicitudes.monedadestino AND tasas.paisdestino=solicitudes.paisdestino AND tasas.monedacompra=solicitudes.monedaorigen AND  tasas.paisorigen=solicitudes.paisorigen WHERE solicitudes.monedadestino='".$solicitudes[0]."' AND solicitudes.monedaorigen='".$solicitudes["monedaorigen"]."' AND operaciones.registro<='".$momento."' AND moneda='USDT' AND oepreaciones.tipo='envios' AND operaciones.operacion='venta' ORDER BY registro DESC LIMIT 1");
							if($inicial = $this->Conexion->Recorrido($consultatasainicial)){
								 $tasa = number_format($inicial[0]/$inicial[1],$inicial[4],".","");
								
								 $cantidadrecibir = $inicial[1]*$tasa;
								$tasainicial = number_format($cantidadrecibir/$inicial[3],2,".","");
							}
						}
						$tasausd = $tasainicial;

					}

					if($tasausd==""){
						$tasausd = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa) FROM tasas WHERE monedaventa='".$solicitudes[0]."' LIMIT 1"))[0];
					}
					$usdt = number_format($solicitudes[3]/$tasausd,2,".","");
					$tasausdt =  number_format($solicitudes[2]/$usdt,2,".","");
					
					$preciobtc = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(venta) FROM precios WHERE moneda='BTC' AND momento BETWEEN '".$solicitudes["momento"]."' AND '".$momento."'"))[0];
					$tasaventa = 0;
					$montoventa = 0;
					$monedaventa = "";
					if($preciobtc==null){
						$tasaventa = $tasausdt;
						$montoventa = $usdt;
						$monedaventa = 'USDT';
					}else{
						$preciobtc =  number_format($preciobtc,2,".","");
						$tasaventa =  number_format($preciobtc,2,".","");
						$montoventa = number_format($usdt/$tasaventa,8,".","");
						$monedaventa = 'BTC';
					}
					$retorno .= $tasaventa."  ".$solicitudes[1]."  ".$solicitudes[2]."   ".$usdt."  ".$solicitudes["monedadestino"]."  ".$solicitudes["momento"]." ".$montoventa."  ".$preciobtc."  ".$tasausdt ."<br>";
					
					//
					//$cantidadusd = number_format($solicitudes["cantidadarecibir"]/$tasausd,2,".","");
					//echo $solicitudes[0]." ".number_format($tasausd,$solicitudes["decimalesmoneda"],".","")."   ".$cantidadusd."<br>";
					$retorno .= $this->Conexion->Consultar("UPDATE solicitudes SET estado='finalizado' WHERE momento='".$solicitudes["momento"]."'");
					$retorno .= $this->Conexion->Consultar("INSERT INTO intercambios (montoventa,monedaventa,montocompra,monedacompra,intermediario,momento,registro) VALUES ('".$solicitudes["cantidadarecibir"]."','".$solicitudes["monedadestino"]."','".$solicitudes["cantidadaenviar"]."','".$solicitudes["monedaorigen"]."','".$solicitudes["usuario"]."','".$momento."','".$solicitudes["momento"]."')");
					$retorno .= $this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,registro,tasa,monedaintercambio,paisintercambio,montointercambio,tipo,cantidadusdt) VALUES ('".$monedaventa."',".$montoventa.",'venta','".$momento."','".$solicitudes["usuario"]."','javier','".$solicitudes["momento"]."',".$tasaventa.",'".$solicitudes["monedaorigen"]."','".$solicitudes["paisorigen"]."','".$solicitudes["cantidadaenviar"]."','envios',".$usdt.")");
					$retorno .= "<br>";
				}
				return $retorno;
			}catch(Exception $e){
				return  $e;
			}
			
		}
		private function envioimagen(){
			$url =  "https://api.apichat.io/v1/sendImage";
                        $parameters = [
                            'number' => '584149192168',
							"chat_type" => "normal",
							"external_id" => "123",
                            'url' => 'https://intercash.cl/imagenes/operaciones/compra/javier/2024-01-05/operacion1/pagado.jpeg',
                            'caption' => ' '
                        ];
                        $headers = [
                            "accept: application/json",
                            "token: kMOMQkkx1Zxn",
                            "client-id: 26892"
                        ];
                        $qs = http_build_query($headers); 
                        $request = "{$url}?{$qs}"; 
                        $curl = curl_init(); 
                         // Set cURL options
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url,  
							CURLOPT_HTTPHEADER => $headers,          
                            CURLOPT_POSTFIELDS => $parameters, 
                            CURLOPT_POST => 1,     
                            CURLOPT_RETURNTRANSFER => 1         
                        ));

                        $response = curl_exec($curl); // Send the request, save the response
                        print_r($response);
                        curl_close($curl); // Close request
		}
		private function cambiartasasusdt(){
			$retorno = "";
			$consultar = $this->Conexion->Consultar("SELECT * FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro AND solicitudes.usuario=solicitudes.usuario WHERE monto>0 AND monedaintercambio<>'USDT' AND moneda='USDT' AND tipo='envios' AND operacion='venta'");
			
			while($operaciones = $this->Conexion->Recorrido($consultar)){
				
				$tasa = number_format($operaciones["montointercambio"]/$operaciones["monto"],2,".","");
				$retorno .= $operaciones["momento"]." ". $operaciones["usuario"]." ".$operaciones["monedadestino"]."  ".$operaciones["cantidadarecibir"]." ".$operaciones["monedaintercambio"]." ".$operaciones["montointercambio"]."  ".$operaciones["monto"]." ".$tasa."  ".$operaciones["tipo"]."<br>";
				//$retorno .= $this->Conexion->Consultar("UPDATE operaciones SET tasa='".$tasa."',cantidadusdt='".$operaciones["monto"]."' WHERE momento='".$operaciones[3]."' AND usuario='".$operaciones[4]."'")."<br>";
				//$retorno .= "UPDATE operaciones SET tasa='".$tasa."',cantidadusdt='".$operaciones["monto"]."' WHERE momento='".$operaciones[3]."' AND usuario='".$operaciones[4]."'<br>";
				/*if( $operaciones["moneda"]=="USDT" AND $operaciones["monto"]==0 AND $operaciones["operacion"]=="venta"){
					$tasa = $operaciones["montointercambio"]/$operaciones["monto"];
					return "SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones["monedadestino"]."' AND operacion='venta' AND monto>0 AND moneda='USDT' AND monto<>0";
					$tasa =  $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones["monedadestino"]."' AND operacion='venta' AND monto>0 AND moneda='USDT' AND monto<>0"))[0];
					$retorno .=  $operaciones["monedadestino"]."  ".$operaciones["cantidadarecibir"]." ".$operaciones["monedaintercambio"]." ".$operaciones["montointercambio"]."  ".$operaciones["monto"]." ".$tasa."  ".$operaciones["tipo"]."   ".$tasa."<br>";
					
					//$this->Conexion->Consultar("SELECT ");
					//$this->Conexion->Consultar("SELECT * FROM operaciones")
				}*/
			}
			return $retorno;
		}
		private function cambiartasas(){
			$retorno = "";
			$consultar = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE moneda='USDT' AND tipo='envios' AND operacion='venta' AND registro>'2023-12-31 23:59:59'");
			
			while($operaciones = $this->Conexion->Recorrido($consultar)){

				
				//$usdt = number_format($operaciones[2]/$tasa,2,".",""); 
				 //$tasa = number_format($usdt/$operaciones[4],2,".","");
				
				$usdt= number_format($operaciones["montointercambio"]/$operaciones["tasa"],2,".","");

				if($usdt!=$operaciones[1] OR $usdt!=$operaciones["cantidadusdt"]){
				
					$retorno .= $operaciones[0]." ". $operaciones[1]." ".$operaciones[2]." ".$operaciones[3]." ".$operaciones[4]."  ".$operaciones[5]." ".$operaciones[6]." ".$operaciones["tasa"]."  ".$operaciones["montointercambio"]."  ".$usdt."<br>";
					//$this->Conexion->Consultar("UPDATE operaciones SET monto='".$usdt."',cantidadusdt='".$usdt."' WHERE registro='".$operaciones["registro"]."' AND tasa='".$operaciones["tasa"]."' AND montointercambio='".$operaciones["montointercambio"]."'");
				}
				//$retorno .= $this->Conexion->Consultar("UPDATE operaciones SET tasa=".$tasa.",cantidadusdt=".$usdt.",monto=".$usdt." WHERE momento='".$operaciones[1]."' ");
				//$retorno .= $this->Conexion->Consultar("UPDATE operaciones SET tasa='".$tasa."',cantidadusdt='".$operaciones["monto"]."' WHERE momento='".$operaciones[3]."' AND usuario='".$operaciones[4]."'")."<br>";
				//$retorno .= "UPDATE operaciones SET tasa='".$tasa."',cantidadusdt='".$operaciones["monto"]."' WHERE momento='".$operaciones[3]."' AND usuario='".$operaciones[4]."'<br>";
				/*if( $operaciones["moneda"]=="USDT" AND $operaciones["monto"]==0 AND $operaciones["operacion"]=="venta"){
					$tasa = $operaciones["montointercambio"]/$operaciones["monto"];
					return "SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones["monedadestino"]."' AND operacion='venta' AND monto>0 AND moneda='USDT' AND monto<>0";
					$tasa =  $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones["monedadestino"]."' AND operacion='venta' AND monto>0 AND moneda='USDT' AND monto<>0"))[0];
					$retorno .=  $operaciones["monedadestino"]."  ".$operaciones["cantidadarecibir"]." ".$operaciones["monedaintercambio"]." ".$operaciones["montointercambio"]."  ".$operaciones["monto"]." ".$tasa."  ".$operaciones["tipo"]."   ".$tasa."<br>";
					
					//$this->Conexion->Consultar("SELECT ");
					//$this->Conexion->Consultar("SELECT * FROM operaciones")
				}*/
			}
			return $retorno;
		}

		private function cambiartasabtc(){
			$retorno = "";
			$consultar = $this->Conexion->Consultar("SELECT operaciones.momento,operaciones.registro,operaciones.monedaintercambio,operaciones.montointercambio,operaciones.monto FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro AND solicitudes.usuario=solicitudes.usuario WHERE tasa>77000 AND monedaintercambio<>'USDT' AND moneda='BTC' AND tipo='envios' AND operacion='venta'");
			while($operaciones = $this->Conexion->Recorrido($consultar)){
				$tasa = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(venta) FROM precios WHERE momento BETWEEN '".$operaciones[1]."' AND '".$operaciones[0]."'"))[0];
				if(is_null($tasa)){
					$tasa =  $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones[2]."' AND moneda='USDT' AND momento >= '".$operaciones[1]."' AND tipo='envios' AND monto>0 LIMIT 1"))[0];
					//$retorno .= "SELECT tasa FROM operaciones WHERE monedaintercambio='".$operaciones[2]."' AND moneda='USDT' AND momento >= '".$operaciones[1]."' AND tipo='envios' AND monto>0 LIMIT 1<br>"; 
				
				}
				$usdt = number_format($operaciones[3]/$tasa,2,".",""); 
				$tasa = number_format($usdt/$operaciones[4],2,".","");
				$satoshi = $operaciones[4];
				if($tasa>100000){
					$satoshi = $operaciones[4]*10;
				}
				
				$tasa = number_format($usdt/$satoshi,2,".","");
				$satoshi = number_format($satoshi,8,".","");
				$retorno .= $operaciones[0]." ". $operaciones[1]." ".$operaciones[2]." ".$operaciones[3]." ".$satoshi."  ".$usdt." ".$tasa."<br>";
				$retorno .= $this->Conexion->Consultar("UPDATE operaciones SET monto=".$satoshi.",tasa=".$tasa.",cantidadusdt=".$usdt." WHERE momento='".$operaciones[0]."' ");
				
			}
			return $retorno;
		}

		private function compras(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE operacion='compra' AND momento>'2023-12-31 23:59:59'");
			while($operaciones = $this->Conexion->Recorrido($consultar)){
				$tasa = number_format($operaciones["montointercambio"]/$operaciones["monto"],2,".","");
				$diferencia = $operaciones["tasa"]-$tasa;
				
					$retorno .= $operaciones[0]." ". $operaciones[1]." ".$operaciones[2]." ".$operaciones[3]." ".$tasa." ".$operaciones["tasa"]." ".$operaciones["montointercambio"]." ".$operaciones["monedaintercambio"]."<br>";
					
				
				//$retorno .= $this->Conexion->Consultar("UPDATE operaciones SET tasa=".$tasa.",cantidadusdt=".$operaciones[1]." WHERE momento='".$operaciones[3]."'");
				
			}
			return $retorno;
		}
		private function comprasfaltantesusdt(){
			$consultar = $this->Conexion->Consultar("SELECT * FROM paises WHERE receptor IS NOT NULL");
			$retorno = "";
			$momento = "2023-12-30 00:00:00";
			
					
			while($paises = $this->Conexion->Recorrido($consultar)){
				$montousdt = 0;
				$montobtc = 0;
				$totalusdt = 0;
				$totalmoneda = 0;
				$monedacompra = 0;
				$usdtcompra = 0;
				//$retorno .= $paises["iso"]."  ".$paises["iso_moneda"]."<br>";
				$consultarusdt = $this->Conexion->Consultar("SELECT SUM(monto),SUM(montointercambio),SUM(cantidadusdt),AVG(tasa) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND paisintercambio='".$paises["iso2"]."' AND moneda='USDT' AND tipo='envios' AND operacion='venta' AND registro>'2023-12-31 23:59:59'");
				$consultarbtc = $this->Conexion->Consultar("SELECT SUM(monto),SUM(montointercambio),SUM(cantidadusdt) FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND paisintercambio='".$paises["iso2"]."' AND moneda='BTC' AND tipo='envios' AND operacion='venta' AND registro>'2023-12-31 23:59:59'");
				if($usdt = $this->Conexion->Recorrido($consultarusdt) AND $btc = $this->Conexion->Recorrido($consultarbtc)){
					if(!is_null($usdt[0])){
						$montousdt = number_format($usdt[0],2,".","");
						$totalmoneda += $usdt[1];
						$totalusdt += number_format($usdt[2],2,".","");
					}
					if(!is_null($btc[0])){
						$montobtc = number_format($btc[0],8,".","");
						$totalmoneda += $btc[1];
						$totalusdt += number_format($btc[2],2,".","");
					}

					
					$consultarcompra = $this->Conexion->Consultar("SELECT  SUM(monto),SUM(montointercambio),SUM(cantidadusdt)  FROM operaciones WHERE monedaintercambio='".$paises["iso_moneda"]."' AND paisintercambio='".$paises["iso2"]."' AND moneda='USDT' AND operacion='compra' AND momento>'2023-12-31 23:59:59'");
					if($compras = $this->Conexion->Recorrido($consultarcompra)){
						if(!is_null($compras[1])){
							$monedacompra = number_format($compras[1],$paises["decimalesmoneda"],".","");
							$usdtcompra  = number_format($compras[0],2,".","");
						}
						
					}
				}
				$momento = date('Y-m-d H:i:s', strtotime("+1 Minute",strtotime($momento)));
					$retorno .= "moneda ".$paises["iso_moneda"]."  ".$totalmoneda."  ".$totalusdt." ".$usdt[3]."<br>";
					$retorno .= "envios BTC   ".$montobtc."  ".number_format($btc[1],$paises["decimalesmoneda"],".","")." ".number_format($btc[2],2,".","")."<br>";
					$retorno .= "envios USDT   ".$montousdt."  ".number_format($usdt[1],$paises["decimalesmoneda"],".","")." ".number_format($usdt[2],2,".","")."<br>";
					 
					$retorno .= "compras USDT ".$monedacompra."  ".$usdtcompra."<br>";
					$tasausd = number_format($monedacompra/$usdtcompra,2,".","");
					$compramoneda = number_format($totalmoneda - $monedacompra, $paises["decimalesmoneda"],".","");
					
					if($monedacompra==0){
						$tasausd = number_format($usdt[1]/$montousdt,2,".","");
					}
					
					$comprausdt = number_format($compramoneda/$tasausd, 2,".","");
					
					//$retorno .= $compramoneda." ".$comprausdt ."<br>";
					//$retorno .= $this->Conexion->Consultar("INSERT INTO operaciones(moneda,monto,operacion,momento,operador,tasa,monedaintercambio,paisintercambio,montointercambio,cantidadusdt) VALUES ('USDT',".$comprausdt.",'compra','".$momento."','javier',".$tasausd.",'".$paises["iso_moneda"]."','".$paises["iso2"]."',".$compramoneda.",".$comprausdt.")");
				
				



			}
			
			
			
			return $retorno;
		}
		private function comprasfaltantesbtc(){
			$cantidadusdt = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(cantidadusdt) FROM operaciones WHERE moneda='USDT' AND tipo='envios' AND operacion='venta' AND momento<'2023-12-31 23:59:59'"))[0];
			$cantidadbtc = number_format($this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(monto) FROM operaciones WHERE moneda='BTC' AND tipo='envios' AND operacion='venta' AND momento<'2023-12-31 23:59:59'"))[0], 8,".","");
			
			$cantidadcomprada = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT SUM(cantidadusdt)  FROM operaciones WHERE  moneda='USDT' AND operacion='compra' AND momento<'2023-12-31 23:59:59'"))[0];
			$porcomprar = $cantidadcomprada-$cantidadusdt;
			$tasa = number_format($porcomprar/$cantidadbtc, 2,".","");
			 $porcomprar." ".$cantidadbtc." ".$tasa;
			 return $retorno .= $this->Conexion->Consultar("INSERT INTO operaciones(moneda,monto,operacion,momento,operador,tasa,monedaintercambio,montointercambio,cantidadusdt) VALUES ('BTC',".$cantidadbtc.",'compra','2023-12-31 23:59:59','javier',".$tasa.",'USDT',".$porcomprar.",".$porcomprar.")");
				
			
		}
		private function reparartasas(){
			
		}
	}
	new Reparar();
?>