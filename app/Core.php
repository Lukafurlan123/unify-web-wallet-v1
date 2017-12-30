<?php
namespace unify;

use unify\database\Flags;

/**
 * Class    Core
 * @package unify
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Core
{

    /**
     * Gets user ip. If cloudflare is enabled it
     * will surpass cloudflare firewall and
     * get correct ip address
     *
     * @return mixed
     */
    public static function getUserIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * checks if string is alphanumeric
     *
     * @param $string
     * @return int
     */
    public static function isAlphaNumeric($string)
    {
        return preg_match('/^[a-z0-9 .\-]+$/i', $string);
    }

    /**
     * generates random string that is 15 characters
     * logn
     *
     * @return string
     */
    public static function randomLongStringGenerate()
    {
        return substr(md5(microtime()), 0, 15);
    }

    /**
     * Generates session hash using md5 encryption, members
     * username and current time. This session hash is
     * always unique and cannot be replicated by another
     * member
     *
     * @param $username
     * @return string
     */
    public static function generateSessionHash($username)
    {
        return md5($username . time());
    }

    /**
     * Checks if member is logged in. if both member and
     * member_name sessions are set it will check if they
     * match ones in database
     *
     * @param $database
     * @return bool
     */
    public static function authenticateUser($database)
    {
        if (isset($_SESSION['member'], $_SESSION['member_name'])) {
            if ($database->buildQuery("SELECT", [
                    "table_name" => "members",
                    "columns" => "COUNT(*) AS count",
                    "where" => "username = :username AND session_hash = :sessionHash",
                    "prepare" => [":username" => $_SESSION['member_name'], ":sessionHash" => $_SESSION['member']],
                    "flag" => Flags::FETCH_ONE
                ])->count > 0) {
                return true;
            } else {
                session_destroy();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Destroys sessions and redirects member to
     * login page
     */
    public static function logout()
    {
        session_destroy();
        header('Location: ' . Configuration::LOGIN_PATH);
    }

    /**
     * Uses session to get user object with all
     * user information
     *
     * @param $database
     * @return mixed
     */
    public static function getUserObject($database)
    {
        return $database->buildQuery("SELECT", [
            "table_name" => "members",
            "columns" => "*",
            "where" => "username = :username",
            "prepare" => [":username" => $_SESSION['member_name']],
            "flag" => Flags::FETCH_ONE
        ]);
    }


}