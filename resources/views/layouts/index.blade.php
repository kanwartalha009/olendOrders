<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Management</title>
    <link rel="stylesheet" href="{{ asset('polished.min.css') }}">
    <style>
        .chosen-container-multi .chosen-choices li.search-choice {
            background: black;
            color: white;
        }
        .chosen-container.chosen-container-multi {
            width: 100% !important;
        }
        .chosen-search-input, ul.chosen-choices {
            width: 100%;
            padding: 0.425rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 2px;
        }
        .grid-highlight {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background-color: #5c6ac4;
            border: 1px solid #202e78;
            color: #fff;
        }

        hr {
            margin: 6rem 0;
        }

        hr + .display-3,
        hr + .display-2 + .display-3 {
            margin-bottom: 2rem;
        }

        .top-menu li {
            display: inline;
        }

        .polished-sidebar-menu li.active {
            padding-bottom: 5px;
            border-bottom: 0.5px solid white;
        }

        .btn-dark {
            background: black;
        }

        .bg-dark {
            background: black;
        }

        .text-dark {
            color: black;
        }

        .btn.btn-dark:hover, .btn.btn-dark:visited {
            background: black;
        }

        img.lazy {
            width: 100%;
            height: 100%;
        }
        html, body {
            background: #F6F6F7 !important;
        }
         ul.polished-sidebar-menu li {
             display: inline;
             padding: 0 8px;
         }
        ul.polished-sidebar-menu li a {
            color: white;
        }
        div#product-meta-keywords_tagsinput{
            padding: 10px;
        }
        div.tagsinput span.tag{
            padding: 3px 15px;
            -webkit-border-radius: 5px;
        }
        .navbar{
            background-color: #447464 !important;
        }
        a.btn.btn-primary, button.btn.btn-primary, div.tagsinput span.tag, input.btn.btn-primary, .bg-primary-lighter {
            background: #006E52;
            border: transparent;
            box-shadow: inset 0 1px 0 0 #008060, 0 1px 0 0 rgb(22 29 37 / 5%), 0 0 0 0 transparent;
            border-radius: 5px;
        }
        .page-item.active .page-link {
            background-color: #006E52;
            border-color: #006E52;
        }
        .bg-primary, .bg-success{
            background-color: #006E52 !important;
        }
        .bg-warning {
            background-color: #FEEA8A !important;
            color: #202223 !important;
            padding: 5px 10px;
            border-radius: 10px;
        }
        a, .text-primary{
            color: black;
        }
        .bg-warning-light {
            background-color: #d1dfca !important;
        }
        .border-warning-light{
            border-color: transparent !important;
        }
        .bg-warning-light a.text-primary, .bg-warning-light a.text-primary:hover{
            color: #fb7d2f !important;
        }
        .form-control:focus, .dataTables_wrapper input:focus[type="search"] {
            border-color: #719c84 !important;
        }
        a:hover {
            color: #006E52;
        }
        .add_new_item{
            color: #719c84;
            cursor: pointer;
        }
        h3.remove_item {
            color: #a81313;
            cursor: pointer;
        }
        button.btn.btn-outline-primary {
            color: #006E52;
            border-color: #006E52;
        }

        .btn-outline-primary:hover {
            background: #006E52;
            color: white !important;
            border-color: #006E52;
        }

        .bg-loader {
            height: 150px;
            background: rgba(35, 46, 60, 0.08) url(http://i.stack.imgur.com/FhHRx.gif) 50% 50% no-repeat;
        }
    </style>
    <?php
    header("Content-Security-Policy: frame-ancestors https://".\Illuminate\Support\Facades\
    Auth::user()->name." https://admin.shopify.com;");
    ?>
</head>

<body>

<nav class="navbar navbar-expand p-0 bg-dark">
    <div class="navbar-brand col-xs-12 col-md-12 col-lg-12 mr-0">
        <ul class="polished-sidebar-menu top-menu mb-0 ml-0 ">
            <li class="{{ \Illuminate\Support\Facades\Request::is('/')? 'active': '' }} mr-4"><a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('home') }}" class="text-white">Products</a></li>
{{--            <li class=" mr-4"><a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('pricing.sync') }}" class="text-white">Sync</a></li>--}}
            <li class=" mr-4"><a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('country.all') }}" class="text-white">Country</a></li>
@php $user = \App\Models\User::first(); @endphp
            @if($user->name == 'beatriz-536.myshopify.com')
            <li class=" mr-4"><a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('products.all') }}" class="text-white">Main Store Products</a></li>
            @endif
            @if($user->name == 'moondaybrand.myshopify.com')
                <li class=" mr-4"><a href="{{ \Illuminate\Support\Facades\URL::tokenRoute('return.all') }}" class="text-white">Return & Exchange</a></li>
            @endif
        </ul>
    </div>

</nav>
<div class="container-fluid h-100 p-0">
    <div style="min-height: 100%" class="flex-row d-flex align-items-stretch m-0">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="row pl-5 pr-5 pt-4">
                <div class="col-md-12">
                    <span>   @include('layouts.flash_message') </span>
                    @yield('content')
                </div>
            </div>

        </div>
    </div>
</div>
@if(\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled') && \Osiset\ShopifyApp\Util::useNativeAppBridge())
    <script src="https://unpkg.com/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
    <script src="https://unpkg.com/@shopify/app-bridge-utils{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
    <script
        @if(\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled'))
        data-turbolinks-eval="false"
        @endif
    >
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var utils = window['app-bridge-utils'];
        var createApp = AppBridge.default;
        var app = createApp({
            apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name ) }}",
            host: "{{ \Request::get('host') }}",
            forceRedirect: true,
        });
    </script>

    @include('shopify-app::partials.token_handler')
    @include('shopify-app::partials.flash_messages')
@endif
{{--@if(\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled'))--}}

{{--    <script--}}
{{--        src="https://unpkg.com/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>--}}
{{--    <script--}}
{{--        src="https://unpkg.com/@shopify/app-bridge-utils{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>--}}

{{--    @php--}}

{{--        $host = base64_decode(session('host'));--}}

{{--          if (str_contains($host, 'admin.shopify.com') || $host==''){--}}
{{--              $shopOrigin= 'admin.shopify.com';--}}
{{--          }else{--}}
{{--              $shopOrigin=Auth::user()->name;--}}
{{--          }--}}

{{--    @endphp--}}
{{--    <script--}}
{{--        @if(\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled'))--}}
{{--        data-turbolinks-eval="false"--}}
{{--        @endif--}}
{{--    >--}}
{{--        var AppBridge = window['app-bridge'];--}}
{{--        var actions = AppBridge.actions;--}}
{{--        var utils = window['app-bridge-utils'];--}}
{{--        var createApp = AppBridge.default;--}}
{{--        var app = createApp({--}}
{{--            apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name ) }}",--}}
{{--            shopOrigin: "{{ $shopOrigin}}",--}}
{{--            // shopOrigin: "admin.shopify.com",--}}
{{--            --}}{{--host: "{{ \Request::get('host') }}",--}}
{{--            host: "{{ session('host') }}",--}}
{{--            forceRedirect: true,--}}
{{--        });--}}
{{--    </script>--}}

{{--    @include('shopify-app::partials.token_handler')--}}
{{--    @include('shopify-app::partials.flash_messages')--}}
{{--@endif--}}
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"
        crossorigin="anonymous"></script>
</body>

</html>
