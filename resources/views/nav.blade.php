<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs("index")) active @endif" href="/">{{ __("ui.index") }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs("about")) active @endif" href="/about">{{ __("ui.about") }}</a>
    </li>
    <li class="nav-item dropdown ml-auto">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">Language</a>
        <div class="dropdown-menu dropdown-menu-right">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="../{{ $localeCode }}">{{ $properties["native"] }}</a>
            @endforeach
        </div>
    </li>
</ul>
