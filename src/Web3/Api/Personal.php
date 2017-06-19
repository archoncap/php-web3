<?php

namespace Formaldehid\Web3\Api;

use Formaldehid\Web3;
use Formaldehid\Web3\Providers\Provider;
use Graze\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use phpseclib\Math\BigInteger;

class Personal implements Api
{

    protected $web3;

    protected $provider;

    /**
     * Personal constructor.
     * @param Web3 $web3
     * @param Provider $provider
     */
    public function __construct(Web3 $web3, Provider $provider)
    {
        $this->web3 = $web3;
        $this->provider = $provider;
    }


    public function newAccount(string $password) : string
    {
        return $this->provider->request("personal_newAccount", [$password]);
    }
}