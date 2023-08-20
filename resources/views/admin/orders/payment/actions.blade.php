<div class="hidden-sm hidden-xs action-buttons">
    <a class="blue" href="#" 
        onclick="javascript:createPayment({{ sprintf('%d,%d,%s,%d', $model->id, $model->payment_method_id, $model->total_amount_with_tax,
            $model->user_id) }});return false;">
        <i class="ace-icon fa fa-eur bigger-130"></i>
    </a>
    <a class="green" id="btnShowPayment" href="#" data-order-id="{{$model->id}}">
        <i class="ace-icon fa fa-list-ol bigger-130"></i>
    </a>
</div>

<div class="hidden-md hidden-lg">

    <div class="inline position-relative">
        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
        </button>

        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
            <li>
                <a href="javascript:createPayment({!! $model->id.','.$model->payment_method_id.','.$model->total_amount_with_tax !!})" class="tooltip-info" data-rel="tooltip" title="" data-original-title="Knjiži uplatu">
                    <span class="blue"><i class="ace-icon fa fa-eur bigger-120"></i></span>
                </a>
            </li>
            <li>
                <a href="javascript:showPayment({!! $model->id !!})" class="tooltip-info" data-rel="tooltip" title="Pogledaj uplate">
				    <span class="green"><i class="ace-icon fa fa-list-ol bigger-120"></i></span>
                </a>
            </li>

        </ul>
    </div>
</div>
