@extends("layout")

@section("content")
    <div class="row">
        <div class="col-md">
            <p class="lead"><strong>{{ __("ui.title") }}</strong> {{ __("about.p1") }}</p>
            <hr>
            <p><del>{{ __("about.p2") }}</del></p>
            <p>{{ __("about.p3") }}</p>
            <p>{{ __("about.p4_1") }} <a href="https://yayponies.no/" target="_blank">Yayponies</a>{{ __("about.p4_2") }}<a href="mailto:jingbohao@yeah.net" target="_blank">{{ strtolower(__("ui.contact_me")) }}</a>{{ __("about.p4_3") }}
            </p>
            <p>{{ __("ui.source") . __("ui.colon") }}<a href="https://github.com/JingBh/ts-res" target="_blank">GitHub</a></p>
        </div>
        <div class="col-md-auto text-center">
            <img class="img-fluid d-none d-md-block" src="/images/library.png" alt="Library" style="max-height:15rem;">
        </div>
    </div>
@endsection
