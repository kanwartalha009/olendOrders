<?php

namespace App\Jobs;

use App\Models\LineItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class orderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = new User();
        $user->name = 'Talha';
        $user->email = 'kanwartalha009@gmail.com';
        $user->save();
        foreach ($this->orders->body->orders as $order) {
            $syncOrder = Order::where('order_id', $order->id)->first();
            if ($syncOrder == null) {
                $syncOrder = new Order();
            }
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
            $createdAt = new DateTime($order->created_at);
            $syncOrder->order_created_at = Carbon::instance($createdAt);
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
                if (count($line_item->properties) > 0) {
                    if ($line_item->properties[0]->name == '_is_preorder') {
                        $syncItem->property = $line_item->properties[0]->value;
                    }
                    if ($line_item->properties[1]->name == '_preorder_locale') {
                        $syncItem->property_locale = $line_item->properties[1]->value;
                    }
                }
                $syncItem->order_id = $syncOrder->id;
                $syncItem->save();
            }
        }
    }
}
