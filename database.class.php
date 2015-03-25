<?php
    class db
    {
        private $dbname = '';
        private $user = '';
        private $pwd = '';
        private $Con;
        
        public function __construct($dbname, $user, $pass) {
            $this->dbname = $dbname;
            $this->user = $user;
            $this->pwd = $pass;
        }
        
        // Verbindung mit der Datenbank herstellen
        public function Connect()
        {
            // Versuchen sich mit vorhandener Datenbank zu verbinden
            $con = new PDO("mysql:host=localhost", $this->user, $this->pwd);
            $this->Con = $con;
            
            // Prüfen ob Datenbank erstellung geklappt hat
            if(!$this->CreateDatabase())
            {
               $connection = new PDO("mysql:host=localhost; dbname=" . $this->dbname, $this->user, $this->pwd);
               if (!$connection)
                   return false; 
               
               $this->Con = $connection;
            }
            
            return true;
        }
        
        // DB Verbindung holen
        public function GetInstance()
        {
           return $this->Con; 
        }
        
        // Datenbank erstellen
        // Falls nicht klappt => Fehler
        private function CreateDatabase()
        {
            if(!$this->Con->exec("CREATE DATABASE $this->dbname"))
                return false;
            
            $this->Con->exec("USE $this->dbname");
             
            return true;
        }
        
        // Tabellen erstellen
        public function CreateTables()
        {
            $this->Con->exec("CREATE TABLE users (uID int AUTO_INCREMENT, username varchar(100), email varchar(200), passwd varchar(100), PRIMARY KEY(uID))");
            $this->Con->exec("CREATE TABLE videos (vID int AUTO_INCREMENT, title varchar(100), description text, filename varchar(100), youtube int, PRIMARY KEY(vID))");
            
            return true;
        }
    }
?>
