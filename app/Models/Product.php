<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    public static function getProduct(){
        $records = DB::table('products')->select('title', 'variant_id', 'code', 'stock', 'price', 'sale_price')->get()->toArray();
        return $records;
    }
    public function hasProducts(){
        return $this->hasMany('App\Models\Variant', 'product_id', 'id');
    }
}
