<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        @php $user = \App\Models\User::first();@endphp
        @if($user->name == 'augusta-the-brand.myshopify.com')
            <title>Augusta</title>
            <link>https://augustathebrand.com</link>
        @elseif($user->name == 'beatriz-536.myshopify.com')
        <title>Flabelus</title>
        <link>https://flabelus.com</link>
        @elseif($user->name == 'olendbackpacks.myshopify.com')
            <title>Olend</title>
            <link>https://www.olend.net</link>
        @elseif($user->name == 'my-little-cozmo.myshopify.com')
            <title>My Little Cozmo</title>
            <link>https://mylittlecozmo.com</link>
        @elseif($user->name == 'mellerbrand2016.myshopify.com')
            <title>Meller</title>
            <link>https://mellerbrand.com</link>
        @endif
    @foreach ($products as $product)
        <item>
            <title>{{ $product->title }}</title>
            <g:id>{{ $product->variant_id }}</g:id>
            <g:override>{{ $product->code }}</g:override>
            <g:availability>{{ $product->stock }}</g:availability>
            <g:price>{{ $product->price }}</g:price>
            @if($product->sale_price)
            <g:sale_price>{{ $product->sale_price }}</g:sale_price>
            @endif
        </item>
    @endforeach
    </channel>
</rss>
