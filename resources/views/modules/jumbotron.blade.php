@if($banner_img !== '')

    <section class="jumbotron text-center">
        <div class="parallax"
             data-speed="15"
             style="
                     background: url('../storage/images/background/{{$banner_img}}');
                     background-size: cover;
                     ">

        </div>
        @if($banner_text !== '')
            <div class="container">
                <div class="row">
                    <div class="col-6 offset-1">
                        {!! $banner_text !!}
                    </div>
                </div>
            </div>
        @endif
    </section>
@endif