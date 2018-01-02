<?php
/**
 * Created by PhpStorm.
 * User: luka
 * Date: 29/12/2017
 * Time: 18:47
 */
namespace unify\views;

use unify\Template;

/**
 * Class    Send
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Send extends Template {

    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - Send";
    }

    /**
     * Represents active navigation link.
     * It can be set to "home", "send",
     * "history", "settings", "addresses",
     * "login", "register"
     *
     * @return string
     */
    public function active()
    {
        return "send";
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
        <form class="form-signin" id="send_form">
            <h3 class="form-signin-heading">Send Unify</h3>
            <input type="text" name="wallet_address" id="wallet_address" class="form-control login-form" placeholder="Wallet address" required autofocus>
            <br>
            <input type="number" name="amount" id="amount" step=any class="form-control login-form" placeholder="Amount" required>
            <br>
            <button class="button-orange" type="submit">Send</button>
        </form>
        <br>
        <div id="errorContainer" class="login-form">
        
        </div>
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
        return "custom/send.js";
    }
}