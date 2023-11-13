@extends('layouts.index')
@section('content')
    <div class="row pt-3">

        <div class="col-lg-6 col-sm-6">
            <h5>Orders</h5>
        </div>
        <div class="col-lg-6 col-sm-6 text-right">
            <a href="}" class="btn btn-primary">Sync
                Orders</a>
        </div>
        <div class="col-lg-12 col-sm-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                <input type="text" class="form-control h-100" placeholder="search" name="query"
                                       @if(isset($query)) value="{{ $query }}" @endif>
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
                                <a href="#">{{ $order->order_name }}</a>
                            </td>
                            <td>
                                <a href="#">{{  \Carbon\Carbon::parse( $order->order_created_at)->format('d M H:i') }}</a>
                            </td>
                            <td>
                                <a href="#">${{ $order->current_total_price }}</a>
                            </td>
                            <td class="text-capitalize">
                                <a href="#">
                                    @if($order->financial_status) <span
                                        class="badge bg-light">{{ $order->financial_status }}</span> @else <span
                                        class="badge bg-warning">Unpaid</span> @endif
                                </a>
                            </td>
                            <td class="text-capitalize">
                                <a href="#">
                                    @if($order->fulfillment_status)<span
                                        class="badge bg-light">{{ $order->fulfillment_status }}</span>@else<span
                                        class="badge bg-warning">Unfulfilled</span> @endif
                                </a>
                            </td>
                            <td>
                                <a href="#">{{ $items }}@if($items == 1)
                                        Item @else Items @endif</a></td>
                            <td>@foreach($order->has_items as $i=>$item)@if($i == 1) <br> @endif @if($item->property) {{ $item->property }} @endif @endforeach</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h6 class="text-center mt-5">No Order Found !</h6>
            @endif
        </div>
    </div>
@endsection

