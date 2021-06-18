<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DataTables;
class ijcController extends Controller
{
    public function json(){
        return Datatables::of(User::all())->make(true);
    }

    public function index(){
        return view('ijc/index');
    }
}