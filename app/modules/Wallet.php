<?php

namespace unify\modules;

use unify\Configuration;
use unify\Core;
use unify\rpc\Sender;

/**
 * Class    Wallet
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Wallet {

    /**
     * Private variable that holds rcp
     * client object
     *
     * @var Sender
     */
    private $rcp;

    /**
     * Wallet constructor which creates rcp object
     * and sets it to private rcp variable
     */
    public function __construct()
    {
        $this->rcp = new Sender(Configuration::RPC_HOST, Configuration::RPC_PORT, Configuration::RPC_USERNAME, Configuration::RPC_PASSWORD);
    }

    /**
     * Gets all wallet addresses for member. It gets member
     * object and from it user auth which is used
     * to authenticate user inside rcp server.
     * Once authentication is completed rcp server
     * returns array containing all members addresses
     *
     * @param $database
     * @return mixed
     */
    public function getWallets($database)
    {
        $member = Core::getUserObject($database);
        return $this->rcp->getAddressList($member->user_auth);
    }

    /**
     * Gets primary wallet address. It gets member
     * object and from it user auth which is used
     * to authenticate user inside rcp server.
     * Once authentication is completed rcp server
     * returns primary wallet address
     *
     * @param $database
     * @return mixed
     */
    public function getWallet($database)
    {
        $member = Core::getUserObject($database);
        return $this->rcp->getAddress($member->user_auth);
    }

    /**
     * Adds new wallet address to member account.
     * It gets member object and from it user auth
     * which is used to authenticate user inside rcp
     * server. Once authentication is completed rcp
     * server creates new wallet.
     *
     *
     * @param $database
     */
    public function addWallet($database)
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
         * Uses session to get user object with all
         * user information
         */
        $member = Core::getUserObject($database);

        /*
         * Checks if member already had maximum amount of
         * addresses added.
         */
        if(count($this->rcp->getAddressList($member->user_auth)) >= 10) {
            echo json_encode([
                "type"    => "error",
                "message" => "You have already reached maximum address limit."
            ]);
            return;
        }

        /*
         * Creates new wallet address by using rcp client
         * which sends command to rcp server
         */
        $this->rcp->getNewAddress($member->user_auth);

        /*
         * When new address is created it echoes json
         * encoded success message which will be read by
         * json and displayed as success box
         */
        echo json_encode([
            "type"    => "success",
            "message" => "You have successfully created new address."
        ]);
    }

    /**
     * Gets wallet private key from given
     * wallet address
     *
     * @param $address
     */
    public function getPrivateKey($address)
    {
        return $this->rcp->getPrivateKey($address);
    }

}