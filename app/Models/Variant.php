<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    public function hasProduct(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
