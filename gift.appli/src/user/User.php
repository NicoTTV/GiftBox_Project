<?php

namespace gift\app\user;

class User
{
    public $id;
    public $email;
    public $password;
    public $nom;
    public $prenom;
    public $pseudo;

    public function __construct($id, $email, $password, $nom, $prenom, $pseudo="") {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->pseudo = $pseudo;
    }
}