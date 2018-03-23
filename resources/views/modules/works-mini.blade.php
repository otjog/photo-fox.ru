@if(count($allWorks['works']) > 0)
    @php
        $currentWork = array_shift($allWorks['works']);
    @endphp

    <div class="container">
        <div id="photo-fox-main-img-wrap" class="col-md-4 offset-3">
            <img width="100%"
                 class="img-fluid"
                 src="{{ URL::asset($currentWork['frontImage']) }}"
                 id="{{$currentWork['idWork']}}"
                 data-images="{{ URL::asset($currentWork['dataImage']) }}"
                 data-frames="{{$currentWork['countImages']}}"
            >
        </div>
        @if(count($allWorks['works']) > 0)
            <div id="photo-fox-images-list" class="row photo-fox-list">
                @foreach($allWorks['works'] as $currentWork)
                    <div class="col-md-2">
                        <img width="100%"
                             class="img-thumbnail"
                             src="{{ URL::asset($currentWork['frontImage']) }}"
                             id="{{$currentWork['idWork']}}"
                             data-images="{{ URL::asset($currentWork['dataImage']) }}"
                             data-frames="{{$currentWork['countImages']}}"
                        >
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif