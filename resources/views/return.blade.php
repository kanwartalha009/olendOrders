@extends('layouts.index')
@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xs-9">
            <h4>Return & Exchange</h4>
        </div>

    </div>

    @if(count($exchanges) > 0)
        <div class="row pt-3">
            <div class="col-md-12">

                <table class="table shadow-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Eamil</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Order No</th>
                        <th scope="col">Message</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--                    --><?php //$i = ($products->currentpage()-1)* $products->perpage() + 1; ?>
                    @foreach($exchanges as $i => $exchange)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $exchange->created_at->tz('Europe/Madrid')->format('D, d M Y H:i:s') }}
                            </td>
                            <td>{{ $exchange->name }}</td>
                            <td>{{ $exchange->email }}</td>
                            <td>{{ $exchange->telephone }}</td>
                            <td>{{ $exchange->order_no }} </td>
                            <td>{{ $exchange->message }}</td>
                            <td>
                                <div class="btn-group-sm float-right">
                                    <a class="btn btn-primary" target="_blank" href="mailto:{{ $exchange->email }}">Reply</a>
                                    <a class="btn btn-primary" href="{{ route('return.delete', $exchange->id) }}">Delete</a>
            </div>
                            </td>
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
                <h5 class="text-capitalize text-dark"> No Message Available</h5>
            </div>
        </div>
    @endif
@endsection
