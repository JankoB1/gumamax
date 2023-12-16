@extends('admin.master')

@section('content')
<div class="col-md-12">
    <div class="row">
    <div class="col-md-4">
            {!! Former::vertical_open()
                ->id('form-carousel')
                ->method($formMethod)
                ->action(url($formUrl))
                ->secure()
            !!}

            {!! Former::populate($carousel) !!}

            <input type="hidden" name="carousel_id" id="carousel_id" value="{!! $carousel->carousel_id!!}">


                    <div class="form-group">
                        <label for="datetime_start">Važi od</label>
                        <div class='input-group date' id='datetime_start_picker' data-linked_input="datetime_start">
                            <input type='text' class="form-control" value="{!! date('d.m.Y H:i', strtotime($carousel->datetime_start)) !!}" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <input class="" id="datetime_start" name="datetime_start" type="hidden" value="{!! date('Y-m-d H:i:s', strtotime($carousel->datetime_start)) !!}">



                    <div class="form-group">
                        <label for="datetime_end">Važi do</label>
                        <div class='input-group date' id='datetime_end_picker' data-linked_input="datetime_end">
                            <input type='text' class="form-control" value="{!! date('d.m.Y H:i', strtotime($carousel->datetime_end)) !!}" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <input class="" id="datetime_end" name="datetime_end" type="hidden" value="{!! date('Y-m-d H:i:s', strtotime($carousel->datetime_end)) !!}">




                        {!!  Former::select('carousel_type')->options(['1' => 'Slika','2'=>'Slika + text']) !!}



                        {!!  Former::select('link_type')->options(['0' => 'Ništa','1'=>'Otvori link u novom prozoru', '2'=>'Modalno (popup)']) !!}




                        {!! Former::text('html_id') !!}


                        {!! Former::text('image')->label('Naziv slike') !!}


                        {!! Former::setOption('automatic_label', false) !!}
                        {!! Former::checkbox('active')->text('Aktivan (prvi se učitava)') !!}
                        {!! Former::setOption('automatic_label', true) !!}


                        {!! Former::text('text')->label('Tekst preko slike') !!}


                        {!! Former::text('link_to')->label('Linkuj na') !!}



            <div data-class="col-md-12 text-center">
                <button type="submit" class="btn btn-info btn-large">Sačuvaj izmene</button>
            </div>
            {!! Former::close() !!}
        </div>

        <div class="col-md-8">
            <div class="col-md-12 text-center">
                <a href="{!! url('admin/pictures/carousel') !!}" class="btn">Dodaj novu sliku</a>
            <div>
            <div class="col-md-12">
                @foreach($pictures as $pic)
                    <div class="admin-carousel-img-list">
                        <div class="text-right"><a href="{!! url('admin/pictures/carousel/delete/'.$pic) !!}"><span class="glyphicon glyphicon-remove"></span> Ukloni</a></div>
                        <div class=""><img src="{!!URL::to('carousel/'.$pic)!!}" alt="" style="max-width:235px;max-height:150px;"></div>
                        <div>{!!$pic!!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
    {!! Html::script('js/admin-vendor.js') !!}
    <script>

        $(function(){

             function setDate(sender, e) {
                var dt = e.date.format("YYYY-MM-DD HH:mm:ss");
                var lin = '#'+sender.data('linked_input');
                $(lin).val( dt );
            }


            $('#datetime_start_picker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});

            $('#datetime_end_picker').datetimepicker({
                useCurrent: false, //Important! See issue #1075
                format: 'DD.MM.YYYY HH:mm'
            });

            $("#datetime_start_picker").on("dp.change", function (e) {
                $('#datetime_end_picker').data("DateTimePicker").minDate(e.date);
                setDate($(this), e);
            });

            $("#datetime_end_picker").on("dp.change", function (e) {
                $('#datetime_start_picker').data("DateTimePicker").maxDate(e.date);
                setDate($(this), e);
            });

            $('input.carousel-image').on("change", function(){
                $('#image').val($(this).data('image'));
            });
        });
    </script>
@endsection
