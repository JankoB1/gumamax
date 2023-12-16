<div class="hidden-sm hidden-xs action-buttons">
    <a class="actionEdit blue" href="javascript:editRecord({!! $model->partner_id !!})" title="Odgovori" data-id="{!! $model->partner_id !!}">
        <i class="ace-icon fa fa-pencil bigger-130"></i>
    </a>
</div>

<div class="hidden-md hidden-lg">

    <div class="inline position-relative">
        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
        </button>

        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
            <li>
                <a href="javascript:editRecord({!! $model->partner_id !!})" class="tooltip-info actionReplay" data-rel="tooltip" title="Odgovori" data-original-title="Odgovori">
                    <span class="blue"><i class="ace-icon fa fa-pencil bigger-120"></i></span>
                </a>
            </li>
        </ul>
    </div>
</div>
