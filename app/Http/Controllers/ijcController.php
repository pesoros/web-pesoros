<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\IjcProducts;
use Illuminate\Support\Facades\DB;
use Lazada\LazopClient;
use Lazada\LazopRequest;

class ijcController extends Controller
{
    private $accessToken;
    private $apiGateway;
    private $appKey;
    private $appSecret;

    public function __construct()
    {
        $this->accessToken = ['50000901203pOEa1ed0ec4bzhxlmygwTlXbEU5psxHnetiaFzwlF4CSviEEK9USy'];
        $this->apiGateway = env('LZ_API_GATEWAY');
        $this->apiKey = env('LZ_API_KEY');
        $this->apiSecret = env('LZ_API_SECRET');
    }

    public function index(){
        return view('ijc/index');
    }

    public function ijclokal(){
        return view('ijc/index_local');
    }
    
    public function transaction()
    {
        return view('ijc/transaction');
    }

    public function order()
    {
        return view('ijc/order');
    }

    public function edit($id)
    {
        $data = IjcProducts::findOrFail($id);

        return view('ijc/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'qtylk' => 'required',
            'qtylz' => 'required',
        ]);
        
        $id = $request->input('id');
        $itemid = $request->input('itemid');
        $skuid = $request->input('skuid');
        $data['qty_local'] = $request->input('qtylk');
        $data['qty'] = $request->input('qtylz');

        $this->updateLazadaQty($itemid,$skuid,$data['qty']);
    
        DB::table('ijc_products')
                ->where('id', $id)
                ->update($data);
    
                return redirect('/ijclocal');
    }

    public function updateLazadaQty($itemid,$skuid,$qty)
    {
        $c = new LazopClient($this->apiGateway, $this->apiKey, $this->apiSecret);
        $request = new LazopRequest('/product/price_quantity/update');
        $request->addApiParam('payload','
                <Request>
                <Product>
                <Skus>
                    <Sku>
                    <ItemId>'.$itemid.'</ItemId>
                    <SkuId>'.$skuid.'</SkuId>
                    <Quantity>'.$qty.'</Quantity>
                    </Sku>
                </Skus>
                </Product>
            </Request>
        ');
        $executelazop = json_decode($c->execute($request, $this->accessToken[0]), true);
        
        return $executelazop;
    }
}