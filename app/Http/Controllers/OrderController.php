<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $shop = User::first();
        $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["status" => "any", "limit" => 250]);
        $orders = json_decode(json_encode($response));
        foreach ($orders->body->orders as $order) {
            dd($order);
        }
    }
}
