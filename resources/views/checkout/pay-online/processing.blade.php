@extends('master')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop
@section('css')
    {!! Html::style(mix('assets/allsecure/payment.css')) !!}
@endsection

@section('content')
    <div>
        <div class="page-title content clearfix">
            <h1 class="pull-left">Plaćanje</h1>
        </div>

        <div class="paymentbox">
        
            @include('checkout.partials.order-items', ['order'=>$order, 'order_items'=>$order_items])

            <div id="paymentForm">
                <div class="terms-agreement-check">
                    <input type="checkbox" id="chkPaymentTerms" name="chkPaymentTerms" value="ok">
                    <label for="chkPaymentTerms">Potvrđujem saglasnost sa <a href="#" data-toggle="modal" data-target="#nacinPlacanja">
                        <strong>uslovima plaćanja.</strong></a></label>
                </div>
                <div id="card-placeholder" class="card-placeholder">                
                    <form action="{!! url('/checkout/payment/result')!!}" class="paymentWidgets" data-brands="VISA MASTER"></form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nacinPlacanja">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Način plaćanja</h4>
                </div>
                <div class="modal-body">
                    @include('static.payment-methods-content')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Zatvori</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')

    {!! Html::script(mix('js/payment.js')) !!} 

    {!! Html::script($cardProcessorUrl. '/v1/paymentWidgets.js?checkoutId='. $order->checkout_id) !!} 

@stop
