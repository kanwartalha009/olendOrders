@extends('layouts.index')
@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xs-9">
            <h4>Country</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-3">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">Add Country</button>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="{{ route('countryCode.save') }}" method="get">
                    <h5>New Country</h5>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Country Name" required>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" name="code" placeholder="Country Code" required>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @if(count($countries) > 0)
        <div class="row pt-3">
            <div class="col-md-12">

                <table class="table shadow-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Country</th>
                        <th scope="col">Code</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($countries as $i=>$country)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $country->name }}</td>
                            <td>{{ $country->code }}</td>
                            <td>
                                <div class="btn-group-sm float-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal{{ $country->id }}">Update</button>
                                    <a href="{{ route('countryCode.delete', $country->id) }}" class="btn btn-primary">Delete</a>
                                </div>
                            </td>
                        </tr>

                        <div id="myModal{{ $country->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('countryCode.update', $country->id) }}" method="get">
                                            <h5>Update Country</h5>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" value="{{ $country->name }}" name="name" placeholder="Country Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Code</label>
                                                <input type="text" class="form-control" value="{{ $country->code }}" name="code" placeholder="Country Code" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary float-right">Update</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <h5 class="text-capitalize text-dark"> No Country Available</h5>
            </div>
        </div>
    @endif
@endsection
