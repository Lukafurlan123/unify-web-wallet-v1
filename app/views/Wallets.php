<?php

namespace unify\views;

use unify\Template;

/**
 * Class    Wallets
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Wallets extends Template {


    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - wallets";
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
        return "addresses";
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
        <div id="loadWallets">
        
        </div>
        <button id="addWallet" class="button-orange">Add New Address</button>
        <br><br>
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
        return "custom/wallet.js";
    }
}