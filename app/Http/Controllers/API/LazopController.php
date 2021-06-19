<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Lazada\LazopClient;
use Lazada\LazopRequest;
use App\Models\IjcProducts;

class LazopController extends Controller
{
    private $accessToken;
    private $apiGateway;
    private $appKey;
    private $appSecret;

    public function __construct()
    {
        $this->accessToken = '50000901203pOEa1ed0ec4bzhxlmygwTlXbEU5psxHnetiaFzwlF4CSviEEK9USy';
        $this->apiGateway = 'https://api.lazada.co.id/rest';
        $this->apiKey = '101982';
        $this->apiSecret = 'SXmWsDgxmj6rziM9QyaGhZCPW6c6WVXc';
    }

    public function get_seller()
    {
        $method = 'GET';
        $apiName = '/seller/get';

        $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
        $request = new LazopRequest($apiName,$method);
        $executelazop = json_decode($c->execute($request, $this->accessToken), true);
        
        return $executelazop;
    }

    public function get_product()
    {
        $method = 'GET';
        $apiName = '/products/get';

        $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
        $request = new LazopRequest($apiName,$method);

        $request->addApiParam('filter','live');
        // $request->addApiParam('update_before','2018-01-01T00:00:00+0800');
        // $request->addApiParam('create_before','2018-01-01T00:00:00+0800');
        $request->addApiParam('offset','0');
        $request->addApiParam('create_after','2010-01-01T00:00:00+0800');
        // $request->addApiParam('update_after','2010-01-01T00:00:00+0800');
        $request->addApiParam('limit','50');
        $request->addApiParam('options','1');
        // $request->addApiParam('sku_seller_list',' [\"39817:01:01\", \"Apple 6S Black\"]');

        $executelazop = json_decode($c->execute($request, $this->accessToken), true);
        $data = $executelazop['data']['products'];

        for ($i=0; $i < count($data); $i++) { 
            $res['data'][$i]['itemid'] = isset($data[$i]['item_id']) ? $data[$i]['item_id'] : "-";
            $res['data'][$i]['skuid'] = isset($data[$i]['skus'][0]['SkuId']) ? $data[$i]['skus'][0]['SkuId'] : "-";
            $res['data'][$i]['sellersku'] = isset($data[$i]['skus'][0]['SellerSku']) ? $data[$i]['skus'][0]['SellerSku'] : "-";
            $res['data'][$i]['name'] = isset($data[$i]['attributes']['name']) ? $data[$i]['attributes']['name'] : "-";
            $res['data'][$i]['model'] = isset($data[$i]['attributes']['model']) ? $data[$i]['attributes']['model'] : "-";
            $res['data'][$i]['brand'] = isset($data[$i]['attributes']['brand']) ? $data[$i]['attributes']['brand'] : "-";
            $res['data'][$i]['url'] = isset($data[$i]['skus'][0]['Url']) ? $data[$i]['skus'][0]['Url'] : "-";
            $res['data'][$i]['quantity'] = isset($data[$i]['skus'][0]['quantity']) ? $data[$i]['skus'][0]['quantity'] : "-";
            $res['data'][$i]['status'] = isset($data[$i]['skus'][0]['Status']) ? $data[$i]['skus'][0]['Status'] : "-";
            $res['data'][$i]['shop_sku'] = isset($data[$i]['skus'][0]['ShopSku']) ? $data[$i]['skus'][0]['ShopSku'] : "-";
            $res['data'][$i]['images'] = isset($data[$i]['skus'][0]['Images']) ? $data[$i]['skus'][0]['Images'] : "-";
        }
        
        return $res;
    }

    // public function get_product()
    // {
    //     $method = 'GET';
    //     $apiName = '/products/get';

    //     $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
    //     $request = new LazopRequest($apiName,$method);

    //     $request->addApiParam('filter','live');
    //     // $request->addApiParam('update_before','2018-01-01T00:00:00+0800');
    //     // $request->addApiParam('create_before','2018-01-01T00:00:00+0800');
    //     $request->addApiParam('offset','0');
    //     $request->addApiParam('create_after','2010-01-01T00:00:00+0800');
    //     // $request->addApiParam('update_after','2010-01-01T00:00:00+0800');
    //     $request->addApiParam('limit','50');
    //     $request->addApiParam('options','1');
    //     // $request->addApiParam('sku_seller_list',' [\"39817:01:01\", \"Apple 6S Black\"]');

    //     $executelazop = json_decode($c->execute($request, $this->accessToken), true);
        
    //     return $executelazop;
    // }

    public function get_transaction()
    {
        $method = 'GET';
        $apiName = '/finance/transaction/detail/get';

        $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
        $request = new LazopRequest($apiName,$method);

        $request->addApiParam('trans_type','-1');
        $request->addApiParam('start_time','2020-06-01');
        $request->addApiParam('end_time','2020-06-30');
        $request->addApiParam('limit','10');
        $request->addApiParam('offset','0');

        $executelazop = json_decode($c->execute($request, $this->accessToken), true);
        
        return $executelazop;
    }

    public function importProducts()
    {
        $data = $this->get_product();
        $data = $data['data'];
        for ($i=0; $i < count($data); $i++) { 
            $dataInsert['itemid'] = $data[$i]['itemid'];
            $dataInsert['skuid'] = $data[$i]['skuid'];
            $dataInsert['sellersku'] = $data[$i]['sellersku'];
            $dataInsert['name'] = $data[$i]['name'];
            $dataInsert['model'] = $data[$i]['model'];
            $dataInsert['brand'] = $data[$i]['brand'];
            $dataInsert['url'] = $data[$i]['url'];
            $dataInsert['qty'] = $data[$i]['quantity'];
            $dataInsert['status'] = $data[$i]['status'];
            IjcProducts::create($dataInsert);
        }
        return 'success';
    }

    public function get_product_locally()
    {
        $products = IjcProducts::all();
        $res['data'] = $products;
        return $res;
    }
}
