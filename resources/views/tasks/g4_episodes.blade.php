<div class="card">
    <div class="card-header">
        <h4 class="mb-1">{{ __("ui.g4_episodes") }}</h4>
        <p class="text-muted mb-0">{{ __("ui.season_s", ["n" => count($data)]) }}</p>
    </div>
    <div class="card-body px-2 py-3">
        <div class="accordion" id="accordion_g4_episodes">
            @foreach ($data as $season => $episodes)
                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#collapse_g4_episodes_s{{ $season }}">
                        <h5 class="mb-1">{{ __("ui.season_n", ["n" => $season]) }}</h5>
                        <p class="text-muted mb-0">{{ __("ui.episode_s", ["n" => count($episodes)]) }}</p>
                    </div>
                    <div class="collapse" id="collapse_g4_episodes_s{{ $season }}" data-parent="#accordion_g4_episodes">
                        <div class="card-body p-0">
                            <div class="list-group">
                                @foreach ($episodes as $episode)
                                    <div class="list-group-item" style="border-radius:0;">
                                        <p class="mb-1">
                                            <span class="text-muted"><small>S</small>{{ sprintf("%02d", $season) }}<small>E</small>{{ sprintf("%02d", $episode["episode_n"]) }}</span>
                                            <strong class="text-success ml-1">{{ $episode["episode_title"] }}</strong>
                                        </p>
                                        @if (filled($episode["disk_download"]))
                                            <div>
                                                {{ __("ui.disk_download") . __("ui.colon") }}
                                                <div class="btn-group" role="group">
                                                    @foreach($episode["disk_download"] as $url)
                                                        <a class="btn btn-sm btn-link" href="{{ $url }}" target="_blank">{{ $loop->iteration }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if (filled($episode["direct_download"]))
                                            <div>
                                                {{ __("ui.direct_download") . __("ui.colon") }}
                                                <div class="btn-group" role="group">
                                                    @foreach($episode["direct_download"] as $url)
                                                        <a class="btn btn-sm btn-link" href="{{ $url }}" target="_blank">{{ $loop->iteration }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if (filled($episode["torrent_download"]))
                                            <div>
                                                {{ __("ui.torrent_download") . __("ui.colon") }}
                                                <div class="btn-group" role="group">
                                                    @foreach($episode["torrent_download"] as $url)
                                                        <a class="btn btn-sm btn-link" href="{{ $url }}" target="_blank">{{ $loop->iteration }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
