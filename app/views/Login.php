<?php

namespace unify\views;

use unify\Template;

/**
 * Class     Login
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Login extends Template {

    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - Login";
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
        return "login";
    }

    /**
     * This will contain location to custom javascript
     * file which will be loaded in footer of website
     *
     * @return mixed
     */
    public function customScript()
    {
        return "custom/login.js";
    }

    /**
     * Represents main content of website
     * it can be set to anything
     */
    public function body()
    {
        echo '
        <form class="form-signin" id="login_form">
            <h3 class="form-signin-heading">Please sign in to continue</h3>
            <input type="text" name="username" id="username" class="form-control login-form" placeholder="Username" required autofocus>
            <br>
            <input type="password" name="password" id="password" class="form-control login-form" placeholder="Password" required>
            <br>
            <input type="text" name="twoFactor" id="twoFactor" class="form-control login-form" placeholder="2FA Code (optional)">
            <br>
            <button class="button-orange" type="submit">Sign in</button>
        </form>
        <br>
        <div id="errorContainer" class="login-form">
        
        </div>
        ';
    }

}