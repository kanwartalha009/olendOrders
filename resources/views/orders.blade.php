@extends('layouts.index')
@section('content')
    <div class="row pt-3">

        <div class="col-lg-6 col-sm-6">
            <h5>Orders</h5>
        </div>
        <div class="col-lg-6 col-sm-6" style="display: flex;
    justify-content: flex-end;">
            <form action="{{ route('order.csv') }}" class="m-0" method="get">
                     <input type="hidden" class="form-control h-100" placeholder="search" name="search"
                               @if(isset($request->search)) value="{{ $request->search }}" @endif>
                        <button type="submit" class="btn btn-primary">Csv Export</button>

            </form>
            <a href="{{ route('order.sync') }}" target="_blank" class="btn btn-primary ml-2">Sync
                Orders</a>
        </div>
        <div class="col-lg-12 col-sm-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('home') }}" method="get">
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                <input type="text" class="form-control h-100" placeholder="search" name="search"
                                       @if(isset($request->search)) value="{{ $request->search }}" @endif>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <button type="submit" class="btn btn-primary w-100 h-100">search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            @if(count($orders)>0)
                <table class="table table-hover">
                    <thead>
                    <tr data-url="head">
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 5%;">Order</th>
                        <th scope="col" style="width: 10%;">Date</th>
                        <th scope="col" style="width: 10%;">Total</th>
                        <th scope="col" style="width: 10%;">Payment status</th>
                        <th scope="col" style="width: 15%;">Fulfillment status</th>
                        <th scope="col" style="width: 10%;">Items</th>
                        <th scope="col" style="width: 35%;">Preorder</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $i=>$order)
                        @php
                            $items = count($order->has_items);
                        @endphp
                        <tr data-url="#">
                            <th scope="row">{{ ++$i }}</th>
                            <td>
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">{{ $order->order_name }}</a>
                            </td>
                            <td>
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">{{  \Carbon\Carbon::parse( $order->order_created_at)->format('d M H:i') }}</a>
                            </td>
                            <td>
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">${{ $order->current_total_price }}</a>
                            </td>
                            <td class="text-capitalize">
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">
                                    @if($order->financial_status) <span
                                        class="badge bg-light">{{ $order->financial_status }}</span> @else <span
                                        class="badge bg-warning">Unpaid</span> @endif
                                </a>
                            </td>
                            <td class="text-capitalize">
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">
                                    @if($order->fulfillment_status)<span
                                        class="badge bg-light">{{ $order->fulfillment_status }}</span>@else<span
                                        class="badge bg-warning">Unfulfilled</span> @endif
                                </a>
                            </td>
                            <td>
                                <a href="https://admin.shopify.com/store/{{$shop}}/orders/{{ $order->order_id }}" target="_blank">{{ $items }}@if($items == 1)
                                        Item @else Items @endif</a></td>
                            <td>@foreach($order->has_items as $i=>$item)@if($item->property) {{ $item->property }} @endif @if(count($order->has_items) > 1) <br> @endif @endforeach</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $orders->appends(['search' => $request->search])->links() }}
            @else
                <h6 class="text-center mt-5">No Order Found !</h6>
            @endif
        </div>
    </div>
@endsection

