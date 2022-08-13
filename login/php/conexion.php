<?php 
	class Conexion{
		private $Servidor;
		private $UsuarioDeMysql;
		private $ClaveDeMysql;
		private $BaseDeDatos;
		private $Conectar;
		
		function __construct(){
			$this->Conexiones("localhost","root","Appserv2324**","intercash.cl");
		}
		private function Conexiones($S,$U,$C,$B){
			$this->Servidor = $S;
			$this->UsuarioDeMysql = $U;
			$this->ClaveDeMysql = $C;
			$this->BaseDeDatos = $B;
			$this->ConectarAMysql();
			mysqli_set_charset($this->Conectar,"utf8");
            date_default_timezone_set('America/Santiago');
		}
		
		private function ConectarAMysql(){
			$this->Conectar =  new mysqli($this->Servidor, 
			$this->UsuarioDeMysql,
			$this->ClaveDeMysql,$this->BaseDeDatos)or die(mysql_errno());
		}
		public function CerrarConexion(){
			return mysqli_close($this->Conectar);
		}
		public function Consultar($sql){
			$resultado = mysqli_query($this->Conectar,$sql);
			return $resultado;
		}
		public function NFilas($sql){
			return mysqli_num_rows($sql);
		}
		public function NColumnas($sql){
			return mysqli_num_fields($sql);
		}
		public function NomCampo($resultado,$ncolumna){
			$campos = mysqli_fetch_field_direct($resultado, $ncolumna);
			return $campos->name;
		}
		public function TipoCampo($resultado,$ncolumna){
			$campos = mysqli_fetch_field_direct($resultado, $ncolumna);
			return $campos->type;
		}
		public function LongitudCampo($resultado,$ncolumna){
			$campos = mysqli_fetch_field_direct($resultado, $ncolumna);
			return $campos->length;
		}
		public function Recorrido($consulta){
			return mysqli_fetch_array($consulta);
		}
		public function ConsultaId(){
			return mysqli_insert_id($this->Conectar);
		}
		
	}
?>