<?php

namespace App\Http\Controllers;
use App\Exports\productsExport;
use App\Jobs\mainProduct;
use App\Jobs\saveProduct;
use App\Models\Country;
use App\Models\mProduct;
use App\Models\Product;
use App\Models\ReturnRequest;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use XMLWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\Facades\Response;
class ProductController extends Controller
{
    public function countryCode(){
        $countries = Country::all();
        return view('countryCode')->with([
            'countries' => $countries
        ]);
    }
    public function countryCodeSave(Request $request){
        $country = Country::where('name', $request->name)->first();
        if ($country == null){
            $country = new Country();
        }
        $country->name = $request->name;
        $country->code = $request->code;
        $country->save();
        return Redirect::tokenRedirect('country.all',['error'=>'Country Added']);
    }
    public function countryCodeUpdate(Request $request, $id){
        $country = Country::find($id);
        $country->name = $request->name;
        $country->code = $request->code;
        $country->save();
        return Redirect::tokenRedirect('country.all',['error'=>'Country Updated']);
    }
    public function countryCodeDelete($id){
        $country = Country::find($id);
        $country->delete();
        return Redirect::tokenRedirect('country.all',['error'=>'Country Deleted']);
    }
    public function allProducts(){
        $products = Product::latest()->get();
        return view('products')->with([
            'products' => $products,
            'user' => User::first()
        ]);
    }
    public function feedSettings(Request $request){
        $user = User::first();
        if ($request->mainFeed){
            $user->mainFeed = $request->mainFeed;
        }
        if ($request->marketFeed){
            $user->marketFeed = $request->marketFeed;
        }
        $user->save();

        if ($request->mainFeed) {
            return Redirect::tokenRedirect('products.all', ['notice' => 'Feed Settings Updated']);
        }else{
            return Redirect::tokenRedirect('home', ['notice' => 'Feed Settings Updated']);
        }
    }
    public function shopifyProducts(){
        mainProduct::dispatchAfterResponse();
        $products = mProduct::paginate(100);
        return view('main_products')->with([
            'products' => $products,
            'user' => User::first()
        ]);
    }
    public function shopifyProductsSync($next = null){
        $shop = User::first();
        $response = $shop->api()->rest('GET', '/admin/api/2022-10/products.json', ["limit" => 250, "page_info" => $next]);
        $products = json_decode(json_encode($response));
        foreach ($products->body->products as $product) {
            $prod = mProduct::where('shopify_id', $product->id)->first();
            if ($prod == null) {
                $prod = new mProduct();
            }
            $prod->shopify_id = $product->id;
            $prod->title = $product->title;
            $prod->status = $product->status;
            $prod->product_json = json_encode($product);
            $prod->save();
        }
        if (isset($products->link->next)) {
            $this->shopifyProductsSync($products->link->next);
        }
        return Redirect::tokenRedirect('products.all', ['notice' => 'Products Synced Successfully']);
    }
    public function syncPricing(){
        $country = Country::latest()->get();
//        foreach ($country as $item){
//            saveProduct::dispatchAfterResponse('UK');
//        }

//                $country = Country::all();
        foreach ($country as $item){
            $this->syncProducts($item->code, null);
        }
        return Redirect::tokenRedirect('home',['notice'=>'Products Syncing']);
    }
    public function syncProducts($code, $cursor = null){
        if ($cursor){
            $query = '{
  products(first: 20, after:"'.$cursor.'") {
    edges{
    cursor
    node {
      id
      title
      totalInventory
      status
      variants(first: 15) {
        edges {
          node {
            id
            inventoryQuantity
            contextualPricing(context: {country: '.$code.'}) {
              price {
                amount
                currencyCode
              }
              compareAtPrice{
                amount
              }
            }
          }
        }
      }
    }
  }
  }
}';
        }else {
            $query = '{
  products(first: 20) {
    edges{
    cursor
    node {
      id
      title
      totalInventory
      status
      variants(first: 15) {
        edges {
          node {
            id
            inventoryQuantity
            contextualPricing(context: {country: '.$code.'}) {
              price {
                amount
                currencyCode
              }
              compareAtPrice{
                amount
              }
            }
          }
        }
      }
    }
  }
  }
}';
        }
        $user = User::first();
        $shop = $user->api()->graph($query);
        $products = json_decode(json_encode($shop));
