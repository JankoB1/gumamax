
<div class="row">
    <div class="col-md-12 px10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Transakcija - {{$transaction->descriptor}} <span class="pull-right">{{$transaction->timestamp}}</span></h3>
            </div>
            <div class="panel-body">
                <div class="transaction-box">
                    <div class="transaction-segment">
                        <div>Porudžbenica</div>
                        <div>
                            Broj: {{$order->number}}<br>
                            Cart id: {{$order->cart_id}} <br>
                            Order id: {{$order->id}} <br>
                            ErpRefId: {{$order->erp_reference_id}}
                        </div>
                    </div>     
                    <div class="transaction-segment">
                        <div>Kartica</div>
                        <div>
                            {{$transaction->paymentBrand. ' **** '. $transaction->card->last4Digits}}<br>                            
                            Iznos: <strong>{{$transaction->amount. ' '. $transaction->currency}}</strong><br>
                            <strong>{{$transaction->transaction_actions}}</strong><br>
                            Vlasnik: {{$transaction->card->holder}}<br>
                            Adresa: {{$transaction->billing->street1}}<br>
                            Mesto: {{$transaction->billing->postcode. ' '. $transaction->billing->city}}<br>
                            Država: {{$transaction->billing->country}}
                        </div>
                    </div>   
                    <div class="transaction-segment">
                        <div>Kupac</div>
                        <div>
                            {{$transaction->customer->givenName. ' '. $transaction->customer->surname}}<br>
                            {{$transaction->customer->email}}<br>
                            {{$transaction->customer->phone ?? ''}}<br>
                            {{$transaction->customer->ip}}
                        </div>
                    </div>   
                    <div class="transaction-segment">
                        <div>Isporuka</div>
                        <div>
                            {{$transaction->shipping->street1}}<br>
                            {{$transaction->shipping->street2}}<br>
                            {{$transaction->shipping->postcode. ' '. $transaction->shipping->city}}<br>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 px20">
        <div class="transaction-caption">Operacije</div>        
        <div class="col-md-6">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" id="backoffice-operations-tab">
                    <li @if (in_array($transaction->paymentType, ['CP','RV'])) class="disabled" @endif>
                        <a data-toggle="tab" data-operation="CP">
                            <i class="green fa fa-arrow-circle-right bigger-130"></i>
                            Capture
                        </a>
                    </li>        
                    <li @if (in_array($transaction->paymentType, ['PA','RF','RV'])) class="disabled" @endif>
                        <a data-toggle="tab" data-operation="RF">
                            <i class="red fa fa-arrow-circle-left bigger-130"></i>
                            Refund
                        </a>
                    </li>        
                    <li @if (in_array($transaction->paymentType, ['CP','RF','RV'])) class="disabled" @endif>
                        <a data-toggle="tab" data-operation="RV">
                            <i class="red fa fa-times bigger-130"></i>
                            Reversal
                        </a>
                    </li>
                </ul>
        
                <div class="tab-content backoffice-tab-content">
                    <form id="frmBackofficeOp" action="">
                        <input type="hidden" name="full_amount" id="full_amount" value="{{$transaction->amount}}">
                        <input type="hidden" name="payment_type" id="payment_type" value="">
                        <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                        <div class="backoffice-controls">
                            <div>
                                <label class="control-label">Iznos:</label>

                                <div class="controls">
                                    <label>
                                        <input name="amount_type" type="radio" class="ace" value="full" checked="checked">
                                        <span class="lbl">
                                            Pun iznos ({{$transaction->amount. ' '. $transaction->currency}})
                                        </span>
                                    </label>

                                    <label>
                                        <input name="amount_type" type="radio" class="ace" value="partial">
                                        <span class="lbl">Deo iznosa</span>
                                        <input type="text" class="input-small ml5" name="partial_amount" id="partial_amount" disabled="">
                                    </label>
                                </div>
                            </div> 
                            <div class="ml7 mb5">
                                <button id="submit" type="submit" class="btn disabled btn-small btn-inverse">Pošalji</button>
                            </div>                     
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default backoffice-result">
                <div id="result" class="panel-body"></div>
            </div>
        </div>
    </div>
</div>