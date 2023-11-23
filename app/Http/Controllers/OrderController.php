<?php

namespace App\Http\Controllers;

use App\Jobs\orderJob;
use App\Models\LineItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Order $order, Request $request)
    {
        $order_query = $order->newQuery();
        if ($request->input('search')) {
            if (Str::contains($request->input('search'), '#')) {
                $order_query->where('order_name', 'like', '%' . $request->input('search') . '%');
            } else {
                $order_query->where(function ($query) use ($request) {
                    $query->orWhereHas('has_items', function ($q) use ($request) {
                        $q->where('property', 'like', '%' . $request->input('search') . '%');
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

    public function csvExport(Order $order, Request $request)
    {
        $order_query = $order->newQuery();
        if ($request->input('search')) {
            if (Str::contains($request->input('search'), '#')) {
                $order_query->where('order_name', 'like', '%' . $request->input('search') . '%');
            } else {
                $order_query->where(function ($query) use ($request) {
                    $query->orWhereHas('has_items', function ($q) use ($request) {
                        $q->where('property', 'like', '%' . $request->input('search') . '%');
                    });
                });
            }

        } else {
            $query = null;
        }
        $orders = $order_query->latest('order_name', 'DESC')->get();
//        dd($orders);
        $data = [
            ['Order', 'Email', 'Items', 'Preorder Date'],
        ];

        foreach ($orders as $order) {
            $date = '';
            if (count($order->has_items) == 1) {
                $date = str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property);
            } else {
                foreach ($order->has_items as $i => $item) {
                    if ($item->property) {
                        if ($i != 0) {
                            $date .= ',' . str_replace('Pre-order item - Delivery date:', '', $item->property);
                        } else {
                            $date .= str_replace('Pre-order item - Delivery date:', '', $item->property);
                        }
                    }
                }
            }
            $data[] = [
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
        return response()->download(storage_path('app/' . $filePath), str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property) . 'Orders.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . str_replace('Pre-order item - Delivery date:', '', $order->has_items[0]->property) . 'Orders.csv',
        ]);

    }

    public function ordersSync($next = null)
    {
        $shop = User::first();
                $shop->api()->rest('post', '/admin/api/2022-10/webhooks.json', [
            'webhook'=>[
                'topic' => 'orders/create',
                'address' => 'https://phpstack-946419-4061850.cloudwaysapps.com/webhook/orders-create'
            ]
        ]);
                $webhook = $shop->api()->rest('GET', '/admin/api/2022-10/webhooks.json');
        dd($webhook);
        if ($next) {
            $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["page_info" => $next, "limit" => 250]);
        } else {
            $response = $shop->api()->rest('GET', '/admin/api/2022-10/orders.json', ["limit" => 250, "status" => 'any']);
        }
        $orders = json_decode(json_encode($response));
        orderJob::dispatchAfterResponse($orders);
        if (isset($orders->link->next)) {
            $this->ordersSync($orders->link->next);
        }
        return redirect()->route('home')->with('success', 'Orders Syncing!');
    }
}
