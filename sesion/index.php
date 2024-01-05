<?php
  Class Index{
    private $Conexion;
		function __construct(){
      session_start();
      ?>

			<!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="../imagenes/logo.ico"> 
        <link href="https://file.myfontastic.com/iKa94pqcdMLv4DmY2UYJVK/icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" >
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
        
        
        <link rel="stylesheet" href="./../solicitud/css/style.css">
        <link rel="stylesheet" href="./../css/screenshot.css">
        <link rel="stylesheet" href="./../css/cajero.css">
        <link rel="stylesheet" href="css/style.css">
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="js/sesion.js"></script>
        
        <title>Intercash</title>
      </head>
      <body>
        <div id="sidemenu" class="menu-collapsed">
          <div id="header">
            <div id="title"><span>Intercash.CL</span></div>
            <div id="menu-btn" >
              <i class=" iconos icono-menu"></i>
            </div>
          </div>

          <div id="profile">
            <div id="photo"><img src="imagenes/logo (1).png"></div>
            <div id="name"><span><?php echo $_SESSION["nombreusaurio"]; ?></span></div>
          </div>
          <div id="menu-items">

            <?php    
              if($_SESSION["tipousaurio"]=="administrador"){
            ?>
                <div class="item" opcion="capital">
                  <a>
                    <div class="icon"><i class=" iconos icono-graficas"></i></div>
                    <div class="title"><span>Patrimonio</span></div>
                  </a>
                </div>
                <div class="item" opcion="tasas">
                  <a>
                    <div class="icon"><i class=" iconos icono-calculadora"></i></div>
                    <div class="title"><span>Tasas</span></div>
                  </a>
                </div>
                <div class="item" opcion="solicitud">
                  <a>
                    <div class="icon"><i class=" iconos icono-dinero"></i></div>
                    <div class="title"><span>Solicitar Intecambio</span></div>
                  </a>
                </div>
                <div class="item" opcion="cajero">
                  <a>
                    <div class="icon"><i class=" iconos icono-vallet"></i></div>
                    <div class="title"><span>Cajero</span></div>
                  </a>
                </div>
                
                <div class="item" opcion="solicitudes">
                  <a>
                    <div class="icon"><i class=" iconos icono-texto"></i></div>
                    <div class="title"><span>Solicitudes</span></div>
                  </a>
                </div>
                <div class="item" opcion="operaciones">
                  <a>
                    <div class="icon"><i class=" iconos icono-desktop"></i></div>
                    <div class="title"><span>Operaciones</span></div>
                  </a>
                </div>
                <div class="item" opcion="operacionescripto">
                  <a>
                    <div class="icon"><i class=" iconos icono-bitcoin"></i></div>
                    <div class="title"><span>Comprar Cripto</span></div>
                  </a>
                </div>
                <div class="item" opcion="intercambios">
                  <a>
                    <div class="icon"><i class=" iconos icono-texto"></i></div>
                    <div class="title"><span>Intercambios</span></div>
                  </a>
                </div>
                <div class="item" opcion="depositos">
                  <a>
                    <div class="icon"><i class=" iconos icono-money"></i></div>
                    <div class="title"><span>Depositos</span></div>
                  </a>
                </div>
                <div class="item" opcion="debitos">
                  <a>
                    <div class="icon"><i class="iconos icono-dollar-bill"></i></div>
                    <div class="title"><span>Debitos</span></div>
                  </a>
                </div>
                <div class="item" opcion="vender">
                  <a>
                    <div class="icon"><i class="iconos icono-venta"></i></div>
                    <div class="title"><span>Vender</span></div>
                  </a>
                </div>
            <?php  
              }
            ?>
            <?php    
              if($_SESSION["tipousaurio"]=="sociocomercial"){
            ?>
                <div class="item" opcion="cajero">
                  <a>
                    <div class="icon"><i class=" iconos icono-vallet"></i></div>
                    <div class="title"><span>Cajero</span></div>
                  </a>
                </div>
                <?php
                  if($_SESSION["receptordinero"]=="si"){?>
                
                      <div class="item" opcion="depositos">
                      <a>
                        <div class="icon"><i class=" iconos icono-money"></i></div>
                        <div class="title"><span>Depositos</span></div>
                      </a>
                    </div>
                <?php
                  }
                ?>
                <div class="item" opcion="solicitud">
                  <a>
                    <div class="icon"><i class=" iconos icono-dinero"></i></div>
                    <div class="title"><span>Solicitar Intecambio</span></div>
                  </a>
                </div>
                <div class="item" opcion="solicitudes">
                  <a>
                    <div class="icon"><i class=" iconos icono-texto"></i></div>
                    <div class="title"><span>Solicitudes</span></div>
                  </a>
                </div>
                <div class="item" opcion="intercambios">
                  <a>
                    <div class="icon"><i class=" iconos icono-texto"></i></div>
                    <div class="title"><span>Intercambios</span></div>
                  </a>
                </div>
            <?php  
              }
            ?>
          </div>
        </div>
        
        <div class="contenido-imagen"><img style="margin: auto;" src="../imagenes/carga.gif"></div>
        <div id="main-container">
          
        </div>
      </body>
      </html>
      
      
      
      
      
      <?php
			$this->Conexion->CerrarConexion();
		}
  }
  new Index();
?>

