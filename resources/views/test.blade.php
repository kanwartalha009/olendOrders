<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        @php use App\Models\User;$user = User::first()@endphp
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
            @foreach($product->hasProducts as $item)
                <item>
                    <g:id>{{ $item->variant_id }}</g:id>
                    <title>{{ $product->title }}</title>
                    <g:override>{{ $product->code }}</g:override>
                    @if($item->stock > 0)
                        <g:availability>in stock</g:availability>
                    @else
                        <g:availability> out of stock</g:availability>
                    @endif
                    @php $product_id = explode('gid://shopify/Product/', $product->product_id) @endphp
                    <g:item_group_id>{{ $product_id[1] }}</g:item_group_id>
                    <g:price>{{ $item->price }}</g:price>
                    @if($item->sale_price)
                        <g:sale_price>{{ $item->sale_price }}</g:sale_price>
                    @endif
                </item>
            @endforeach
        @endforeach
    </channel>
</rss>
