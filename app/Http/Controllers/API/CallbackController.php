<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function store(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'no data saved',
            'response' => $request
        ], 200);
    }
}
