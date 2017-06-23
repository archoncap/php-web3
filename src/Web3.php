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

    /**
     * @param $address
     * @return bool
     */
    public function isAddress($address)
    {
        if(!preg_match("/^(0x)?[0-9a-f]{40}$/i", $address)){
            // check if it has the basic requirements of an address
            return false;
        } else if(preg_match("/^(0x)?[0-9a-f]{40}$/", $address) || preg_match("/^(0x)?[0-9A-F]{40}$/", $address)){
            // If it's all small caps or all all caps, return true
            return true;
        } else {
            // Otherwise check each case
            return $this->isChecksumAddress($address);
        }
    }

    /**
     * @param $address
     * @return bool
     */
    public function isChecksumAddress($address)
    {
        return true;

        //TODO
        /*
        // Check each case
        $address = str_replace('0x','', $address);
        $addressHash = hash('sha256', strtolower($address));
        $addressArray = str_split($address);
        $addressHashArray = str_split($addressHash);

        for ($i = 0; $i < 40; $i++ ) {
            // the nth letter should be uppercase if the nth digit of casemap is 1
            if ((hexdec($addressHashArray[$i]) > 7 && strtoupper($addressArray[$i]) !== $addressArray[$i])
                || (hexdec($addressHashArray[$i]) <= 7 )) {
                return false;
            }
        }
        return true;
        */
    }

}