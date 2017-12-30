<?php
/**
 * Created by PhpStorm.
 * User: luka
 * Date: 27/12/2017
 * Time: 01:31
 */

namespace unify\views;

use unify\modules\Authenticator;

/**
 * Class    Settings
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Settings extends \unify\Template {

    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - settings";
    }

    /**
     * Represents active navigation link.
     * It can be set to "home", "send",
     * "history", "settings", "addresses"
     *
     * @return string
     */
    public function active()
    {
        return "settings";
    }

    /**
     * Represents main content of website
     * it can be set to anything
     *
     * @return mixed
     */
    public function body()
    {
        echo '
        <form class="form-signin" id="change_password_form">
            <h3 class="form-signin-heading">Change password</h3>
            <input type="password" name="password" id="password" class="form-control login-form" placeholder="Old password" required>
            <br>
            <input type="password" name="newPassword" id="newPassword" class="form-control login-form" placeholder="New Password" required>
            <br>
            <button class="button-orange" type="submit">Change password</button>
        </form>
        <br>
        <div id="errorContainer" class="login-form">
        
        </div>
        <br>
        <h3 class="form-signin-heading">Authenticator</h3>';
        $authenticator = new Authenticator($this->database);
        if($authenticator->isEnabled($_SESSION['member_name'])) {
            echo '<button class="button-orange" id="switchAuthenticator" data-state="enabled" type="submit"><span id="buttonText">Disable authenticator</span></button>';
        } else {
            echo '<button class="button-orange" id="switchAuthenticator" data-state="disabled" type="submit"><span id="buttonText">Enable authenticator</span></button>';
        }
        echo '
        <br><br>
        Please scan this QR code with <b>Google Authenticator</b> before enabling authenticator.
        <br><br>
        ';
        $QRCodeURL = $authenticator->getQRCodeURL($_SESSION['member_name']);
        echo '
        <img src="'.$QRCodeURL.'" alt="QR Code">
        ';
    }

    /**
     * This will contain location to custom javascript
     * file which will be loaded in footer of website
     *
     * @return mixed
     */
    public function customScript()
    {
        return "custom/settings.js";
    }

}