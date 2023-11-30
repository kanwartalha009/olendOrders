@extends('layouts.index')
@section('content')
    <div class="row pt-3">

        <div class="col-lg-6 col-sm-6">
            <h5>Non English</h5>
        </div>
        <div class="col-lg-12 col-sm-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('test.save') }}" method="get">
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                <textarea type="text" class="form-control h-100" placeholder="search" name="search"></textarea>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <button type="submit" class="btn btn-primary w-100 h-100">search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

