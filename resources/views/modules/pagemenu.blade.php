@if(count($allPages) > 0)
    <div class="col-6 d-flex flex-column flex-md-row justify-content-around">
        @foreach($allPages as $alias => $page)
            <a class="py-2 d-none d-md-inline-block" href="{{$alias}}">{{$page['name']}}</a>
        @endforeach
    </div>
@endif