<?php

    class user
    {
        private $Con;
        public function __construct($con) 
        {
            $this->Con = $con;
        }
        
        // Überprüft die eingebenen Logindaten
        // $email = Email des Users
        // $pwd = Passwort des Users
        public function CheckLogin($email, $pwd)
        {
            $q = $this->Con->prepare("SELECT uID, username, email, passwd FROM users WHERE email = :email AND passwd = :pwd");
            $q->bindValue(':email', $email);
            $q->bindValue(':pwd', $pwd);
            $q->execute();
            
            // Wenn kein Eintrag gefunden wurde => Fehler
            if ($q->rowCount() != 1)
                return false;
            
            $user = $q->fetch();
            // Session setzen
            $_SESSION['uID'] = $user['uID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            
            return true;
        }
        
        // Ausloggen des Users
        public function Logout()
        {
            unset($_SESSION['uID']);
            unset($_SESSION['username']);
            unset($_SESSION['email']);
        }
        
        // User löschen
        // $id = ID aus der Datenbank
        public function DeleteUser($id)
        {
            $q = $this->Con->prepare("DELETE FROM users WHERE uID = :id");
            $q->bindValue(':id', $id);
            
            if(!$q->execute())
                return false;
            
            return true;
        }
        
        // User bearbeiten
        // $id = ID aus der Datenbank
        // $passwd = neues Passwort
        // $email = neue Email
        public function EditUser($id, $passwd, $email)
        {
            if(empty($email))
                return false;
            
            // Wurde kein Passwort eingetragen, wird es auch nicht geändert
            if(empty($passwd))
            {
                $q = $this->Con->prepare("UPDATE users SET email = :email WHERE uID = :id");
                $q->bindValue(':email', $email);
                $q->bindValue(':id', $id);
            }
            else
            {
                $q = $this->Con->prepare("UPDATE users SET passwd = :pw, email = :email WHERE uID = :id");
                $q->bindValue(':pw', md5($passwd));
                $q->bindValue(':email', $email);
                $q->bindValue(':id', $id);   
            }
            
            if(!$q->execute())
                return false;
            
            return true;
        }
        
        // Hole Daten zu einem Account aus der Datenbank
        // $id = ID aus der Datenbank
        public function GetUserByID($id)
        {
            $q = $this->Con->prepare("SELECT * FROM users WHERE uID = :id");
            $q->bindValue(':id', $id);
            $q->execute();
            
            return $u = $q->fetch();
        }
        
        // Methode zum Prüfen, ob der Username noch verfügbar ist
        // $name = Username
        private function CheckUsername($name)
        {
            $q = $this->Con->prepare("SELECT username FROM users WHERE username = :user");
            $q->bindValue(':user', $name);
            $q->execute();
            
            if($q->rowCount() > 0)
                return false;
            
            return true;
        }
        
        // Methode zum Registrieren eines Accounts
        // $username = Username des Accounts
        // $email = Email des Accounts
        // $pwd = Passwort des Accounts
        public function RegisterUser($username, $email, $pwd)
        {
            if(empty($username) || empty($email) || empty($pwd))
                return false;
            
            // Prüfen ob der Name noch verfügbar ist
            if(!$this->CheckUsername($username))
                return false;
            
            $q = $this->Con->prepare("INSERT INTO users (username, email, passwd) VALUES (:user, :email, :pwd)");
            $q->bindValue(':user', $username);
            $q->bindValue(':email', $email);
            $q->bindValue(':pwd', md5($pwd));
            $q->execute();
            
            return true;
        }
    }
