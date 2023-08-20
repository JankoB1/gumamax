<div class="hidden-sm hidden-xs action-buttons">
    @if ($status=='opened')
        <a class="actionReplay blue" href="javascript:replyToCallbackReq({!! $model->id !!})" title="Odgovori" data-id="{!! $model->id !!}">
            <i class="ace-icon fa fa-reply bigger-130"></i>
        </a>
    @else

    @endif
</div>

<div class="hidden-md hidden-lg">

    <div class="inline position-relative">
        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
        </button>

        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
            @if ($status=='opened')
                <li>
                    <a href="javascript:replyToCallbackReq({!! $model->id !!})" class="tooltip-info actionReplay" data-rel="tooltip" title="Odgovori" data-original-title="Odgovori">
                        <span class="blue"><i class="ace-icon fa fa-reply bigger-120"></i></span>
                    </a>
                </li>
            @else
                <li>

                </li>
            @endif
        </ul>
    </div>
</div>

