<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function has_items(){
        return $this->hasMany('App\Models\LineItem', 'order_id', 'id');
    }
}
