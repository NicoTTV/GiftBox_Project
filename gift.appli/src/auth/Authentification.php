<?php

namespace gift\app\auth;

use gift\app\db\ConnectionFactory;

class Authentification
{
    public static function authenticate($email, $password)
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE email = ?');

        if ($stmt->execute([$email])) {
            $user = $stmt->fetch();
        }
    }
}