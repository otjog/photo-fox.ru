<main role="main">

    @if(count($currentPage['modules']) > 0)

        @if($currentPage['banner_active'])
            @include('modules.jumbotron', $currentPage)
        @endif

        @foreach($currentPage['modules'] as $module)

            <div class="module {{$module}} py-5">
                @include('modules.'.$module, $currentPage)
            </div>
        @endforeach
    @endif

</main>