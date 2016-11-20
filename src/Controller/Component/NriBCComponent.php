<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

Class NriBCComponent extends Component
{
    private $api;
    
    public function initialize(array $config)
    {
        $conf = new \Swagger\Client\Configuration();
        $base64auth = "NWRmNWZmYmMyMGFlM2U3NGRkNGI0MjAxZjE3YWQ2YmM6MTNlMjFlNWFlMDQwYzgxZjM5YTlhZTAzN2E3ODQ5MjE=";
	if (!empty($config['key']) && !empty($config['pw']))
		$base64auth = base64_encode($config['key'] . ':' . $config['pw']);
        $conf->addDefaultHeader("X-BCD-Authorization", $base64auth);
        $this->api = new \Swagger\Client\ApiClient($conf);
    }

    public function CallAssetsHistoryApi(string $assetName)
    {
        $apiPath = "api/v1/user/assets/" . $assetName . "/tradingHistory";
        return $this->api->CallApi($apiPath, \Swagger\Client\ApiClient::$GET, [], [], []);
    }

    public function CallMyAssetsApi()
    {
        $charlies = [
            "charlie0001", "charlie0002", "charlie0003", "charlie0004", "charlie0999"
        ];
        $responses;
        foreach ($charlies as $charlie)
        {
            $apiPath = "api/v1/user/assets/" . $charlie;
            $response = $this->api->CallApi($apiPath, \Swagger\Client\ApiClient::$GET, [], [], []);
            $responses[] = $response[0];
        }

        return $responses;
    }

    public function CallMyAssetApi(string $name)
    {
        $response = $this->api->CallApi("api/v1/user/assets/" . $name, \Swagger\Client\ApiClient::$GET, [], [], []);
        return $response[0];
    }

    public function CallSendMyAssetApi(string $name, string $address)
    {
        $path = "api/v1/user/assets/" . $name . "/send";
        $post = ["quantity" => 1, "toAddress" => $address];
        $this->api->CallApi($path, \Swagger\Client\ApiClient::$POST, [], $post, ["Content-Type" => "application/json"]);
    }
}
