<?php
    class video
    {
        private $Con;
        public function __construct($con) 
        {
            $this->Con = $con;
        }
        
        // Speichert hochgeladene Videos
        // $filename = Dateiname
        // $type = Typ
        // $title = Titel des Videos
        // $desc = Beschreibung des Videos
        public function Upload($filename, $type, $title, $desc)
        {
            if(empty($title) || empty($desc) || empty($filename) || empty($type))
                return FALSE;
            
            $cname = str_replace(" ", "_", $filename);
            $tmp_name = $_FILES['uploadvideo']['tmp_name'];
            $target_path = "videos/";
            $target_path = $target_path . basename($cname);
            
            
            
            // Speichert die Datei auf dem Server
            if(!move_uploaded_file($tmp_name, $target_path))
                return FALSE;
            
            $q = $this->Con->prepare("INSERT INTO videos (title, description, filename, youtube) VALUES (:title, :desc, :fname, 0)");
            $q->bindValue(':title', $title);
            $q->bindValue(':desc', $desc);
            $q->bindValue(':fname', $cname);
            $q->execute();
            
            return true;
        }
        
        // Methode zum Speichern von YouTube Videos
        // $url = ID_DES_VIDEOS (/watch?v=ID)
        // $title = Titel des Videos
        // $desc = Beschreibung des Videos
        public function SaveYoutube($url, $title, $desc)
        {
            if(empty($url) || empty($title))
                return false;
        
            $q = $this->Con->prepare("INSERT INTO videos (title, description, filename, youtube) VALUES (:title, :desc, :filename, 1)");
            $q->bindValue(':title', $title);
            $q->bindValue(':desc', $desc);
            $q->bindValue(':filename', $url);
            
            if(!$q->execute())
                return false;
            
            return true;
        }
        
        // Holt sich die letzten 3 Video-Einträge
        public function GetTop3()
        {
            $q = $this->Con->prepare("SELECT * FROM videos ORDER BY vID desc LIMIT 3");
            $q->execute();
            
            return $v = $q->fetchAll();
        }
        
        // Holt sich alle Video-Einträge
        public function GetAll()
        {
            $q = $this->Con->prepare("SELECT * FROM videos ORDER BY vID desc ");
            $q->execute();
            
            if($q->rowCount() < 1)
                return false;
            
            return $v = $q->fetchAll();
        }
        
        // Holt sich Daten zu einem Videoeintrag
        // $id = ID aus der Datenbank
        public function GetVideoByID($id)
        {
            $q = $this->Con->prepare("SELECT * FROM videos WHERE vID = :id");
            $q->bindValue(':id', $id);
            $q->execute();
            
            if($q->rowCount() > 1)
                return false;
            
            return $v = $q->fetch();
        }
        
        // Methode zum Bearbeiten eines Videos
        // $title = neuer Titel des Videos
        // $desc = neue Beschreibung des Videos
        // $id = ID aus der Datenbank des Videos
        public function EditVideo($title, $desc, $id)
        {
            if(empty($title))
                return false;
            
            $q = $this->Con->prepare("UPDATE videos SET title = :title, description = :desc WHERE vID = :id");
            $q->bindValue(':title', $title);
            $q->bindValue(':desc', $desc);
            $q->bindValue(':id', $id);
            $q->execute();
            
            return true;
        }
        
        // Löscht ein Video
        // $id = ID aus der Datenbank
        // Bei Youtube Videos wird keine Datei entfernt, lediglich der DB Eintrag
        public function Delete($id)
        {
            $q = $this->Con->prepare("SELECT filename, youtube FROM videos WHERE vID = :id");
            $q->bindValue(':id', $id);
            $q->execute();
            
            $vid = $q->fetch();
            
            // Dateientfernung findet nur bei hochgeladenen Videos statt!
            if($vid['youtube'] == 0)
            {
                if(!unlink("videos/" . $vid['filename']))
                    return false;
            }
            
            $q = $this->Con->prepare("DELETE FROM videos WHERE vID = :id");
            $q->bindValue(':id', $id);
            
            if($q->execute())
                return false;
            
            return true;
        }
    }
?>
