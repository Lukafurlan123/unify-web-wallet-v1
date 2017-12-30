<?php

namespace unify\modules;

use unify\Configuration;
use unify\Core;
use unify\rpc\Sender;

/**
 * Class    Transactions
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Transactions {

    /**
     * Gets transaction for member. It gets member
     * object and from it user auth which is used
     * to authenticate user inside rcp server.
     * Once authentication is completed rcp server
     * returns members transaction list
     *
     * @param $database
     * @return mixed
     */
    public static function getTransactions($database)
    {
        $rcp = new Sender(Configuration::RPC_HOST, Configuration::RPC_PORT, Configuration::RPC_USERNAME, Configuration::RPC_PASSWORD);
        $member = Core::getUserObject($database);
        return $rcp->getTransactionList($member->user_auth);
    }

}