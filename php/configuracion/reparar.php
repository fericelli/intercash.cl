<?php
	Class Reparar{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			 $this->Conexion = new Conexion();
			 echo $this->operaciones();
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
						if($cantidadregistro>1){
							while($operacion = $this->Conexion->Recorrido($consultar2)){
								$consultar3 = $this->Conexion->Consultar("SELECT * FROM operaciones WHERE momento='".$operacion["momento"]."' AND usuario='".$datos[21]."' AND solicitud='".$datos[6]."'");
								$cantida = $this->Conexion->NFilas($consultar3);
								if($cantida>1){

									$this->Conexion->Consultar("DELETE FROM operaciones WHERE momento='".$operacion["momento"]."' LIMIT ".($cantida-1)."");
									//$this->Conexion->Consultar("INSERT INTO operaciones (moneda,monto,operacion,momento,usuario,operador,solicitud,tasa,monedaintercambio,paisintercambio,montointercambio,tipo) VALUES ('".$operacion["moneda"]."','".$operacion["monto"]."','".$operacion["operacion"]."','".$operacion["momento"]."','".$operacion["usuario"]."','".$operacion["operador"]."','".$operacion["solicitud"]."','".$operacion["tasa"]."','".$operacion["monedaintercambio"]."','".$operacion["paisintercambio"]."','".$operacion["montointercambio"]."','envio')");
									echo $operacion["momento"]."<br>";
								}
							}
						}
						/*
						if($cantidadregistro==1){
							if($operaciones = $this->Conexion->Recorrido($consultar2)){
								//$this->Conexion->Consultar("UPDATE operaciones SET montointercambio=".$datos["cantidadaenviar"]." WHERE usuario='".$datos[21]."' AND solicitud='".$datos[6]."'")."<br>";
											
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
									
									array_push($not,"'".$operacion5[3]."'");
									$totalintercambio += $montointercambio;
									if($total==$cantidadregistro){
										$modificar =  number_format($montointercambio+($datos["cantidadaenviar"]-$totalintercambio),$datos["decimalesmoneda"],".","") ."<br>";
										//$totalintercambio += $datos["cantidadaenviar"]-$totalintercambio;
									}
								}
								
							}
									
						}*/
						
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
	}
	new Reparar();
?>