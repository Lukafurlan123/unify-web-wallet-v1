<?php

namespace unify\views;

use unify\Template;

/**
 * Class    Register
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Register extends Template {

    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - Register";
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
        return "register";
    }

    /**
     * This will contain location to custom javascript
     * file which will be loaded in footer of website
     *
     * @return mixed
     */
    public function customScript()
    {
        return "custom/register.js";
    }

    /**
     * Represents main content of website
     * it can be set to anything
     */
    public function body()
    {
        echo '
        <form class="form-signin" id="registration_form">
            <h3 class="form-signin-heading">Create new account</h3>
            <input type="text" name="username" id="username" class="form-control login-form" placeholder="Username" required autofocus>
            <br>
            <input type="password" name="password" id="password" class="form-control login-form" placeholder="Password" required>
            <br>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control login-form" placeholder="Confirm password" required>
            <br>
            <button class="button-orange" type="submit">Register</button>
        </form>
        <br>
        <div id="errorContainer" class="login-form">
        
        </div>
        ';
    }

}