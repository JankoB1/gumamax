@foreach ($user->projects() as $project)
    <div id="#wdg-project-{!! $project->project_id !!}" class="widget-box transparent collapsed">
        <div class="widget-header widget-header-large">
            <h5 class="widget-title">{!! $project->project_name !!}</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-pencil" data-icon-show="fa-pencil" data-icon-hide="fa-minus"></i>
                </a>
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                <div class="col-sm-12 infobox-container">
                <!-- #section:profile/partners.infobox -->
                    @foreach($user->members as $member)
                        <a href="{!! url(route('crm.member.edit', ['id'=>$member->id])) !!}">
                            <div id="callback-request-count" class="infobox infobox-wood">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-industry"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">{!! $member->erp_partner_id !!}</span>
                                    <div class="infobox-content"> {!! smartTruncate($member->name, 19, false) !!}</div>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            <!-- #section:profle/partner.infobox.stat -->
            </div>
        </div>
    </div>
@endforeach
