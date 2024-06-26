@php
$block_data = footer_block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_header = unserialize($block_data->header ?? null);
    @endphp

    <div class="py-4">

        @if ($block_header['add_header'] ?? null)
            @if ($block_header['title'] ?? null)
                <div class="title">
                    {!! $block_header['title'] ?? null !!}
                </div>
            @endif

            @if ($block_header['content'] ?? null)
                <div class="subtitle">
                    {!! $block_header['content'] ?? null !!}
                </div>
            @endif
        @endif

        {!! $block_data->content !!}
    </div>
@endif
