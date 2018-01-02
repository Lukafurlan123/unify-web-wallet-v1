<?php

namespace unify\rpc;

class Sender {

	private $uri;

	private $jsonRPC;

	function __construct($host, $port, $user, $pass)
	{
		$this->uri = "http://" . $user . ":" . $pass . "@" . $host . ":" . $port . "/";
		$this->jsonRPC = new RPCClient($this->uri);
	}

	function getBalance($userAuth)
	{
		return $this->jsonRPC->getbalance($userAuth, 6);
	}

	function getInfo()
	{
		return $this->jsonRPC->getinfo();
	}

    function getAddress($userAuth)
    {
		return $this->jsonRPC->getaccountaddress($userAuth);
	}

	function getAddressList($userAuth)
	{
		return $this->jsonRPC->getaddressesbyaccount($userAuth);
	}

	function getTransactionList($userAuth)
	{
		return $this->jsonRPC->listtransactions($userAuth, 10);
	}

	function getNewAddress($userAuth)
	{
		return $this->jsonRPC->getnewaddress($userAuth);
	}

	function withdraw($userAuth, $address, $amount)
	{
		return $this->jsonRPC->sendfrom($userAuth, $address, (float)$amount, 6);
	}

	function getPrivateKey($address)
	{
		return $this->jsonRPC->dumpprivkey($address);
	}
}
?>
