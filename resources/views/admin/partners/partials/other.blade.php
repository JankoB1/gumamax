{!!Former::horizontal_open()->url(url('partner/'.$partner->partner_id))->secure()!!}
{!!Former::populate($partner->about) !!}
<fieldset>
    <div class="col-xs-12 col-sm-12">
        <legend>{!! _('Descriptions') !!}</legend>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                {!! Former::textarea('about.short_text')->label('Kraći opis')->rows(2) !!}
            </div>
            <div class="col-xs-12 col-sm-6">
                {!! Former::textarea('about.long_text')->label('Duži opis')->rows(4) !!}
            </div>
        </div>
    </div>
</fieldset>
{!!Former::close()!!}