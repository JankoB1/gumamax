
    
        <div class="row no-gutter">
            <div class="col-md-7 col-xs-12">
                <div class="order-checkout-caption">Online porudžbenica: {{$order->number}}</div>
                <div class="order-checkout-table">
                    <div class="items">
                        <table class="table">
                            <thead><tr>
                                <th></th>
                                <th>Artikal</th>
                                <th>Količina</th>
                                <th>Cena</th>
                                <th>Iznos</th>
                            </tr></thead>
                            <tbody>
                                @foreach ($order_items as $item)
                                <?php $cc = explode('/', $item->cat_no); ?>
                                    <tr>
                                        <td>{{ $loop->iteration. '.'}}</td>
                                        @if ($item->product_id == \Delmax\Models\ShippingMethod::ERP_SHIPPING_ARTIKAL_ID)
                                            <td>{!!$item->description. ' - '. $item->additional_description !!}</td>
                                        @else
                                            <td>{!!$item->manufacturer!!} {!!$item->additional_description!!}<br>
                                            CAI: {!!$cc[0]!!}
                                            
                                            @if(count($cc)==2)
                                               &nbsp;God.proizvodnje: {!!$cc[1]!!}
                                            @endif
                                            </td>
                                        @endif
                                        <td>{!!number_format($item->qty)!!}</td>
                                        <td>{!!number_format($item->price_with_tax,2,',','.')!!}</td>
                                        <td>{!!number_format($item->amount_with_tax, 2,',','.'). ' '. $order->currency_str!!}</td>
                                    </tr> 
                                @endforeach
                                <tr>
                                    <td colspan="4">Ukupno:</td>
                                    <td>{!!number_format($order->total_amount_with_tax,2,',','.'). ' '. $order->currency_str!!}</td>
                                </tr>                                
                            </tbody>
                        </table>                        
                    </div>
                </div>        
            </div>
            <div class="col-md-5 col-xs-12">
                <div class="order-checkout-caption">Prodavac</div>
                <div class="order-checkout-merchant">
                    Delmax d.o.o. Banovačka 42, Stara Pazova, Srbija
                </div>
            </div>     
        </div>
 
