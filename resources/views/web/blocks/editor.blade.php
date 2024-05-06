@php
$block_data = block($block['id'], $is_layout ?? 0);
@endphp

@if ($block_data->content ?? null)
    <div class="block">
        {!! $block_data->content->content !!}
    </div>
@endif
