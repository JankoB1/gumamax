@extends('admin.master')

@section('content')
<div class="col col-xs-12 col-sm-6 col-lg-6 widget-container-col">
    <div class="widget-box">
        <div class="widget-header">
            <h5 class="widget-title">Service info</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
               <pre> {!! $esServiceInfo !!} </pre>
            </div>
        </div>
    </div>
</div>
<div class="col col-xs-12 col-sm-6 col-lg-6">
    <div class="widget-box widget-container-col">
        <div class="widget-header">
            <h5 class="widget-title">Indices</h5>
        </div>
        <div class="widget-body">
            <table class="table table-bordered table-striped table-responsive table-hover">
                <thead>
                <tr>
                    <th>Index name</th>
                    <th>Description</th>
                    <th>Documents count</th>
                    <th>Documents deleted</th>
                    <th>Index action</th>
                    <th>Type name</th>
                    <th>Type action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($appIndices as $key=>$value)
                    <tr>
                        <td>{!! $key !!}</td>
                        <td>{!! $value['description']!!}</td>
                        <td>@if ($value['exists']) {!! $value['total']['docs']['count'] !!} @endif</td>
                        <td>@if ($value['exists']) {!! $value['total']['docs']['deleted'] !!} @endif</td>
                        <td>
                            @if ($value['exists'])
                            {{ html()->form('DELETE', route('admin.elastic-index.delete'))->open() }}
                                <input type="hidden" name="index_name" id="index_name" value="{!! $key !!}"/>
                                <button type="submit" class="btn btn-small btn-danger">Briši</button>
                            {{ html()->form()->close() }}
                            @else
                            {{ html()->form('POST', route('admin.elastic-index.create'))->open() }}
                                <input type="hidden" name="index_name" id="index_name" value="{!! $key !!}"/>
                                <button type="submit" class="btn btn-small btn-success">Kreiraj</button>
                            {{ html()->form()->close() }}
                            @endif
                        </td>
                        <td>
                            {!! $value['type'] !!}
                        </td>
                        <td>
                            @if (($value['exists'])&&(!$value['type_exists']))
                            {{ html()->form('POST', route('admin.elastic-type.create'))->open() }}
                                <input type="hidden" name="index_name" id="index_name" value="{!! $key !!}"/>
                                <input type="hidden" name="type_name" id="type_name" value="{!! $value['type'] !!}"/>
                                <button type="submit" class="btn btn-small btn-info">Kreiraj</button>
                            {{ html()->form()->close() }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
