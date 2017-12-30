<?php
namespace unify;

/**
 * Class    Configuration
 * @package unify
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Configuration {

    /**
     * Path to login page
     */
    const LOGIN_PATH = "login";

    /**
     * Path to home page
     */
    const HOME_PATH = "login";

    /**
     * Location of unify api. It shows current unify
     * price in dollars, sats and a lot more information
     */
    const API_LOCATION = "https://api.coinmarketcap.com/v1/ticker/unify/";

    /**
     * path to dashboard page
     */
    const DASHBOARD_URL = "dashboard";

    /**
     * MYSQL DETAILS
     */

    const MYSQL_HOST = "localhost";

    const MYSQL_DATABASE = "wallet";

    const MYSQL_USERNAME = "root";

    const MYSQL_PASSWORD = "root";

    const MYSQL_PREFIX = "";

    /**
     * RPC DETAILS
     */

    const RPC_HOST = "127.0.0.1";

    const RPC_USERNAME = "root";

    const RPC_PASSWORD = "root";

    const RPC_PORT = "3333";

}