<?php

namespace unify\modules;

use unify\Core;
use unify\database\Flags;

/**
 * Class    Login
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Login {

    /**
     * Variable which holds database object
     *
     * @var
     */
    protected $database;

    /**
     * Login constructor. It sets param database
     * to protected variable
     *
     * @param $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Handles information that was entered in form
     * and processes it. If all information entered is
     * correct, sessions will be set and member will
     * appear as logged in. He will also be redirected to
     * dashboard
     *
     * @param $username
     * @param $password
     * @param null $twoFactor
     */
    public function handleLogin($username, $password, $twoFactor = null)
    {
        /*
         * Selects member object by using username
         * parameter
         */
        $user = $this->database->buildQuery("SELECT", [
            "table_name" => "members",
            "columns"    => "COUNT(*) AS count ,password, authenticator_secret AS secret, authenticator_enabled",
            "where"      => "username = :username",
            "prepare"    => [":username" => $username],
            "flag"       => Flags::FETCH_ONE
        ]);

        /*
         * If result count is 0 then it will throw json
         * encoded error which will be read by javascript.
         * 0 means that no members under that username
         * were found in the database
         */
        if($user->count == 0) {
            echo json_encode([
                "type"    => "error",
                "message" => "Entered username is incorrect"
            ]);
            return;
        }

        /*
         * If member has google authenticator enabled it will
         * use members authenticator_secret and entered two
         * factor code to check if code entered is correct.
         * if its not it will throw json encoded error which
         * will be read by javascript and displayed as alert box
         */
        if($user->authenticator_enabled == 1) {
            if (!Authenticator::authenticate($user->secret, $twoFactor)) {
                echo json_encode([
                    "type" => "error",
                    "message" => "Two factor authenticator code is invalid."
                ]);
                return;
            }
        }

        /*
         * Checks if entered password matches one in database
         * but because password in database is hashed using
         * password_hash function it has to use password_verify
         * function to verify it. if passwords do not match
         * it will throw json encoded error which will be read
         * by javascript and displayed as alert box
         */
        if(!password_verify($password, $user->password)) {
            echo json_encode([
                "type" => "error",
                "message" => "Entered password is incorrect."
            ]);
            return;
        }

        /*
         * Generates session hash which is unique string
         * made from random chars. It is used to verify
         * login session.
         */
        $sessionHash = Core::generateSessionHash($username);

        /*
         * Updates member in database with new session
         * hash. If someone else is logged in it will log
         * him out because he will not be able to authenticate
         * his session with new session hash in database.
         */
        $this->database->buildQuery("UPDATE", [
            "table_name" => "members",
            "where"      => "username = :username",
            "columns"    => ["session_hash"],
            "prepare"    => [":username" => $username, ":session_hash" => $sessionHash]
        ]);

        /*
         * Sets session called member with sessionHash variable
         */
        $_SESSION['member'] = $sessionHash;
        /*
         * Sets session called member with username variable
         */
        $_SESSION['member_name'] = $username;

        /*
         * If login is successful it echoes json encoded success
         * message which will be read by json and displayed as success box
         */
        echo json_encode([
            "type" => "success",
            "message" => "You have sucessfully logged in."
        ]);
        return;

    }

}