<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Lazada\LazopClient;
use Lazada\LazopRequest;
use App\Models\IjcProducts;
use Carbon\Carbon;

class LazopController extends Controller
{
    private $accessToken;
    private $apiGateway;
    private $appKey;
    private $appSecret;

    public function __construct()
    {
        $datatoken = [
            [
                "akun" => "indodjainem.group@gmail.com", 
                "token" => "50000901203pOEa1ed0ec4bzhxlmygwTlXbEU5psxHnetiaFzwlF4CSviEEK9USy" 
            ]
         ]; 
          
        $this->accessToken = $datatoken;
        $this->apiGateway = env('LZ_API_GATEWAY');
        $this->apiKey = env('LZ_API_KEY');
        $this->apiSecret = env('LZ_API_SECRET');
    }

    public function get_seller()
    {
        $method = 'GET';
        $apiName = '/seller/get';

        $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
        $request = new LazopRequest($apiName,$method);
        $executelazop = json_decode($c->execute($request, $this->accessToken[0]['token']), true);
        
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

        $executelazop = json_decode($c->execute($request, $this->accessToken[0]['token']), true);
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

    //     $executelazop = json_decode($c->execute($request, $this->accessToken[0]['token']), true);
        
    //     return $executelazop;
    // }

    public function get_transaction(Request $request)
    {
        $querystring = $request->all();
        if (isset($querystring['date'])) {
            $date = $querystring['date'];
        } else {
            $date = "2021-01-01";
        }

        $tokenwehave = $this->accessToken;
        $arr = [];
        $method = 'GET';
        $apiName = '/finance/transaction/detail/get';

        foreach ($tokenwehave as $key => $value) {
            $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
            $request = new LazopRequest($apiName,$method);
            $request->addApiParam('trans_type','-1');
            $request->addApiParam('start_time',$date);
            $request->addApiParam('end_time',$date);
            $request->addApiParam('limit','500');
            $request->addApiParam('offset','0');
            $executelazop = json_decode($c->execute($request, $value['token']), true);

            if (isset($executelazop['data'])) {

                $data = $executelazop['data'];
            
                for ($i=0; $i < count($data); $i++) { 
                    if ($data[$i]['fee_name'] != "Payment Fee") {
                        $data[$i]['nama_akun'] = $value['akun'];
                        array_push($arr,$data[$i]);
                    }
                }
    
                $res['jumlah_data'] = count($arr);
                $res['data'] = $arr;
            } else {
                $res = $executelazop;
            }
        }
        
        return $res;
    }
    
    public function get_orders(Request $request)
    {
        $querystring = $request->all();
        if (isset($querystring['date'])) {
            $datestart = Carbon::createFromFormat('Y-m-d', $querystring['date']);
            $daysToAdd = 1;
            $dateend = $datestart->addDays($daysToAdd)->format('Y-m-d').'T01:00:00+08:00';
            $datestart = $querystring['date'].'T00:00:00+08:00';
        } else {
            $datestart = "2021-01-01T01:00:00+08:00";
            $dateend = "2021-01-02T00:00:00+08:00";
        }
        
        $tokenwehave = $this->accessToken;
        $arr = [];
        $method = 'GET';
        $apiName = '/orders/get';
        
        foreach ($tokenwehave as $key => $value) {
            $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
            $request = new LazopRequest($apiName,$method);
            // $request->addApiParam('update_before','2018-02-10T16:00:00+08:00');
            $request->addApiParam('sort_direction','DESC');
            $request->addApiParam('offset','0');
            $request->addApiParam('limit','10');
            // $request->addApiParam('update_after','2017-02-10T09:00:00+08:00');
            $request->addApiParam('sort_by','created_at');
            $request->addApiParam('created_before', $dateend);
            $request->addApiParam('created_after', $datestart);
            // $request->addApiParam('status','shipped');
            $executelazop = json_decode($c->execute($request, $value['token']), true);

            if (isset($executelazop['data']['orders'])) {

                $data = $executelazop['data']['orders'];
            
                for ($i=0; $i < count($data); $i++) { 
                    $data[$i]['nama_akun'] = $value['akun'];
                    array_push($arr,$data[$i]);
                }
    
                $res['date_start'] = $datestart;
                $res['date_end'] = $dateend;
                $res['jumlah_data'] = count($arr);
                $res['data'] = $arr;
            } else {
                $res = $executelazop;
            }
        }

        return $res;
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
