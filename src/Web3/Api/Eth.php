<?php

namespace Formaldehid\Web3\Api;

use Formaldehid\Web3;
use Formaldehid\Web3\Providers\Provider;
use Graze\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use phpseclib\Math\BigInteger;
use Formaldehid\Web3\Api\Eth\Contract;

class Eth implements Api
{

    protected $web3;

    protected $provider;

    public $defaultAccount;

    /**
     * Eth constructor.
     * @param Web3 $web3
     * @param Provider $provider
     */
    public function __construct(Web3 $web3, Provider $provider)
    {
        $this->web3 = $web3;
        $this->provider = $provider;
    }

    /**
     * @param $addressHexString
     * @param string $defaultBlock
     * @return BigInteger
     */
    public function getBalance($addressHexString, $defaultBlock = "latest") : BigInteger
    {
        $balance = $this->provider->request("eth_getBalance", [$addressHexString, $defaultBlock]);
        return new BigInteger($balance, 16);
    }

    public function sendTransaction(array $object) : string
    {
        $object["data"] = isset($object["data"]) ? $object["data"] : $this->web3->toHex(0);
        $object["from"] = isset($object["from"]) ? $object["from"] : $this->defaultAccount;
        if(isset($object["value"])){
            $object["value"] = $this->web3->toHex($object["value"]);
        }
        if(isset($object["gas"])){
            $object["gas"] = $this->web3->toHex($object["gas"]);
        }
        if(isset($object["gasPrice"])){
            $object["gasPrice"] = $this->web3->toHex($object["gasPrice"]);
        }
        if(isset($object["nonce"])){
            $object["nonce"] = $this->web3->toHex($object["nonce"]);
        }

        return $this->provider->request("eth_sendTransaction", [$object]);
    }

    public function contract(array $abi)
    {
        return new Contract($this, $abi);
    }
    
    public function blockNumber()
    {
        $blockNumber = $this->provider->request("eth_blockNumber");
        return new BigInteger($blockNumber, 16);
    }

    public function call(array $object) : string
    {

    }
}