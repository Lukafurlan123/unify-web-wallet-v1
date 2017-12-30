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
		return $this->jsonRPC->getbalance("zelles(" . $userAuth . ")", 6);
	}

	function getInfo()
	{
		return $this->jsonRPC->getinfo();
	}

    function getAddress($userAuth)
    {
		return $this->jsonRPC->getaccountaddress("zelles(" . $userAuth . ")");
	}

	function getAddressList($userAuth)
	{
		return $this->jsonRPC->getaddressesbyaccount("zelles(" . $userAuth . ")");
	}

	function getTransactionList($userAuth)
	{
		return $this->jsonRPC->listtransactions("zelles(" . $userAuth . ")", 10);
	}

	function getNewAddress($userAuth)
	{
		return $this->jsonRPC->getnewaddress("zelles(" . $userAuth . ")");
	}

	function withdraw($userAuth, $address, $amount)
	{
		return $this->jsonRPC->sendfrom("zelles(" . $userAuth . ")", $address, (float)$amount, 6);
	}
}
?>
