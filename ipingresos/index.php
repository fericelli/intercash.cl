<?php
  Class Index{
    private $Conexion;
		function __construct(){
            echo $_SERVER['REMOTE_ADDR']."<br>";
            echo $_SERVER['HTTP_CLIENT_IP']."<br>";
            echo $_SERVER['HTTP_X_FORWARDED_FOR']."<br>";
            echo $_SERVER['REMOTE_ADDR']."<br>";
            echo $_SERVER['HTTP_CLIENT_IP']."<br>";
            echo $_SERVER['HTTP_X_FORWARDED_FOR']."<br>";  
        }
  }
  new Index();
?>

