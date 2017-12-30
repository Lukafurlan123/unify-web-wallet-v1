<?php

namespace unify\modules;

use unify\authenticator\GoogleAuthenticator;
use unify\Core;
use unify\database\Flags;

/**
 * Class    Authenticator
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Authenticator {

    /**
     * Variable which holds database object
     *
     * @var
     */
    protected $database;

    /**
     * Authenticator constructor. It sets param database
     * to protected variable
     *
     * @param $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Switches authenticator between enabled and
     * disabled. If parameter type is 0 it will
     * enable authenticator and if its 1 it will
     * disable it
     *
     * @param $type
     */
    public function switchAuthenticator($type)
    {
        /*
         * Checks if member is logged in since api
         * itself doesn't have session check inside
         * router
         */
        if(!Core::authenticateUser($this->database)) {
            echo json_encode([
                "type"    => "error",
                "message" => "You need to log in before enabling authenticator."
            ]);
            return;
        }

        /*
         * Uses session to get user object with all
         * user information
         */
        $user = Core::getUserObject($this->database);

        /*
         * Executes query that updates authenticator_enabled
         * field to 0 if you are disabling it and to 1 if
         * you're enabling it
         */
        $this->database->buildQuery("UPDATE", [
            "table_name" => "members",
            "where"      => "username = :username",
            "columns"    => ["authenticator_enabled"],
            "prepare"    => [":username" => $user->username, ":authenticator_enabled" => $type]
        ]);

        /*
         * Simple if statement which set variable a to enabled
         * or disabled. this is used for return message
         */
        $a = $type == 1 ? "enabled" : "disabled";

        /*
         * If authenticator switch is successful it echoes json
         * encoded message which is read by javascript and
         * displayed in DOM as an alert
         */
        echo json_encode([
            "type" => "success",
            "message" => "You have successfully " . $a . " google authenticator."
        ]);
    }

    /**
     * Checks if authenticator is enabled by
     * executing select query for given username.
     * returns true if enabled and false if disabled
     *
     * @param $username
     * @return bool
     */
    public function isEnabled($username)
    {
        if($this->database->buildQuery("SELECT", array(
                "table_name" => "members",
                "columns"    => "authenticator_enabled",
                "where"      => "username = :username",
                "prepare"    => array(":username" => $username),
                "flag"       => Flags::FETCH_ONE
            ))->authenticator_enabled == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if entered google authenticator code
     * correct. returns true if it is and false if
     * it is not.
     *
     * @param $secret
     * @param $code
     * @return bool
     */
    public static function authenticate($secret, $code)
    {
        $authenticator = new GoogleAuthenticator();
        if($authenticator->getCode($secret) == $code) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets google authenticator QR code url by using
     * authenticator_secret which is generated on
     * member registration. It selects it from database
     * with given parameter username and converts to url.
     *
     * @param $username
     * @return string
     */
    public function getQRCodeURL($username)
    {
        $authenticator = new GoogleAuthenticator();
        return $authenticator->getQRCodeGoogleUrl("Unify web wallet", $this->database->buildQuery("SELECT", array(
            "table_name" => "members",
            "columns"    => "authenticator_secret",
            "where"      => "username = :username",
            "prepare"    => array(":username" => $username),
            "flag"       => Flags::FETCH_ONE
        ))->authenticator_secret);
    }

}