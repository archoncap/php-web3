<?php
namespace Formaldehid\Web3\Providers;

use Graze\GuzzleHttp\JsonRpc\Client;

class HttpProvider implements Provider
{
    protected $client;

    protected $id = 0;

    public function __construct(string $url)
    {
        $this->client = Client::factory($url, ["rpc_error" => true]);
    }

    /**
     * @param string $method
     * @param null|array $params
     * @return mixed
     */
    public function request(string $method, $params = null)
    {
        $this->id++;
        $response = $this->client->send($this->client->request($this->id, $method, $params));
        $array = \Graze\GuzzleHttp\JsonRpc\json_decode($response->getBody(), true);
        return $array["result"];
    }
}