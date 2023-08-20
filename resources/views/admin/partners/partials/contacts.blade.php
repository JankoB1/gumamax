{!!Former::horizontal_open()->url(url('partner/'.$partner->partner_id))->secure()!!}
{!!Former::populate($partner->about) !!}
<fieldset>
    <div class="col-xs-12 col-sm-12">
        <legend>{!! _('Contact') !!}</legend>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                {!! Former::text('about.contact_person_firstname')->label('Ime')->placeholder('Ime osobe za kontakt') !!}
                {!! Former::text('about.contact_person_lastname')->label('Prezime')->placeholder('Prezime osobe za kontakt') !!}
            </div>
        </div>
    </div>
</fieldset>
{!!Former::close()!!}