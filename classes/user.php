<?php
 class User {
    private $username;
    private $email;
    private $id;

    public function __construct($username, $email, $id) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }
    
    public function getID() {
        echo $this->id;
    }

    public function getUsername() {
        echo $this->username;
    }

    public function getEmail() {
        echo $this->email;
    }
    
 }
?>

