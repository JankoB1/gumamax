<div class="hidden-sm hidden-xs action-buttons">   
    
    @if (($model->payment_method_id == 5) && (($model->payment_status_id == 2) || ($model->payment_status_id == 1100)))            
        <button class="btn btn-minier btn-inverse" id="btnBackofficeShow" data-order-id="{{$model->id}}">
            <i class="fa fa-eur bigger-120"></i>
            Backoffice
        </button>
    @endif
</div>