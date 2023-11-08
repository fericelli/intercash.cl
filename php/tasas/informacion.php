<?php
	Class Informacion{
		private $Conexion;
		function __construct(){
			include("../conexion.php");
			$this->Conexion = new Conexion();
			echo "[".$this->retorno()."]";
			$this->Conexion->CerrarConexion();
		}
		private function retorno(){
            $tasa = 0;
            $tasausddestino=0;
            $decimalestasa = 0;
			
             $tasas = $this->Conexion->Recorrido($this->Conexion->Consultar("SELECT AVG(anuncioventa),tasa FROM tasas WHERE monedaventa='".$_POST["monedadestino"]."' AND paisdestino='".$_POST["paisdestino"]."' AND monedacompra='".$_POST["monedaorigen"]."' AND paisorigen='".$_POST["paisorigen"]."'"));
            
             $tasausddestino = $tasas[0];
             $tasa = $tasas[1];
            if(isset($_POST["cantidadenviar"])){
                
                $dinerorecibir = round($tasa*$_POST["cantidadenviar"], $_POST["decimaldestino"]);
                $dineroenviar=round($_POST["cantidadenviar"],$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
                
                $disponiblidad = "si";
                
                return '{"dineroenviar":"'.$dineroenviar.'",
                "dinerorecibir":"'.$dinerorecibir.'",
                "usd":"'.round($usd, 2).'",
                "tasa":"'.$tasa.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }else{
                
                $dinerorecibir = round($_POST["cantidadrecibir"],$_POST["decimaldestino"]);
                $dineroenviar = round($dinerorecibir/$tasa,$_POST["decimalorigen"]);
                $usd = ($dinerorecibir/$tasausddestino);
                $disponiblidad = "si";
                
                return '{"dineroenviar":"'.$dineroenviar.'",
                "dinerorecibir":"'.$dinerorecibir.'",
                "usd":"'.round($usd, 2).'",
                "tasa":"'.$tasa.'",
                "diponibilidad":"'.$disponiblidad.'"}';
            }
		} 
	}
	new Informacion();
?>