@foreach ($user->projects() as $project)
    {!! $project->project_id !!}
    {!! $project->project_name !!}
    </br>
@endforeach
