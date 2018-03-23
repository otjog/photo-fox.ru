@if(count($allWorks['works']) > 0)
    <div class="container">
        @if(count($allWorks['tags']) > 0)
            <div class="tags-container">
                <span data-filter="" class="work-tags badge badge-primary">Все</span>
                @foreach($allWorks['tags'] as $tagName)
                    <span data-filter="{{$tagName}}" class="work-tags badge">{{$tagName}}</span>
                @endforeach
            </div>
        @endif
        <div class="row no-gutters">
            @foreach($allWorks['works'] as $alias => $options)
                <div class="col-md-4">
                    <div style="display: none;" id="{{$alias}}-wrap">
                        <img src="{{ URL::asset($options['frontImage']) }}"
                             class="photo-fox"
                             id="{{$alias}}"
                             data-images="{{ URL::asset($options['dataImage']) }}"
                             data-frames="{{$options['countImages']}}"
                             data-speed="0.3">
                    </div>
                    <a
                            class="fancy-fox"
                            data-filter="{{$options['description']['tag']}}"
                            data-src="#{{$alias}}-wrap" href="javascript:;">

                        <img
                                src="{{ URL::asset($options['frontImage']) }}"
                                width="100%">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif