@extends('frontend.layouts.main')
@section('title', $title)
@section('content')
    <!-- # site-content
        ================================================== -->
    <section id="content" class="s-content">
        <!-- hero -->
        <div class="hero">

            <div class="hero__slider swiper-container">

                <div class="swiper-wrapper">
                    @foreach($news as $new)
                        <article class="hero__slide swiper-slide">
                            <div class="hero__entry-image" style="background-image: url('{{asset('image/'. $new->image)}}');"></div>
                            <div class="hero__entry-text">
                                <div class="hero__entry-text-inner">
                                    <h2 class="hero__entry-title">
                                        <a href="single-standard.html">
                                            {{$new->title}}
                                        </a>
                                    </h2>
                                    <p class="hero__entry-desc">
                                        {!! $new->getExcerptAttribute() !!}
                                    </p>
                                    <a class="hero__more-link" href="{{route('new',['id' => $new->id])}}">Read More</a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                </div> <!-- swiper-wrapper -->

                <div class="swiper-pagination"></div>

            </div> <!-- end hero slider -->

            <a href="#bricks" class="hero__scroll-down smoothscroll">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.25 6.75L4.75 12L10.25 17.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 12H5"></path>
                </svg>
                <span>Scroll</span>
            </a>

        </div> <!-- end hero -->


        <!--  masonry -->
        <div id="bricks" class="bricks">

            <div class="masonry">

                <div class="bricks-wrapper" data-animate-block>

                    <div class="grid-sizer"></div>
                    @foreach($news as $new)

                    <article class="brick entry" data-animate-el>

                        <div class="entry__thumb">
                            <a href="{{route('new',['id' => $new->id])}}" class="thumb-link">
                                <img src="{{asset('image/'. $new->image)}}" alt="">
                            </a>
                        </div> <!-- end entry__thumb -->
                        <div class="entry__meta">
                            <div class="entry__meta-date">
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="7.25" stroke="currentColor" stroke-width="1.5"></circle>
                                    <path stroke="currentColor" stroke-width="1.5" d="M12 8V12L14 14"></path>
                                </svg>
                                {{$new->created_at}}
                            </div>
                        </div>
                        <div class="entry__text">
                            <div class="entry__header">
                                <h1 class="entry__title">{{$new->title}}</h1>
                            </div>
                            <div class="entry__excerpt">
                                <p>
                                    {!! $new->getExcerptAttribute() !!}
                                </p>
                            </div>
                            <a class="entry__more-link" href="{{route('new',['id' => $new->id])}}">Read More</a>
                        </div> <!-- end entry__text -->

                    </article> <!-- end article -->

                    @endforeach
                </div> <!-- end bricks-wrapper -->
            </div> <!-- end masonry-->


            <!-- pagination -->

                    {{$news->links('frontend.pagination')}}


        </div> <!-- end bricks -->

    </section> <!-- end s-content -->
@endsection
