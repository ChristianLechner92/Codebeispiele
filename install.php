<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ViMP Videoportal</title>
    <link href="../css/bootstrap_1.css" rel="stylesheet">

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
          <a class="navbar-brand" href="install.php">ViMP Videoportal - Setup</a>
        </div>
      </div>
    </nav>
      <div class="jumbotron">
    <div class="container">
        <h1>ViMP Videoportal - Setup</h1>
        <p>Schnell und einfach Ihr Portal installieren.</p>
     </div>
</div>
      
      <div class="container">
    <?php   
        // Wenn die Config-Datei schon existiert, wurde die Installation schon durchgeführt
        if(file_exists("../config.php"))
        {
            echo '<p>Die Installation wurde bereits abgeschlossen, bitte entfernen Sie den "Install" Ordner!</p>';
            echo '<p><a class="btn btn-default" href="../index.php?page=home" role="button">Zurück zur Homepage</a></p>';
            return;
        }
  
        define("_PATH_", "steps/");
        define("_PREFIX_", "step");
        define("_TYPE_", ".php");
        
        $step = @$_GET['step'];
        
        // install.php?page = LADEN DIESER AUFGERUFENEN SEITE
        if(!isset($step) || empty($step) || !file_exists(_PATH_ . _PREFIX_ . $step . _TYPE_))
            include(_PATH_ . "main" . _TYPE_);
        else
            include(_PATH_ . _PREFIX_ . $step . _TYPE_);
      ?>
  </div>  
  <footer class="footer">
    <div class="container">
        <p class="text-muted">&copy; Christian Lechner 2015</p>
    </div>
</footer>  
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
