<?php

namespace Formaldehid\Web3\Api\Eth\Contract;

use Formaldehid\Web3\Api\Eth\Contract;

class Token
{
    public $contract;

    public $address;

    /**
     * Token constructor.
     * @param Contract $contract
     * @param string $address
     */
    public function __construct(Contract $contract, string $address)
    {
        $this->contract = $contract;
        $this->address = $address;
    }

    /**
     * @param $name
     * @param $arguments
     * @return null|string
     */
    public function __call($name, $arguments)
    {
        foreach ($this->contract->abi as $code){
            if(isset($code["constant"]) && isset($code["name"]) && $code["name"] == $name){
                $payload = [];
                if(count($arguments) > count($code["inputs"])){
                    $payload = $arguments[count($code["inputs"])];
                }
                $payload["to"] = $this->address;
                $payload["data"] = '0x' . $this->_signature($code["name"], $code["inputs"])
                    . $this->_encodeParams($arguments, $code["inputs"]);

                if($code["constant"]){
                    return $this->contract->eth->call($payload);
                } else {
                    return $this->contract->eth->sendTransaction($payload);
                }
            }
        }

        return null;
    }

    /**
     * @param $name
     * @param array $inputs
     * @return string
     */
    protected function _signature($name, array $inputs)
    {
        $fullName = $name;
        if($inputs){
            $fullName .= "(";
            for($c=0; $c<count($inputs); $c++){
                $fullName .= $inputs[$c]["type"];
                if(count($inputs) - 1 != $c){
                    $fullName .= ",";
                }
            }
            $fullName .= ")";
        }

        return substr($this->contract->eth->web3->sha3($fullName), 2, 8);
    }

    /**
     * @param array $params
     * @param array $inputs
     * @return string
     */
    protected function _encodeParams(array $params, array $inputs)
    {
        $result = "";
        for($c=0; $c<count($params); $c++){
            $param = $params[$c];
            if(isset($inputs[$c])){
                $input = $inputs[$c];
                if(isset($input["type"])){
                    switch ($input["type"]){
                        case "address":
                            $result .= str_pad(substr($param, 2), 64, "0", STR_PAD_LEFT);
                            break;
                        case "uint256":
                            $result .= str_pad(substr($this->contract->eth->web3->toHex($param), 2), 64, "0", STR_PAD_LEFT);
                            break;
                    }
                }
            }
        }

        return $result;
    }
}

// mcoToken.balanceOf(address, function(error, mcoBalance){
// mcoToken.transfer(env.ETH_ADDRESS, amount, {from: address, gas:60000}, function(error, result){