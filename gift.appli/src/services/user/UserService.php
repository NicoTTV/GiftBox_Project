<?php

namespace gift\app\services\user;

use Exception;
use gift\app\models\User;
use gift\app\services\exceptions\BadDataUserException;
use gift\app\services\exceptions\UserNotFoundException;
use gift\app\services\exceptions\UserRegisterException;
use Slim\Exception\HttpInternalServerErrorException;

class UserService
{
    /**
     * @param string $email
     * @return array
     * @throws BadDataUserException
     * @throws UserNotFoundException
     */
    public function getUserByLogin(string $email):array
    {

        if (empty($email))
            throw new BadDataUserException("Bad data user : empty");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new BadDataUserException("Bad data user : not a valid email");

        if ($email !== filter_var($email, FILTER_SANITIZE_EMAIL))
            throw new BadDataUserException("Bad data user : not a sanitize email");

        try {
            return User::where('email', '=', $email)->get()->toArray();
        }catch (Exception $e){
            throw new UserNotFoundException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws BadDataUserException
     * @throws UserNotFoundException
     */
    public function connexion(array $data): bool
    {
        if (!isset($data['password']) || !isset($data['email']))
            throw new BadDataUserException("Bad data user : empty");

        if ($data['password'] !== filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BadDataUserException("Bad data user : not a sanitize email");

        $user = $this->getUserByLogin($data['email']);
        if (empty($user))
            throw new UserNotFoundException("User not found");

        if (!password_verify($data['password'], $user[0]['password']))
            throw new BadDataUserException("Bad data user : password not valid");

        $_SESSION['user'] = serialize($user);
        return true;
    }


    /**
     * @param array $data
     * @return bool
     * @throws BadDataUserException
     * @throws UserNotFoundException
     * @throws UserRegisterException
     */
    public function inscription(array $data): bool
    {
        if (!isset($data['password']) || !isset($data['email']) || !isset($data['passwordVerify']) || !isset($data['pseudo']))
            throw new BadDataUserException("Bad data user : empty");

        if ($data['email'] !== filter_var($data['email'], FILTER_SANITIZE_EMAIL))
            throw new BadDataUserException("Bad data user : not a sanitize email");

        if ($data['pseudo'] !== filter_var($data['pseudo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BadDataUserException("Bad data user : not a sanitize pseudo");

        $user = $this->getUserByLogin($data['email']);
        if (!empty($user))
            throw new BadDataUserException("Bad data user : email already exist");

        if (strlen($data['password']) < 8)
            throw new BadDataUserException("Bad data user : password too short");

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/', $data['password']))
            throw new BadDataUserException("Bad data user : password not valid");

        if ($data['password'] !== $data['passwordVerify'])
            throw new BadDataUserException("Bad data user : password don't match");


        $user = new User();
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->pseudo = $data['pseudo'];
        try {
            $user->saveOrFail();
        } catch (\Throwable $e) {
            throw new UserRegisterException("Error when register user");
        }
        $_SESSION['user'] = serialize($user);
        return true;
    }
}