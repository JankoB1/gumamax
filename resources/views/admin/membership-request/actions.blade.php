<div class="hidden-sm hidden-xs action-buttons">
    @if (in_array($status, ['opened','rejected']))
    <a class="blue" href="javascript:approveRequest({!! $model->id !!})" title="Prihvati zahtev">
        <i class="ace-icon fa fa-check-square-o bigger-130"></i>
    </a>
    @endif
    @if(in_array($status, ['opened','approved']))
    <a class="red" href="javascript:rejectRequest({!! $model->id !!})" title="Odbij zahtev">
        <i class="ace-icon fa fa-times-circle bigger-130"></i>
    </a>
    @endif

    <a class="green" href="javascript:showRequest({!! $model->id.",'".$status."'" !!})" title="Pogledaj zahtev">
        <i class="ace-icon fa fa-search bigger-130"></i>
    </a>
</div>

<div class="hidden-md hidden-lg">

    <div class="inline position-relative">
        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
        </button>

        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
            @if (in_array($status, ['opened','rejected']))
            <li>
                <a href="javascript:javascript:approveRequest({!! $model->id !!})" class="tooltip-info" data-rel="tooltip" title="Prihvati zahtev" data-original-title="Prihvati zahtev">
                    <span class="blue"><i class="ace-icon fa fa-check-square-o bigger-120"></i></span>
                </a>
            </li>
            @endif
            @if(in_array($status, ['opened','approved']))
            <li>
                <a href="javascript:rejectRequest({!! $model->id !!})" class="tooltip-info" data-rel="tooltip" title="Odbij zahtev">
                    <span class="red"><i class="ace-icon fa fa-times-circle bigger-120"></i></span>
                </a>
            </li>
            @endif
            <li>
                <a href="javascript:showRequest({!! $model->id.",'".$status."'" !!})" class="tooltip-info" data-rel="tooltip" title="Pogledaj zahtev">
                    <span class="green"><i class="ace-icon fa fa-list-ol bigger-120"></i></span>
                </a>
            </li>

        </ul>
    </div>
</div>
