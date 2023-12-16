<div class="alert alert-danger @if(count($errors)>0) {!! 'showing' !!}@else {!! 'hidden' !!} @endif">
    <strong>Whoops!</strong> There were some problems with your input.<br>
    <ul id="errorsList">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        @endif
    </ul>
</div>

