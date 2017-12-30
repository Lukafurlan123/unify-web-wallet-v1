<?php
namespace unify\modules;

use unify\authenticator\GoogleAuthenticator;
use unify\Configuration;
use unify\Core;
use unify\database\Flags;
use unify\rpc\Sender;

/**
 * Class    Registration
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Registration {

    /**
     * Variable which holds database object
     *
     * @var
     */
    protected $database;

    /**
     * Registration constructor.  It sets param database
     * to protected variable
     *
     * @param $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     *
     *
     * @param $username
     * @param $password
     * @param $confirmPassword
     */
    public function handleRegistration($username, $password, $confirmPassword)
    {
        if(!Core::isAlphaNumeric($username)) {
            echo json_encode([
                "type"    => "error",
                "message" => "You can only use letters and numbers in your username."
            ]);
            return;
        }

        if(strlen($username) < 4) {
            echo json_encode([
                "type"    => "error",
                "message" => "Your username must be at least 4 characters long."
            ]);
            return;
        }

        if($password != $confirmPassword) {
            echo json_encode([
                "type"    => "error",
                "message" => "Passwords entered do not match."
            ]);
            return;
        }

        if(strlen($password) < 8) {
            echo json_encode([
                "type"    => "error",
                "message" => "Your password must be at least 8 characters long."
            ]);
            return;
        }

        $userAuth = $this->generateUserAuth($this->database);

        if($this->database->buildQuery("SELECT", [
            "table_name" => "members",
            "columns"    => "COUNT(*) AS count",
            "where"      => "username = :username",
            "prepare"    => [":username" => $username],
            "flag"       => Flags::FETCH_ONE
        ])->count > 0) {
            echo json_encode([
                "type"    => "error",
                "message" => "This username is already registered in our database."
            ]);
            return;
        }

        $authenticator = new GoogleAuthenticator();

        $rcp = new Sender(Configuration::RPC_HOST, Configuration::RPC_PORT, Configuration::RPC_USERNAME, Configuration::RPC_PASSWORD);

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sessionHash = Core::generateSessionHash($username);

        $this->database->buildQuery("INSERT", [
            "table_name" => "members",
            "columns"    => ["username"             => $username,
                             "password"             => $password,
                             "user_auth"            => $userAuth,
                             "ip_address"           => Core::getUserIP(),
                             "session_hash"         => $sessionHash,
                             "authenticator_secret" => $authenticator->createSecret()]
        ]);

        $rcp->getNewAddress($userAuth);

        $_SESSION['member']      = $sessionHash;
        $_SESSION['member_name'] = $username;

        echo json_encode([
            "type"    => "success",
            "message" => "You have successfully registered."
        ]);

    }

    private function generateUserAuth($database)
    {
        $userAuth = Core::randomLongStringGenerate();

        if($this->database->buildQuery("SELECT", [
            "table_name" => "members",
            "columns"    => "COUNT(*) AS count",
            "where"      => "user_auth = :auth",
            "prepare"    => [":auth" => $userAuth],
            "flag"       => Flags::FETCH_ONE
        ])->count > 0) {
            return $this->generateUserAuth($database);
        } else {
            return $userAuth;
        }
    }

}