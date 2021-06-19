<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IjcProducts extends Model
{
    use HasFactory;
    protected $fillable = ['itemid','skuid','sellersku','name','model','status','url','brand','qty'];
}