//        dd($products);
        $last_cursor = null;
        if ($products->errors != false){
            dd($products);
//            if ($products->body->extensions->cost->actualQueryCost == null){
//                if (Session::has('sleep30')){
//                    sleep(40);
//                    session()->put('sleep40', '40');
//                    $this->syncProducts($code, $cursor);
//                }else {
//                    sleep(30);
//                    session()->put('sleep30', '30');
//                    $this->syncProducts($code, $cursor);
//                }

//            }
        }
//        dd($products, $i+1);
        if($products->errors != true){
            if (count($products->body->data->products->edges)>0) {
                foreach ($products->body->data->products->edges as $i=>$product) {
//                    dd($product->node->variants->edges);
                    $prod = Product::where('product_id', $product->node->id)->where('code', $code)->first();
                    if ($prod == null) {
                        $prod = new Product();
//                    }
                        $prod->product_id = $product->node->id;
                        $variant_id = explode('gid://shopify/ProductVariant/', $product->node->variants->edges[0]->node->id);
                        $prod->variant_id = $variant_id[1];
                        $prod->title = $product->node->title;
                        $prod->code = $code;
                        if ($product->node->totalInventory > 0) {
                            $prod->stock = 'in_stock';
                        } else {
                            $prod->stock = 'no_stock';
                        }
                        $prod->currency_code = $product->node->variants->edges[0]->node->contextualPricing->price->currencyCode;
                        $prod->price = number_format($product->node->variants->edges[0]->node->contextualPricing->price->amount, 2) . $product->node->variants->edges[0]->node->contextualPricing->price->currencyCode;
                        if ($product->node->variants->edges[0]->node->contextualPricing->compareAtPrice) {
                            if ($product->node->variants->edges[0]->node->contextualPricing->compareAtPrice->amount == '0.0') {
                                $prod->sale_price = '';
                            } else {
                                $prod->sale_price = number_format($product->node->variants->edges[0]->node->contextualPricing->compareAtPrice->amount, 2) . $product->node->variants->edges[0]->node->contextualPricing->price->currencyCode;
                            }
                        } else {
                            $prod->sale_price = '';
                        }
                        $prod->status = $product->node->status;
                        $prod->save();
                    }
                    foreach ($product->node->variants->edges as $variant) {
//                            dd($variant);
                        $variant_id = explode('gid://shopify/ProductVariant/', $variant->node->id);
                        $p_variant = Variant::where('variant_id', $variant_id[1])
                            ->where('product_id', $prod->id)
                            ->first();
                        if ($p_variant == null) {
                            $p_variant = new Variant();
//                            }
                            $p_variant->variant_id = $variant_id[1];
                            $p_variant->title = $product->node->title;
                            $p_variant->shopify_product_id = $product->node->id;
                            $p_variant->country_code = $code;
                            $p_variant->status = $product->node->status;
                            $p_variant->stock = $variant->node->inventoryQuantity;
                            $p_variant->currency_code = $variant->node->contextualPricing->price->currencyCode;
                            $p_variant->price = number_format($variant->node->contextualPricing->price->amount, 2) . $variant->node->contextualPricing->price->currencyCode;
                            if ($variant->node->contextualPricing->compareAtPrice) {
                                if ($variant->node->contextualPricing->compareAtPrice->amount == '0.0') {
                                    $p_variant->sale_price = '';
                                } else {
                                    $p_variant->sale_price = number_format($variant->node->contextualPricing->compareAtPrice->amount, 2) . $variant->node->contextualPricing->price->currencyCode;
                                }
                            } else {
                                $p_variant->sale_price = '';
                            }
//                            $p_variant->product_id = $prod->id;
                            $p_variant->save();
                        }
                    }
                    }
//                    dd($prod);
                    if (end($products->body->data->products->edges) == $product) {
                        if ($product->cursor) {
                            $last_cursor = $product->cursor;
                        }
                    }
                }
            }else{
                return Redirect::tokenRedirect('home',['notice'=>'Pricing Synced']);
            }
            sleep(20);
            if(isset($last_cursor) && !empty($last_cursor)) {
                $this->syncProducts($code, $last_cursor);
            }
    }
    public function export()
    {
//        return (new productsExport())->download('products.csv', \Maatwebsite\Excel\Excel::CSV);
        return Excel::download(new productsExport(), 'products.csv');
    }
    public function exportXML(){
        $products= Product::where('status', 'ACTIVE')->get();
        return response ()->view ('xml', [
            'products' => $products,
        ])->header ('Content-Type', 'text/xml');
    }
    public function exportMainXML(){
        $products= mProduct::where('status', 'ACTIVE')->get();
        $user = User::first();
        return response ()->view ('mainXml', [
            'products' => $products,
            'check' => $user->mainFeed
        ])->header ('Content-Type', 'text/xml');
    }
    public function exportTestXML(Request $request) {
        $offset = 0; // Initialize the offset

            do {
                // Query the database to get the next chunk of products
                $products = Product::whereNull('title')->skip($offset)
                    ->take(100) // Adjust the batch size as needed
                    ->get();

                foreach ($products as $product) {
                    $variants = Variant::where('product_id', $product->id)->get();
                    foreach ($variants as $variant){
                        $data = Variant::find($variant->id);

                        $data->title = $product->title;
                        $data->shopify_product_id = $product->product_id;
                        $data->country_code = $product->code;
                        $data->save();
//                        dd($data);
                    }
                }

                // Update the offset to fetch the next chunk
                $offset += 100; // Adjust the batch size as needed

                // Flush the output to send the current chunk to the client
                ob_flush();
                flush();
            } while (count($products) > 0); // Continue fetching until no more products are found

    return 'Good Ho gaya';
//        $response = new StreamedResponse();
//        $response->headers->set('Content-Type', 'text/xml');
//
//        $response->setCallback(function () {
//            // Output the XML declaration and opening tag
/*            echo '<?xml version="1.0" encoding="UTF-8"?>';*/
//            echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"><channel>';
//
//            $offset = 0; // Initialize the offset
//
//            do {
//                // Query the database to get the next chunk of products
//                $products = Variant::orderBy('id')
//                    ->skip($offset)
//                    ->take(1000) // Adjust the batch size as needed
//                    ->get();
//
//                foreach ($products as $product) {
////                    foreach ($product->hasProducts as $item) {
//                    $escapedTitle = htmlspecialchars($product->hasProduct->title, ENT_XML1);
//                    $escapedCode = htmlspecialchars($product->hasProduct->code, ENT_XML1);
//
//                    echo '<item>';
//                        echo '<g:id>' . $product->variant_id . '</g:id>';
//                    echo '<title><![CDATA[' . $escapedTitle . ']]></title>';
//                    echo '<g:override><![CDATA[' . $escapedCode . ']]></g:override>';
//
//
//                    if ($product->stock > 0) {
//                            echo '<g:availability>in stock</g:availability>';
//                        } else {
//                            echo '<g:availability>out of stock</g:availability>';
//                        }
//
//                        $product_id = explode('gid://shopify/Product/', $product->hasProduct->product_id);
//                        echo '<g:item_group_id>' . $product_id[1] . '</g:item_group_id>';
//                        echo '<g:price>' . $product->price . '</g:price>';
//
//                        if ($product->sale_price) {
//                            echo '<g:sale_price>' . $product->sale_price . '</g:sale_price>';
//                        }
//
//                        echo '</item>';
////                    }
//                }
//
//                // Update the offset to fetch the next chunk
//                $offset += 1000; // Adjust the batch size as needed
//
//                // Flush the output to send the current chunk to the client
//                ob_flush();
//                flush();
//            } while (count($products) > 0); // Continue fetching until no more products are found
//
//            // Output the closing tag
//            echo '</channel></rss>';
//        });
//        return $response;
    }
public function returnRequest(){
        $exchanges = ReturnRequest::latest()->get();
        return view('return')->with([
            'exchanges' => $exchanges
        ]);
}
public function returnRequestSave(Request $request){
        $data = new ReturnRequest();
        $data->name = $request['contact']['name'];
        $data->email = $request['contact']['email'];
        $data->telephone = $request['contact']['telephone'];
        $data->order_no = $request['contact']['Order No.'];
        $data->message = $request['contact']['body'];
//        $data->retun_json = $request['contact'];
        $data->save();
        return 'success';
}
public function returnRequestDelete($id){
        $exchange = ReturnRequest::find($id);
        $exchange->delete();
    return Redirect::tokenRedirect('return.all',['error'=>'Deleted']);
}
}
