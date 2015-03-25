<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ViMP Videoportal</title>
    <link href="css/bootstrap_1.css" rel="stylesheet">

  </head>
  <body>
     <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php?page=home">ViMP Videoportal</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <li><a href="index.php?page=home">Home</a></li>
            <li><a href="index.php?page=videos">Videos</a></li>
          </ul>
            <?php if(!isset($_SESSION['uID'])) {?>
            <form class="navbar-form navbar-right" action="index.php?page=login" method="POST">
            <div class="form-group">
              <input type="text" placeholder="Email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" name="pwd" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-success">Login</button>
            <a href="index.php?page=register"><button type="button" class="btn btn-success">Registrieren</button></a>
          </form>
            <?php } else { ?>
           
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php  echo $_SESSION['email']; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="index.php?page=edituser&id=<?php echo $_SESSION['uID']; ?>">Profil bearbeiten</a></li>
                  <li><a href="index.php?page=upload">Upload</a></li>
                  <li><a href="index.php?page=logout">Logout</a></li>
                </ul>
              </li>
            </ul>
            <?php } ?>
        </div>
      </div>
    </nav>
          
    <div class="jumbotron">
      <div class="container">
          <h3>ViMP Videoportal</h3>
        <p>Herzlich Willkommen zum ViMP Videoportal.</p>
        <p>Hier können Sie Videos hochladen, anschauen und bearbeiten.</p>
       </div>
    </div>

    <div class="container">
        <?php
            // Wenn die Config-Datei noch fehlt => Installation fehlt noch
            if(!file_exists("config.php"))
            {
                echo '<p>Bitte führen Sie zuvor die Installation durch!</p>';
                echo '<p><a class="btn btn-default" href="Install/install.php" role="button">Zur Installation</a></p>';
                return;
            }
            
            include("config.php");
            include("class/database.php");
            include("class/user.php");
            include("class/video.php");
        
            define("_PATH_", "sites/");
            define("_TYPE_", ".php");
            
            $site = @$_GET['page'];
            
            // Aus Sicherheitsgründen sollte der Installationsordner entfernt werden
            if(file_exists("Install/install.php"))
            {
                echo '<div class="alert alert-danger" role="alert">';
                echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>';
                echo '<span class="sr-only">Error:</span>';
                echo ' Bitte entfernen sie den "Install" Ordner!';
                echo '</div>';
            }
            
            // DB Verbindung herstellen
            $db = new dbase($database['dbname'], $database['user'], $database['pw']);
            $con = $db->Connect();
            if(!$con)
            {
                echo '<p>Verbindung fehlgeschlagen!</p>';
            }
            
            // Index.php?page = LADEN DER HIER AUSGEWÄHLTEN SEITE
            if(!isset($site) || empty($site) || !file_exists(_PATH_. $site . _TYPE_))
                include(_PATH_ . 'home' . _TYPE_);
            else
                include (_PATH_ . $site . _TYPE_);
        ?>
    </div>
    <hr>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <p class="text-muted"><a href="index.php?page=impressum" style="color:#EEE9E9;">Impressum</a></p>
                </div>
                <div class="col-md-1">
                    <p class="text-muted"><a href="index.php?page=register" style="color:#EEE9E9;">Registrieren</a></p>
                </div>
                <div class="col-md-5">
                    <p class="text-muted">&copy; Christian Lechner 2015</p>
                </div>
            </div>
            </div>
                
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
