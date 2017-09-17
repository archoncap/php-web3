<?php

namespace Formaldehid\Web3\Api\Eth;

use Formaldehid\Web3\Api\Eth;
use Formaldehid\Web3\Api\Eth\Contract\Token;

class Contract
{
    public $eth;

    public $abi;

    /**
     * Contract constructor.
     * @param Eth $eth
     * @param array $abi
     */
    public function __construct(Eth $eth, array $abi)
    {
        $this->eth = $eth;
        $this->abi = $abi;
    }

    /**
     * @param string $address
     * @return Token
     */
    public function at(string $address)
    {
        return new Token($this, $address);
    }
}