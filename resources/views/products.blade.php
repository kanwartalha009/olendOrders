@extends('layouts.index')
@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xs-9">
            <h4>Market Products</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-3 text-right">
            <a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('pricing.sync') }}" class="btn btn-primary mr-2">Sync Shopify</a>
            <a href="{{ route('xml.export') }}" class="btn btn-primary" target="_blank">XML Link</a>
{{--            <a href="{{ route('product.export') }}" class="btn btn-primary" target="_blank">Export CSV</a>--}}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Feed Settings</button>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="{{ route('feed.settings') }}" method="get">
                        <h5>Feed Settings</h5>
                        <div class="form-group ml-3 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="marketFeed" id="gridRadios1" value="all" @if($user->marketFeed == 'all') checked @endif>
                                <label class="form-check-label" for="gridRadios1">
                                    Include All Variants
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="marketFeed" id="gridRadios2" value="first" @if($user->marketFeed == 'first') checked @else checked @endif>
                                <label class="form-check-label" for="gridRadios2">
                                    Only Include First Variant
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @if(count($products) > 0)
        <div class="row pt-3">
            <div class="col-md-12">

                <table class="table shadow-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Id</th>
                        <th scope="col">Override</th>
                        <th scope="col">Availability</th>
                        <th scope="col">Price</th>
                        <th scope="col">Compare at Price</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
<!--                    --><?php //$i = ($products->currentpage()-1)* $products->perpage() + 1; ?>
                    @foreach($products as $i => $product)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $product->title }}</td>
                            <td>{{ $product->variant_id }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->stock }} </td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->status }}</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
{{--                {!! $products->links() !!}--}}

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
