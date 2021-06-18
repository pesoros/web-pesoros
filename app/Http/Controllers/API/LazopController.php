<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Lazada\LazopClient;
use Lazada\LazopRequest;

class LazopController extends Controller
{
    private $current_timestamp;
    private $accessToken;
    private $apiGateway;

    public function __construct()
    {
        $this->current_timestamp = $this->msectime();
        $this->accessToken = '50000901203pOEa1ed0ec4bzhxlmygwTlXbEU5psxHnetiaFzwlF4CSviEEK9USy';
        $this->apiGateway = 'https://api.lazada.co.id/rest';
    }

    function msectime() 
    {
        list($msec, $sec) = explode(' ', microtime());
        return $sec . '000';
     }

    protected function generateSign($apiName,$params)
	{
		ksort($params);

		$stringToBeSigned = '';
		$stringToBeSigned .= $apiName;
		foreach ($params as $k => $v)
		{
			$stringToBeSigned .= "$k$v";
		}
		unset($k, $v);

		return strtoupper($this->hmac_sha256($stringToBeSigned,$this->secretKey));
	}

    public function get_seller(Type $var = null)
    {
        $c = new LazopClient('https://api.lazada.test/rest', '101982', 'SXmWsDgxmj6rziM9QyaGhZCPW6c6WVXc');
        $request = new LazopRequest('/products/get','GET');
        $request->addApiParam('filter','live');
        $request->addApiParam('update_before','2018-01-01T00:00:00+0800');
        $request->addApiParam('create_before','2018-01-01T00:00:00+0800');
        $request->addApiParam('offset','0');
        $request->addApiParam('create_after','2010-01-01T00:00:00+0800');
        $request->addApiParam('update_after','2010-01-01T00:00:00+0800');
        $request->addApiParam('limit','10');
        $request->addApiParam('options','1');
        $request->addApiParam('sku_seller_list',' [\"39817:01:01\", \"Apple 6S Black\"]');
        return $c->execute($request, $this->accessToken);
    }

    // public function get_seller()
    // {
    //     $client = new \GuzzleHttp\Client();
    //     $apiName = '/seller/get';
    //     $httpres = $client->request('GET', $this->apiGateway.$apiName, ['query' => [
    //         'app_key' => '101982',
    //         'sign_method' => 'sha256',
    //         'access_token' => $this->accessToken,
    //         'timestamp' => $this->current_timestamp,
    //         'sign' => '49A943A4DD7DF1AD4CEF5CB87E02ADB956C1E74E464ED562D4A85AC387B1CBFC'
    //     ]]);
        

    //     $res = $httpres->getBody(); 
    //     $res = json_decode($httpres->getBody(), true);

    //     return response()->json([
    //         'timestamp' => $this->current_timestamp,
    //         'host_by' => 'pesoros',
    //         'success' => true,
    //         'message' => 'Seller Data',
    //         'response' => $res
    //     ], 200);
    // }
    public function get_product()
    {
        $client = new \GuzzleHttp\Client();
        $httpres = $client->request('GET', 'https://api.lazada.co.id/rest/products/get', ['query' => [
            'filter' => 'live',
            'offset' => '0',
            'limit' => '10',
            'options' => '1',
            'create_after' => '2010-01-01T00:00:00+0800',
            'app_key' => '101982',
            'sign_method' => 'sha256',
            'access_token' => $this->accessToken,
            'timestamp' => $this->current_timestamp,
            'sign' => 'E00A875B9ECD67B9DA17FBCECF812A3E094180EA95D791C13BFD6F0E19BE2226'
        ]]);
        

        $res = $httpres->getBody(); 
        $res = json_decode($httpres->getBody(), true);

        return response()->json([
            'host_by' => 'pesoros',
            'success' => true,
            'message' => 'List Product',
            'response' => $res
        ], 200);
    }

    public function get_transaction()
    {
        $client = new \GuzzleHttp\Client();
        $httpres = $client->request('GET', 'https://api.lazada.co.id/rest/finance/transaction/detail/get?start_time=2018-01-01&offset=0&end_time=2021-06-17&limit=100&trans_type=-1&app_key=101982&sign_method=sha256&access_token$this->accessTokentimestamp=1623936066044&sign=0DF6EAFF6574855F0014F429F7BEE7B803489B13A270A76986E757C85F96AF64');
        

        $res = $httpres->getBody(); 
        $res = json_decode($httpres->getBody(), true);

        return response()->json([
            'host_by' => 'pesoros',
            'success' => true,
            'message' => 'List Transaction',
            'response' => $res
        ], 200);
    }
}
