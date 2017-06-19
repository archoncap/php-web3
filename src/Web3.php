<?php
namespace Formaldehid;

use Formaldehid\Web3\Api\Eth;
use Formaldehid\Web3\Api\Personal;
use Formaldehid\Web3\Providers\Provider;
use phpseclib\Math\BigInteger;

class Web3
{
    public $eth;

    public $personal;

    public function __construct(Provider $provider)
    {
        $this->eth = new Eth($this, $provider);
        $this->personal = new Personal($this, $provider);
    }

    /**
     * @param string|integer|BigInteger $value
     * @return string
     */
    public function toHex($value) : string
    {
        if($value instanceof BigInteger){
            return "0x" . $value->toHex();
        }
        if(substr($value, 0, 2) == "0x"){
            return $value;
        }

        return "0x" . (new BigInteger($value))->toHex();
    }

    /**
     * @param string|integer|BigInteger $number
     * @param string $unit
     * @return string
     */
    public function toWei($number, $unit = "ether") : string
    {
        //TODO more units
        if($number instanceof BigInteger){
            return $number->multiply(new BigInteger(pow(10, 18)))->toString();
        }
        if(is_string($number)){
            return (new BigInteger($number))->multiply(new BigInteger(pow(10, 18)))->toString();
        }

        $decimalPlaces = strlen(substr(strrchr($number, "."), 1));
        return (new BigInteger($number * pow(10, $decimalPlaces)))->multiply(new BigInteger(pow(10, 18 - $decimalPlaces)))
            ->toString();
    }

}