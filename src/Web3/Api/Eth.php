<?php

namespace Formaldehid\Web3\Api;

use Formaldehid\Web3;
use Formaldehid\Web3\Providers\Provider;
use Graze\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use phpseclib\Math\BigInteger;
use Formaldehid\Web3\Api\Eth\Contract;

class Eth implements Api
{

    public $web3;

    public $provider;

    public $defaultAccount;

    public $defaultBlock = self::DEFAULT_BLOCK_LATEST;

    CONST DEFAULT_BLOCK_EARLIEST = "earliest";
    CONST DEFAULT_BLOCK_LATEST = "latest";
    CONST DEFAULT_BLOCK_PENDING = "pending";

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

    /**
     * @param array $object
     * @return string
     */
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

    /**
     * @param array $abi
     * @return Contract
     */
    public function contract(array $abi)
    {
        return new Contract($this, $abi);
    }

    /**
     * @return BigInteger
     */
    public function blockNumber()
    {
        $blockNumber = $this->provider->request("eth_blockNumber");

        return new BigInteger($blockNumber, 16);
    }

    /**
     * @param array $object
     * @param null $defaultBlock
     * @return string
     */
    public function call(array $object, $defaultBlock = null) : string
    {
        $object["data"] = isset($object["data"]) ? $object["data"] : $this->web3->toHex(0);
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
        if(!$defaultBlock){
            $defaultBlock = $this->defaultBlock;
        }

        return $this->provider->request("eth_call", [$object, $defaultBlock]);
    }
}