<div class="hidden-sm hidden-xs action-buttons">
    @if ($status=='opened')
        <a class="blue" href="javascript:replyToContact({!! $model->id !!})" title="Odgovori">
            <i class="ace-icon fa fa-reply bigger-130"></i>
        </a>
    @else
        <a class="green" href="javascript:showContact({!! $model->id !!})" title="Pogledaj poruku">
            <i class="ace-icon fa fa-search bigger-130"></i>
        </a>
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
                    <a href="javascript:replyToContact({!! $model->id !!})" class="tooltip-info" data-rel="tooltip" title="Odgovori" data-original-title="Odgovori">
                        <span class="blue"><i class="ace-icon fa fa-reply bigger-120"></i></span>
                    </a>
                </li>
            @else

            <li>
                <a href="javascript:showContact({!! $model->id !!})" class="tooltip-info" data-rel="tooltip" title="Pogledaj poruku">
                    <span class="green"><i class="ace-icon fa fa-list-ol bigger-120"></i></span>
                </a>
            </li>
            @endif

        </ul>
    </div>
</div>

