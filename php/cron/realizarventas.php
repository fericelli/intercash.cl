<?php
    Class RealizarVentas{
        private $Conexion;
		function __construct(){
			//include("/home/u956446715/public_html/public_html/php/conexion.php");
			include("./php/conexion.php");
			$this->Conexion = new Conexion();
			$values = "";
			$update = "";
            $preciousdt = 0;
			try{
                $consultar = $this->Conexion->Consultar("SELECT solicitudes.cantidadarecibir,solicitudes.cantidadaenviar,solicitudes.monedadestino,operaciones.montointercambio,operaciones.momento,operaciones.registro,operaciones.moneda,operaciones.usuario,operaciones.monedaintercambio,operaciones.cantidadusdt FROM operaciones LEFT JOIN solicitudes ON solicitudes.momento=operaciones.registro AND solicitudes.usuario=operaciones.usuario  WHERE monto=0 AND tipo='envios'");
                while($operaciones = $this->Conexion->Recorrido($consultar)){
                    $tasa = $operaciones[0]/$operaciones[1];
                    $monto = 0;
                    $precio = 0;
                    if($operaciones[9]!=0){
                        if($operaciones["moneda"]=="BTC"){
                            $precio = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT (AVG(compra)+AVG(venta))/2 AS precio FROM precios WHERE momento BETWEEN '".$operaciones[5]."' AND '".$operaciones[4]."'  AND moneda='".$operaciones[6]."'"))[0];
                            $precio = number_format($precio/1.01,2,".","");
                            $monto = number_format($operaciones[9]/$precio,8,".","");
                            $preciousdt = number_format($operaciones[3]/$operaciones[9],2,".","");
                        }else{
                            $monto = $operaciones[9];
                            $preciousdt = number_format($operaciones[3]/$operaciones[9],2,".","");
                            $precio = $preciousdt;
                        }
                        //echo $operaciones[3]."  ".$monto."  ".$operaciones[9]."  ".$preciousdt."\n";
                        $this->Conexion->Consultar("UPDATE operaciones SET monto='".$monto."',tasa='".$precio."' WHERE momento='".$operaciones[4]."' AND usuario='".$operaciones[7]."'");
                        
                    }
                    

                    //monto=".$monto.",tasa=".$precio."
                    //if($monto!=0){
                        
                }
            }catch(Exception $e){
                
            }
			$this->Conexion->CerrarConexion();
		}
    }
    new RealizarVentas();
?>