<?php

namespace unify\modules;

use unify\Configuration;
use unify\Core;
use unify\rpc\Sender;

/**
 * Class    Send
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Send {

    /**
     * Uses rcp client to send unify from your
     * wallet to another wallet.
     *
     * @param $database
     * @param $amount
     * @param $wallet
     */
    public function sendUnify($database, $wallet, $amount)
    {
        /*
         * Checks if member is logged in since api
         * itself doesn't have session check inside
         * router
         */
        if(!Core::authenticateUser($database)) {
            echo json_encode([
                "type"    => "error",
                "message" => "Please sign in before doing this."
            ]);
            return;
        }

        /*
         * Creates new rcp sender object with authentication
         * params as well as host and port which are all
         * taken from configuration enum
         */
        $rcp    = new Sender(Configuration::RPC_HOST, Configuration::RPC_PORT, Configuration::RPC_USERNAME, Configuration::RPC_PASSWORD);

        /*
         * Uses session to get user object with all
         * user information
         */
        $member = Core::getUserObject($database);

        /*
         * Checks if amount is smaller or equal to 0
         */
        if($amount <= 0) {
            echo json_encode([
                "type"    => "error",
                "message" => "You can only send amount of coins that is bigger then 0."
            ]);
            return;
        }

        /*
         * Checks if wallet balance is big enough for transaction.
         * If its smaller then amount you want to send it will
         * throw json encoded error which will later be read by
         * javascript and displayed as alert box
         */
        if($rcp->getBalance($member->user_auth) < $amount) {
            echo json_encode([
                "type"    => "error",
                "message" => "You don't have enough Unify coins."
            ]);
            return;
        }

        /*
         * Sends command to rcp client which will execute
         * transaction on rcp server
         */
        $rcp->withdraw($member->user_auth, $wallet, $amount);

        /*
         * when sending is completed it echoes json encoded success
         * message which will be read by json and displayed as success box
         */
        echo json_encode([
            "type"    => "success",
            "message" => "You have successfully sent " . $amount . " to " . $wallet
        ]);
    }

}