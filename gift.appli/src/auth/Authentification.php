<?php

namespace gift\app\auth;

use gift\app\db\ConnectionFactory;
use gift\app\user\User;

class Authentification
{
    public static function authenticate($email, $password)
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE email = ?');

        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(\PDO::\FETCH_ASSOC);
            if ($user && $password === $user['mdp']) {
                $utilisateur = new User($user['id'], $user['email'], $user['password'], $user['nom'], $user['prenom']);
                $_SESSION['user'] = serialize($utilisateur);
                return $utilisateur;
            }
        }
        return null;
    }
}