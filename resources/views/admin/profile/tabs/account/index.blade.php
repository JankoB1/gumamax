<div class="col-xs-12 col-sm-6">
    <div id="#wdg-user-account" class="widget-box transparent collapsed">
        <div class="widget-header widget-header-large">
            <h5 class="widget-title">{!! $user->fullName() !!}</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-pencil" data-icon-show="fa-pencil" data-icon-hide="fa-minus"></i>
                </a>
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                @include('admin.profile.tabs.account.user')
            </div>
        </div>
    </div>
    <div id="#wdg-user-customer-type" class="widget-box transparent collapsed">
        <div class="widget-header widget-header-large">
            <h5 class="widget-title">{!! ($customer->customerType)?$customer->customerType->description:_('Customer type') !!}</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-pencil" data-icon-show="fa-pencil" data-icon-hide="fa-minus"></i>
                </a>
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                @include('admin.profile.tabs.account.customer-type')
            </div>
        </div>
    </div>
    <div id="#wdg-user-address" class="widget-box transparent collapsed">
        <div class="widget-header widget-header-large">
            <h5 class="widget-title">{!! ($address->city_name)?smartTruncate(($address->city_name.', '. $address->address), 45):(_('Address')) !!}</h5>

            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-pencil" data-icon-show="fa-pencil" data-icon-hide="fa-minus"></i>
                </a>
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                @include('admin.profile.tabs.account.address')
            </div>
        </div>
    </div>
    <div id="#wdg-user-password" class="widget-box transparent collapsed">
        <div class="widget-header widget-header-large">
            <h5 class="widget-title">{!! _('Password') !!}</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-pencil" data-icon-show="fa-pencil" data-icon-hide="fa-minus"></i>
                </a>
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                @include('admin.profile.tabs.account.password')
            </div>
        </div>
    </div>

</div>


