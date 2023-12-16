@extends('master')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop

@section('content')
   
    <div class="page-title content clearfix">
        <h1 class="pull-left">Rezultat transakcije</h1>
    </div>
    <div class="content">
        <div class="row mt15">        
            <div class="col-md-6 col-xs-12">                    
                <div class="payment-result-caption">Transakcija nije uspela.</div>
                <div class="well">
                    Greška: <strong>{!! $status->result->code !!}</strong><br>
                    Opis: {!! $status->result->description !!}<br>
                </div>
                <p>Kupovinu možete nastaviti promenom načina plaćanja ili korišćenjem druge platne kartice.</p>
                <p>U vezi informacija možete nam se obratiti na <strong>{!!config('gumamax.web_support_email_address')!!}</strong> 
                    ili putem telefona <strong>{{Config::get('gumamax.web_support_phone')}}</strong>.</p>
            </div>  
            <div class="col-md-6 col-xs-12">
                <div id="payment-method-change-box" class="panel panel-default mt10">
                    <div class="panel-heading p5">
                        <span class="payment-method-change-caption">Izmena načina plaćanja</span>
                    </div>
                    <div class="panel-body text-center p5">
                        @forelse ($available_payment_methods as $apm)
                            <button type="button" class="payment-method-btn btn btn-gray" data-id={{ $apm->payment_method_id }}  autocomplete="off">
                                <span class="fa {{ $apm->icon }} fa-2x" aria-hidden="true"></span>
                                <p>{{ $apm->description }}</p>
                          </button>        
                        @empty
                            
                        @endforelse
                    </div>
                    <div class="row">
                        <div class="col-12 text-center p10">
                            <button id="payment-method-submit" type="button" class="btn btn-primary">Izmeni</button>
                        </div>
                    </div>
                </div>                    
            </div>          
        </div>        
    </div>
@endsection

@section('js')
    <script> 
    
        $(function() {

            $("#payment-method-submit").on("click", function(){
                var btnSelected = $("#payment-method-change-box div.panel-body > button.active");
        
                if (btnSelected !== undefined) { 
                    showLoading();

                    $.ajax({
                        type: "post",
                        url: urlTo("/checkout/payment/method-change"),
                        data: {order_id: {!! $order->id !!},payment_method_id: btnSelected.data('id')},
                        dataType: "json"
                    }).done(function(data) {
                        if (data.error) {
                            hideLoading();
                            swal("Greška", "Nije uspela promena načina plaćanja", "warning");
                        } else {
                            //swal("", data.resource_path, "info");
                            window.location.href = data.resource_path;
                        }                
                    }).fail(function(xhr, textStatus, errorThrown) {
                        hideLoading();
                        swal("Greška", "Nije uspela promena načina plaćanja", "warning");                
                    });                   
                }
            });
        
            $("#payment-method-change-box").on("click", "div.panel-body > button", function() {                
                $("#payment-method-change-box div.panel-body > button").filter(function(index) {
                    return $(this).hasClass('active');
                }).removeClass('active');

                $(this).addClass('active');
            })
        });
    </script>
@stop  
