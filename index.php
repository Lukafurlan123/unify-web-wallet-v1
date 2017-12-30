<?php

use unify\modules\Authenticator;
use unify\modules\Registration;
use unify\modules\Settings;
use unify\Router;

/*
 * starts session
 */
session_start();

/*
 * requires autoloader which will load all
 * classes in project and make them available
 * via namespaces.
 */
require_once("vendor/autoload.php");

/*
 * Creates new router object
 */
$router = new Router();

/*
 * Registration page route which uses Register class
 * to display page.
 */
$router->route(["GET", "POST"], "register", false, function($request, $database) {
    new \unify\views\Register(true, $database);
});

/*
 * Login page route which uses login class to display
 * page.
 */
$router->route(["GET", "POST"], "login", false, function($response, $database) {
    new unify\views\Login(true, $database);
});

/*
 * Dashboard page route which uses dashboard class to
 * display page. Which is used to display basic information
 * about member
 */
$router->route(["GET", "POST"], "dashboard", true, function($response, $database) {
    new \unify\views\Dashboard(false, $database);
});

/*
 * Settings page route which uses settings class to
 * display page which is used for authenticator settings
 * and password settings
 */
$router->route(["GET", "POST"], "settings", true, function($response, $database) {
    new \unify\views\Settings(false, $database);
});

/*
 * Send page which uses send class to display page which
 * is used to send unify from one wallet to another
 */
$router->route(["GET", "POST"], "send", true, function($response, $database) {
    new \unify\views\Send();
});

/*
 * History page route which uses Transactions class
 * to display page which shows members transactions
 */
$router->route(["GET", "POST"], "history", true, function($response, $database) {
    new \unify\views\Transactions(false, $database);
});

/*
 * Addresses page route which uses Wallets class
 * to display page which shows all members addresses
 * and has ability to add new address
 */
$router->route(["GET", "POST"], "addresses", true, function($response, $database) {
    new \unify\views\Wallets();
});

/*
 * Logout page route which uses logout function in core
 * class to destroy user session and redirect him to login
 */
$router->route(["GET", "POST"], "logout", true, function($response, $database) {
    \unify\Core::logout();
});

/*
 * API route which is used to switch authenticator from
 * enabled to disabled and otherwise
 */
$router->route(["GET", "POST"], "api/settings/switchauthenticator/:type{i}", true, function($request, $database) {
    $authenticator = new Authenticator($database);
    $authenticator->switchAuthenticator($request->type);
});

/*
 * API route which is used to send unify from one wallet
 * from another
 */
$router->route(["GET", "POST"], "api/send/:wallet/:amount", false, function($request, $database) {
    if(isset($request->wallet, $request->amount)) {
        $send = new \unify\modules\Send();
        $send->sendUnify($database, $request->wallet, $request->amount);
    } else {
        echo json_encode([
            "type" => "error",
            "message" => "All information is required."
        ]);
    }
});

/*
 * API route which adds new wallet address
 */
$router->route(["GET", "POST"], "api/wallet/add", false, function($request, $database) {
    $wallet = new \unify\modules\Wallet();
    $wallet->addWallet($database);
});

/*
 * API route which gets all wallet addresses and
 * prints them out as html table
 */
$router->route(["GET", "POST"], "api/wallets/get", false, function($request, $database) {
    $wallet = new \unify\modules\Wallet();
    $wallets = $wallet->getWallets($database);

    echo '
    <table class="table table-bordered table-striped" id="alist">
        <thead>
            <tr>
                <td>Address:</td>
            </tr>
        </thead>
        <tbody id="loadWallets">
            <tr>
                <td>' . $wallet->getWallet($database) . '</td>
            </tr>
    ';

    foreach($wallets as $wallet) {
        echo '
        <tr>
            <td>' . $wallet . '</td>
        </tr>';
    }

    echo '
        </tbody>
    </table>
    ';
});

/*
 * API route which is used to login.
 */
$router->route(["GET", "POST"], "api/login/:username/:password/:twoFactor", false, function($request, $database) {
    $login = new unify\modules\Login($database);
    if(isset($request->twoFactor, $request->username, $request->password)) {
        $login->handleLogin($request->username, $request->password, $request->twoFactor);
    } else if(isset($request->username, $request->password)) {
        $login->handleLogin($request->username, $request->password);
    } else {
        echo json_encode([
            "type" => "error",
            "message" => "All information is required."
        ]);
    }
});

/*
 * API route which is used to register.
 */
$router->route(["GET", "POST"], "api/register/:username/:password/:confirmPassword", false, function($request, $database) {
    $login = new Registration($database);
    if(isset($request->confirmPassword, $request->username, $request->password)) {
        $login->handleRegistration($request->username, $request->password, $request->confirmPassword);
    } else {
        echo json_encode([
            "type" => "error",
            "message" => "All information is required."
        ]);
    }
});

/*
 * API route which is used to change password
 */
$router->route(["GET", "POST"], "api/settings/changepassword/:password/:newPassword", false, function($request, $database) {
    $settings = new Settings($database);
    if(isset($request->password, $request->newPassword)) {
        $settings->handlePasswordChange($request->password, $request->newPassword);
    } else {
        echo json_encode([
            "type" => "error",
            "message" => "All information is required."
        ]);
    }
});

/*
 * Runs router. Once route is recognised in url it will
 * run correct route which was set to router with
 * route function
 */
$router->run();