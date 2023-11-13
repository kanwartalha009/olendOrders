<?php

namespace App\Http\Controllers;

use App\Models\LineItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $shop = User::first();
        $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["status" => "any", "limit" => 250]);
        $orders = json_decode(json_encode($response));
        foreach ($orders->body->orders as $order) {
//            dd($order);
if ($order->id == '5785339560271'){
    foreach ($order->line_items as $line_item) {
        dd($line_item->properties);
    }
}

//            $syncOrder = Order::where('order_id', $order->id)->first();
//            if ($syncOrder == null) {
//                $syncOrder = new Order();
//            }
//            $syncOrder->order_id = $order->id;
//            $syncOrder->order_name = $order->name;
//            $syncOrder->browser_ip = $order->browser_ip;
//            $syncOrder->contact_email = $order->contact_email;
//            $syncOrder->currency = $order->currency;
//            $syncOrder->current_subtotal_price = $order->subtotal_price;
//            $syncOrder->current_total_discounts = $order->current_total_discounts;
//            $syncOrder->current_total_tax = $order->total_tax;
//            $syncOrder->current_total_price = $order->total_price;
//            $syncOrder->financial_status = $order->financial_status;
//            $syncOrder->fulfillment_status = $order->fulfillment_status;
//            $syncOrder->note = $order->note;
//            $syncOrder->order_json = json_encode($order);
//            $syncOrder->save();
//            foreach ($order->line_items as $line_item) {
//                $syncItem = LineItem::where('shopify_item_id', $line_item->id)->where('shopify_order_id', $order->id)->first();
//                if ($syncItem == null) {
//                    $syncItem = new LineItem();
//                }
//                $syncItem->shopify_item_id = $line_item->id;
//                $syncItem->shopify_order_id = $order->id;
//                $syncItem->name = $line_item->name;
//                $syncItem->product_id = $line_item->product_id;
//                $syncItem->title = $line_item->title;
//                $syncItem->variant_id = $line_item->variant_id;
//                $syncItem->variant_title = $line_item->variant_title;
//                $syncItem->property = $line_item->vendor;
//                $syncItem->property_locale = $line_item->vendor;
//                $syncItem->order_id = $syncOrder->id;
//                $syncItem->save();
//            }
        }
    }
}
