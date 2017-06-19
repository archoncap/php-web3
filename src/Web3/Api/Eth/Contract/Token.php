<?php

namespace Formaldehid\Web3\Api\Eth\Contract;

use Formaldehid\Web3\Api\Eth\Contract;

class Token
{
    protected $contract;

    protected $abi;

    public function __construct(Contract $contract, array $abi)
    {
        $this->contract = $contract;
        $this->abi = $abi;
    }
}