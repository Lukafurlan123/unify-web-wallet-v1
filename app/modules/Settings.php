<?php

namespace unify\modules;

use unify\Core;
use unify\database\Flags;

/**
 * Class    Settings
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Settings {

    /**
     * Variable which holds database object
     *
     * @var
     */
    protected $database;

    /**
     * Settings constructor. It sets param database
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
     * and processes it. If old password is right and
     * new password meets requirements it will be changed
     *
     * @param $oldPassword
     * @param $newPassword
     */
    public function handlePasswordChange($oldPassword, $newPassword)
    {
        /*
         * Checks if member is logged in since api
         * itself doesn't have session check inside
         * router
         */
        if(!Core::authenticateUser($this->database)) {
            echo json_encode([
                "type"    => "error",
                "message" => "Please sign in before doing this."
            ]);
            return;
        }

        /*
         * Selects password from member database by using
         * member username which is set in session
         */
        $user = $this->database->buildQuery("SELECT", [
            "table_name" => "members",
            "columns"    => "password",
            "where"      => "username = :username",
            "prepare"    => [":username" => $_SESSION['member_name']],
            "flag"       => Flags::FETCH_ONE
        ]);

        /*
         * Checks if entered password matches one in database
         * but because password in database is hashed using
         * password_hash function it has to use password_verify
         * function to verify it. if passwords do not match
         * it will throw json encoded error which will be read
         * by javascript and displayed as alert box
         */
        if(!password_verify($oldPassword, $user->password)) {
            echo json_encode([
                "type" => "error",
                "message" => "Current password is incorrect."
            ]);
            return;
        }

        /*
         * Checks if newly entered password is atleast
         * 8 characters long. if its not it will throw json
         * encoded error which will be read by javascript
         * and displayed as alert box
         */
        if(strlen($newPassword) < 8) {
            echo json_encode([
                "type"    => "error",
                "message" => "Your new password must be at least 8 characters long."
            ]);
            return;
        }

        /*
         * Updates member in database with new password which
         * is hashed using password_hash function
         */
        $this->database->buildQuery("UPDATE", [
            "table_name" => "members",
            "where"      => "username = :username",
            "columns"    => ["password"],
            "prepare"    => [":username" => $_SESSION['member_name'], ":password" => password_hash($newPassword, PASSWORD_DEFAULT)]
        ]);

        /*
         * If password change is successful it will echo json
         * encoded success message which will be read by
         * json and displayed as success box
         */
        echo json_encode([
            "type"    => "success",
            "message" => "You have successfully changed your password."
        ]);
    }

}