<?php

namespace Formaldehid\Web3\Api\Eth;

use Formaldehid\Web3\Api\Eth;
use Formaldehid\Web3\Api\Eth\Contract\Token;

class Contract
{
    protected $eth;

    protected $abi;

    public function __construct(Eth $eth, array $abi)
    {
        $this->eth = $eth;
        $this->abi = $abi;
    }

    public function at(string $address)
    {
        return new Token($this, $address);
    }
}