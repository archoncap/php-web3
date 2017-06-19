<?php

namespace Formaldehid\Web3\Api;

use Formaldehid\Web3;
use Formaldehid\Web3\Providers\Provider;

interface Api
{
    public function __construct(Web3 $web3, Provider $provider);
}