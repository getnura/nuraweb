<div class="@if ($page->container_fluid ?? null) container-fluid @else container-xxl @endif">
    @switch($block['type'])
        @case('accordion')
            @include('web.blocks.accordion')
        @break

        @case('alert')
            @include('web.blocks.alert')
        @break

        @case('blockquote')
            @include('web.blocks.blockquote')
        @break

        @case('card')
            @include('web.blocks.card')
        @break

        @case('custom')
            @include('web.blocks.custom')
        @break

        @case('editor')
            @include('web.blocks.editor')
        @break

        @case('gallery')
            @include('web.blocks.gallery')
        @break

        @case('hero')
            @include('web.blocks.hero')
        @break

        @case('image')
            @include('web.blocks.image')
        @break

        @case('links')
            @include('web.blocks.links')
        @break

        @case('map')
            @include('web.blocks.map')
        @break

        @case('slider')
            @include('web.blocks.slider')
        @break

        @case('testimonial')
            @include('web.blocks.testimonial')
        @break

        @case('text')
            @include('web.blocks.text')
        @break

        @case('video')
            @include('web.blocks.video')
        @break
    @endswitch
</div>
