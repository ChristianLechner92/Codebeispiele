<?php
    class user
    {
        private $Con;
        
        public function __construct($con) {
            $this->Con = $con;
        }
        
        // Erstellen des ersten User Accounts
        // $username = Username
        // $passwd = Passwort
        // $email = Email
        public function CreateUser($username, $passwd, $email)
        {
            $q = $this->Con->prepare("INSERT INTO users (username, email, passwd) VALUES (:user, :mail, :pwd)");
            $q->bindValue(':user', $username);
            $q->bindValue(':mail', $email);
            $q->bindValue(':pwd', md5($passwd));
            
            if(!$q->execute())
                return false;
            
            return true;
        }
    }
?>
