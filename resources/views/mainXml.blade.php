<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>Flabelus</title>
        <link>https://flabelus.com</link>
        @foreach($products as $product)
            <?php
            $variants = json_decode($product->product_json);
            $tags = explode(',', $variants->tags);
            ?>
            @foreach($variants->variants as $variant)
                <item>
                    <g:id>{{ $variant->id }}</g:id>
                    <g:title><![CDATA[{{ $variants->title }}]]></g:title>
                    <g:description><![CDATA[{{ strip_tags($variants->body_html) }}]]></g:description>
                    <g:product_type>{{ $variants->product_type }}</g:product_type>
                    <g:link><![CDATA[https://flabelus.com/products/{{ $variants->handle }}?variant={{ $variant->id }}]]></g:link>
                    @foreach($variants->images as $image)
                        @if($image->id == $variant->image_id)
                            <g:image_link><![CDATA[{{ $image->src }}]]></g:image_link>
                        @else
                            <g:additional_image_link><![CDATA[{{ $image->src }}]]></g:additional_image_link>
                        @endif
                    @endforeach
                    @if($variant->inventory_quantity > 0)
                        <g:availability>in stock</g:availability>
                    @else
                        <g:availability> out of stock</g:availability>
                    @endif
                    <g:condition><![CDATA[New]]></g:condition>
                    <quantity_to_sell_on_facebook>{{ $variant->inventory_quantity }}</quantity_to_sell_on_facebook>
                    <g:inventory>{{ $variant->inventory_quantity }}</g:inventory>
                    <g:price>{{ $variant->price }} EUR</g:price>
                    @if($variant->compare_at_price != '0.0')
                        <g:sale_price>{{ $variant->compare_at_price }} EUR</g:sale_price>
                    @endif
                    @if($variant->sku)
                    <g:mpn>{{ $variant->sku }}</g:mpn>
                    @endif
                    @if($variant->barcode)
                    <g:gtin>{{ $variant->barcode }}</g:gtin>
                    @endif
                    <g:shipping_weight>{{ $variant->weight }} {{ $variant->weight_unit }}</g:shipping_weight>
                    <g:item_group_id>{{ $variant->product_id }}</g:item_group_id>
                    @foreach($tags as $i=>$tag)
                        <g:custom_label_{{ $i }}><![CDATA[{{ $tag }}]]></g:custom_label_{{ $i }}>
                    @endforeach
                    {{--            <g:google_product_category><![CDATA[Apparel & Accessories > Shoes]]></g:google_product_category>--}}
                    {{--                <g:gender><![CDATA[Female]]></g:gender>--}}
                    @if($variant->option1 != 'Default Title')
                    <g:size><![CDATA[{{ $variant->option1 }}]]></g:size>
                    @endif
                    @if($variant->option2)
                    <g:color><![CDATA[{{ $variant->option2 }}]]></g:color>
                    @endif
                    <g:brand><![CDATA[{{ $variants->vendor }}]]></g:brand>
                    {{--                <g:age_group><![CDATA[Adult]]></g:age_group>--}}
                </item>
                @if($check == 'first')
                    @break
                    @endif
            @endforeach
        @endforeach
    </channel>
</rss>
