<?php

namespace unify\views;

use unify\Configuration;
use unify\Core;
use unify\rpc\Sender;
use unify\Template;

/**
 * Class    Dashboard
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Dashboard extends Template {


    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - Dashboard";
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
       return "home";
    }

    /**
     * Represents main content of website
     * it can be set to anything
     */
    public function body()
    {
        $member  = Core::getUserObject($this->database);
        $rcp     = new Sender(Configuration::RPC_HOST, Configuration::RPC_PORT, Configuration::RPC_USERNAME, Configuration::RPC_PASSWORD);
        $balance = $rcp->getBalance($member->user_auth);

        echo '
        <span class="text-title">Welcome to your wallet <b>'.$_SESSION['member_name'].'</b>.</span> <br>We have prepared some statistics for you. You can view them down bellow.
        <br><br>
        <b>Amount of coins in wallet: </b> '.$balance.'
        <br>
        <b>Wallet value in dollars: </b> $'.$balance*$this->unifyPrice.'
        <br>
        <b>Primary wallet address: </b> '.$rcp->getAddress($member->user_auth).'
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
        return;
    }
}