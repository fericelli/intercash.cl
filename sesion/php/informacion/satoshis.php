<?php
    Class Satoshis{
        function __construct(){
            include("../../../php/conexion.php");
			$conexion = new Conexion();
            $filtos = "";
            if(isset($_POST["usuario"])){
                $filtos = "AND intermediario='".$_POST["usuario"]."'";
            }
            
            if($filtos!=""){
                $consultar = $conexion->Consultar("SELECT DISTINCT(monedacompra) FROM intercambios ".str_replace("AND", "WHERE", $filtos));
            }else{
                $consultar = $conexion->Consultar("SELECT DISTINCT(monedacompra) FROM intercambios ".$filtos);
            }
            
            $informacion = "["; 
            while($monedascompra = $conexion->Recorrido($consultar)){
                $informacion .= '{"moneda":"'.$monedascompra[0].'",';
                $consultar1 = $conexion->Consultar("SELECT DISTINCT(monedaventa) FROM intercambios WHERE monedacompra='".$monedascompra[0]."' ".$filtos);
                $arraymonedasventas = [];
                while($monedasventa = $conexion->Recorrido($consultar1)){
                    $consultar2 = $conexion->Consultar("SELECT IFNULL(SUM(montoventa),0) FROM intercambios WHERE monedacompra='".$monedascompra[0]."' AND monedaventa='".$monedasventa[0]."' ".$filtos);
                    if($montoventa = $conexion->Recorrido($consultar2)){
                        $arraymonedasventas[$monedasventa[0]] = $montoventa[0];
                    }
                }
              //  $consultardinerocambiado = $conexion->Consultar("SELECT IFNULL(SUM(montoventa),0) FROM intercambios WHERE monedaventa='".$monedascompra[0]."'");

                //echo $conexion->Recorrido($consultardinerocambiado)[0];
                $gastosmoneda = 0;
                if($filtos==""){
                    $consultagastos = $conexion->Consultar("SELECT IFNULL(SUM(montocompra),0) FROM intercambios WHERE monedacompra='".$monedascompra[0]."' AND intermediario='gastos'");
                    if($gastosmonedas = $conexion->Recorrido($consultagastos)){
                        $gastosmoneda = $gastosmonedas[0];
                    }
                }
                

                $satoshisvendidos = 0;
                $dinerorecibido = 0;
                $dinerocomprado = 0;
                $monedascompradas = 0;
                for($i=0;$i<count(array_keys($arraymonedasventas));$i++){
                    $consultaoperacionesventa = $conexion->Consultar("SELECT IFNULL(SUM(monto),0),IFNULL(SUM(montobtc),0) FROM operaciones WHERE currency='".array_keys($arraymonedasventas)[$i]."' AND operacion='ven'");
                    $datos = $conexion->Recorrido($consultaoperacionesventa);
                    if($datos[0]!=0){
                        $satoshisvendidos += ($datos[1]*$arraymonedasventas[array_keys($arraymonedasventas)[$i]])/$datos[0];
                    }
                    //var_dump($datos[0]);
                    //echo $arraymonedasventas[array_keys($arraymonedasventas)[$i]];
                    //var_dump($conexion->Recorrido($consultaoperacionesventa));
                    
                }
                $consultardinerocambiado = $conexion->Consultar("SELECT * FROM intercambios WHERE monedaventa='".$monedascompra[0]."'");
               // print_r($conexion->Recorrido($consultardinerocambiado));
                //echo $satoshisvendidos;
                $consultarmonedacompra = $conexion->Consultar("SELECT IFNULL(SUM(montocompra),0) FROM intercambios WHERE monedacompra='".$monedascompra[0]."' ".$filtos);
                $dinerorecibido = $conexion->Recorrido($consultarmonedacompra)[0];
                $consultaoperacionescompra = $conexion->Consultar("SELECT IFNULL(SUM(monto),0),IFNULL(SUM(montobtc),0) FROM operaciones WHERE currency='".$monedascompra[0]."' AND operacion='com'");
                $resultadooperacioncompra = $conexion->Recorrido($consultaoperacionescompra);
                
                

                $satoshisvendidos = round($satoshisvendidos, 8);
                $dinerocomprado =  $resultadooperacioncompra[0];
                $satoshiscomprados =  round($resultadooperacioncompra[1], 8);

                

                if($dinerocomprado>$dinerorecibido){

                 
                    $dinerodisponible = 0;
                    $satoshisinvertidos = $satoshisvendidos - ($dinerorecibido*$satoshiscomprados)/$dinerocomprado;
                    
                }else{
                    $dinerodisponible = $dinerorecibido-$dinerocomprado;
                    $satoshisinvertidos = $satoshisvendidos - $satoshiscomprados;
                }
                
               
                $gananciasatoshis = 0;
                $gananciadinero = 0;
                if($dinerodisponible==0){
                    
                    $gananciadinero = $dinerodisponible;
                    if($satoshisinvertidos>0){
                        $gananciasatoshis  = $satoshisinvertidos*-1;
                        
                    }else{
                        $gananciasatoshis  = abs($satoshisinvertidos);
                    }
                    
                }else{
                    if($satoshisinvertidos>0){
                        $gananciasatoshis  = 0;
                        $gananciadinero = 0; 
                    }else{
                        $gananciasatoshis  = abs($satoshisinvertidos);
                        $gananciadinero = $dinerodisponible; 
                    }
                }
                
                if($satoshisinvertidos<0){
                    $satoshisinvertidos = 0;
                }

                $informacion .= '"invertidosatoshi":"'.number_format($satoshisinvertidos, 8).'",
                "dineroenviado":"'.$dinerorecibido.'",
                "dinerodisponoble":"'.$dinerodisponible.'",
                "gananciasatoshis":"'.number_format($gananciasatoshis, 8).'",
                "gananciamoneda":"'.$gananciadinero.'"},';
                

                
                //echo $dinerodisponible/$satoshisinvertidos;
                //print_r($conexion->Recorrido($consultaoperacionescompra)); 
                /*var_dump($intercambios);
                    
                    
                    $cunsultaintercambio = $conexion->Consultar("SELECT SUM(montocompra),IFNULL(SUM(montoventa),0) FROM intercambios WHERE monedacompra='".$monedas[0]."'");
                    if($operacionventa = $conexion->Recorrido($consultaoperacionesventa) AND $intercambio = $conexion->Recorrido($cunsultaintercambio) AND $operacioncompra = $conexion->Recorrido($consultaoperacionescompra)){
                        
                        
                    }*/
                
                //break;
            }
            echo substr($informacion,0,strlen($informacion)-1)."]";
            //var_dump($arraymonedasventas);
        }
    }
    new Satoshis();
?>


