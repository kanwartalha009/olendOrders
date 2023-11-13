<?php

namespace App\Http\Controllers;

use App\Models\LineItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Order $order, Request $request){
        $order_query = $order->newQuery();
        if ($request->input('search')) {
            if (Str::contains($request->input('search'), '#')) {
            $order_query->where('order_name', 'like', '%' . $request->input('search') . '%');
            }else{
                $order_query->where(function ($query) use ($request) {
                    $query->orWhereHas('has_items', function ($q) use ($request){
                        $q->where('property', 'like', '%' .$request->input('search') . '%');
                    });
                });
            }

        } else {
            $query = null;
        }
        $orders = $order_query->latest('order_name', 'DESC')->paginate(100);
        $user = User::first();
        $shop = str_replace('.myshopify.com', '', $user->name);
        return view('orders')->with([
            'orders' => $orders,
            'request' => $request,
            'shop' => $shop
        ]);
    }
    public function csvExport(Order $order, Request $request){
        $order_query = $order->newQuery();
        if ($request->input('search')) {
            if (Str::contains($request->input('search'), '#')) {
                $order_query->where('order_name', 'like', '%' . $request->input('search') . '%');
            }else{
                $order_query->where(function ($query) use ($request) {
                    $query->orWhereHas('has_items', function ($q) use ($request){
                        $q->where('property', 'like', '%' .$request->input('search') . '%');
                    });
                });
            }

        } else {
            $query = null;
        }
        $orders = $order_query->latest('order_name', 'DESC')->get();
//        dd($orders);
        $data = [
            ['#', 'Order', 'Email', 'Items', 'Preorder Date'],
        ];

        foreach ($orders as $i=>$order) {
            $date ='';
            if (count($order->has_items) == 1){
                $date = str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property);
            }else{
                foreach ($order->has_items as $item) {
                    $date .= str_replace('Pre-order item - Delivery date:', '', $item->property) . ',';
                }
            }
            $data[] = [
                ++$i,
                $order->order_name,
                $order->contact_email,
                count($order->has_items),
                $date,
            ];
        }
// File path for the CSV file
        $filePath = 'csv/' . Str::random(10) . '.csv';
// Create and write to the CSV file
        Storage::disk('local')->put($filePath, '');

        $file = fopen(storage_path('app/' . $filePath), 'w');

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);

// Download the CSV file
        return response()->download(storage_path('app/' . $filePath), str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property). 'Orders.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property). 'Orders.csv',
        ]);

    }
    public function ordersSync($next = null)
    {
        $shop = User::first();
        if ($next){
            $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["page_info" => $next,"limit" => 250]);
        }else{
            $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["limit" => 250, "status" => 'any']);
        }
        $orders = json_decode(json_encode($response));
        foreach ($orders->body->orders as $order) {
            $syncOrder = Order::where('order_id', $order->id)->first();
            if ($syncOrder == null) {
                $syncOrder = new Order();
            $syncOrder->order_id = $order->id;
            $syncOrder->order_name = $order->name;
            $syncOrder->browser_ip = $order->browser_ip;
            $syncOrder->contact_email = $order->contact_email;
            $syncOrder->currency = $order->currency;
            $syncOrder->current_subtotal_price = $order->subtotal_price;
            $syncOrder->current_total_discounts = $order->current_total_discounts;
            $syncOrder->current_total_tax = $order->total_tax;
            $syncOrder->current_total_price = $order->total_price;
            $syncOrder->financial_status = $order->financial_status;
            $syncOrder->fulfillment_status = $order->fulfillment_status;
            $syncOrder->note = $order->note;
            $syncOrder->order_json = json_encode($order);
            $syncOrder->save();
            foreach ($order->line_items as $line_item) {
                $syncItem = LineItem::where('shopify_item_id', $line_item->id)->where('shopify_order_id', $order->id)->first();
                if ($syncItem == null) {
                    $syncItem = new LineItem();
                }
                $syncItem->shopify_item_id = $line_item->id;
                $syncItem->shopify_order_id = $order->id;
                $syncItem->name = $line_item->name;
                $syncItem->product_id = $line_item->product_id;
                $syncItem->title = $line_item->title;
                $syncItem->variant_id = $line_item->variant_id;
                $syncItem->variant_title = $line_item->variant_title;
                if (count($line_item->properties) > 0){
                    if ($line_item->properties[0]->name == '_is_preorder'){
                        $syncItem->property = $line_item->properties[0]->value;
                    }
                    if ($line_item->properties[1]->name == '_preorder_locale'){
                        $syncItem->property_locale = $line_item->properties[1]->value;
                    }
                }
                $syncItem->order_id = $syncOrder->id;
                $syncItem->save();
            }
        }
        }
        if (isset($orders->link->next)) {
            $this->ordersSync($orders->link->next);
        }
        return redirect()->route('home')->with('success', 'Orders Synced !');
    }
}
