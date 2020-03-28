@extends("layout")

@section("content")
    <div id="tasksList">
        @include("tasks.g4_episodes", ["data" => $data["g4_episodes"]])
    </div>
@endsection
