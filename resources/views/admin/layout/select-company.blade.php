    <a data-toggle="dropdown" class="dropdown-toggle" href="#" data-crm_company_id="{!! session('crmCompanyId') !!}">
        <i class="ace-icon fa fa-industry"></i>
        {!!  session('crmCompanyName') !!}
    </a>

    <ul class="dropdown-menu dropdown-navbar dropdown-menu-right dropdown-caret dropdown-close">
        @foreach($companies::all() as $company)
            <li>
                <a href="#" class="blue link-select-crm-company" data-crm_company_id="{!! $company->id !!}">
                    <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>{!! _($company->name) !!}
                </a>
            </li>
        @endforeach
    </ul>

@push('scripts')
    <script>
        $('.link-select-crm-company').on('click', function(e){
            var crmCompanyId = $(this).data('crm_company_id');
            window.location = updateQueryString('crmCompanyId', crmCompanyId);
        });
    </script>
@endpush
