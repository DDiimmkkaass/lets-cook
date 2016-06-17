<section class="content-header">
    @if (!empty($page_title))
        <h1>{!! $page_title !!}</h1>
    @endif

    @include('partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs ])
</section>