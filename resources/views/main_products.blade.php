@extends('layouts.index')
@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xs-9">
            <h4>Main Store Products</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-3 text-right">
            <a href="{{ route('xml.main.export') }}" class="btn btn-primary mr-2" target="_blank">XML Link</a>
            <a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('products.sync') }}" class="btn btn-primary mr-2">Sync Shopify</a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Feed Settings</button>
        </div>
    </div>
c
    @if(count($products) > 0)
        <div class="row pt-3">
            <div class="col-md-12">

                <table class="table shadow-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $i = ($products->currentpage()-1)* $products->perpage() + 1; ?>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $i++ }}</td>

                            <td>{{ $product->title }}</td>
                            <td>{{ $product->status }}</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                {!! $products->links() !!}
            </div>
        </div>
    @else
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <h5 class="text-capitalize text-dark"> No Products Available</h5>
            </div>
        </div>
    @endif
@endsection
