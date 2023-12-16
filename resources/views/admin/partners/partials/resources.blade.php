<div class="row">
    <div class="col-sm-6">
        {!!Former::horizontal_open()
            ->action($formUrl)
            ->method($formMethod)
            ->id('form-partner-resources-gmx')
            ->secure() !!}
        <fieldset>
            <legend>{!!_('Resources') !!}</legend>
            {!! Former::populate($model) !!}
            {!! Former::text('number_of_platforms')->label('Broj platformi') !!}
            {!! Former::text('number_of_platforms_b')->label('Broj balanserki') !!}
            {!! Former::text('number_of_platforms_d')->label('Broj demonterki') !!}
            <hr>
            {!! Former::text('scheduling_period')->label('Period zakazivanja')->help('Period za koji je moguće zakazati montažu nakon prijema guma u servis (u satima)') !!}

            {!! Former::setOption('automatic_label', false) !!}
            {!! Former::checkbox('online_scheduling')->text('Online zakazivanje') !!}
            {!! Former::checkbox('google_local_service')->text('Da li ste registrovani na google mapi?') !!}
            {!! Former::checkbox('place_of_delivery')->text('Mogućnost isporuke') !!}
            {!! Former::checkbox('free_installation')->text('Besplatna ugradnja')!!}
            <hr>
            <legend>Mobilno montažni servis</legend>

            {!! Former::checkbox('mobile_service_car')->text('za putnička vozila')->class('checkbox') !!}
            {!! Former::checkbox('mobile_service_suv')->text('za terenska vozila')->class('checkbox') !!}
            {!! Former::checkbox('mobile_service_van')->text('za laka dostavna vozila')->class('checkbox') !!}
            {!! Former::checkbox('mobile_service_bike')->text('za motocikle')->class('checkbox') !!}
            {!! Former::checkbox('mobile_service_truck')->text('za kamione')->class('checkbox') !!}
            {!! Former::text('mobile_service_radius')->help('do udaljenosti (km)') !!}
            {!! Former::setOption('automatic_label', true) !!}

            <div class="form-actions center">
                {!! Button::primary(_('Save'))->withAttributes(['class'=>'btnResourcesSubmit'])->submit() !!}
            </div>
        </fieldset>

        {!!Former::close()!!}
    </div>
    @push('scripts')
    <script id="admin-reources-edit">
        $('.btnResourcesSubmit').on('click', function(e){
            e.preventDefault();

        })
    </script>
    @endpush
{{--
    <div class="col-sm-6">
        {!! Former::open()->url(url('/partner/'.$partner->partner_id.'/working-hours/save'))->id('form-edit-partner-working-hours') !!}
        <fieldset>
        <legend>Radno vreme</legend>

        <div class="alert alert-info">
            Vreme unosite u formatu <em><strong>08:00</strong></em> <br>
            <strong>Opis</strong> je predviđen za tekst tipa "Nedeljom ne radimo"
        </div>

        @foreach($partner->openingHours->sortBy('order_index') as $openingHour)
            <div class="form-group row">
                <div class="col-md-2"><label for="dow{!! $openingHour->dayOfWeek->id !!}_s" class="control-label">{!! $openingHour->dayOfWeek->description!!}</label></div>
                <div class="col-md-2"><input class="form-control" data-dow="{!! $openingHour->dayOfWeek->id !!}" type="text" name="dow{!! $openingHour->dayOfWeek->id !!}_s" id="dow{!! $openingHour->dayOfWeek->id !!}_s" value="{!! $openingHour->start_time !!}" placeholder="Vreme"></div>
                <div class="col-md-2"><input class="form-control" data-dow="{!! $openingHour->dayOfWeek->id !!}" type="text" name="dow{!! $openingHour->dayOfWeek->id !!}_e" id="dow{!! $openingHour->dayOfWeek->id !!}_e" value="{!! $openingHour->end_time !!}" placeholder="Vreme"></div>
                <div class="col-md-6"><input class="col-md-3 form-control" data-dow="{!! $openingHour->dayOfWeek->id !!}" type="text" name="dow{!! $openingHour->dayOfWeek->id !!}_o" id="dow{!! $openingHour->dayOfWeek->id !!}_o" value="{!! $openingHour->description !!}" placeholder="Opis"></div>
            </div>
        @endforeach

        <div class="text-center">
            <input type="submit" value="Sačuvaj izmene" class="btn btn-primary btn-large">
        </div>
        </fieldset>
        {!! Former::close() !!}
    </div>
    --}}
</div>