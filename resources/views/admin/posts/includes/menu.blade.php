<nav class="nav nav-tabs" id="myTab" role="tablist">
    @can('view', App\Models\Post::class)
    <a class="nav-item nav-link @if (($menu_section ?? null) == 'posts') active @endif" href="{{ route('admin.posts.index') }}"><i class="bi bi-card-text"></i> {{ __('Posts') }}</a>
    @endcan

    @can('view', App\Models\PostCateg::class)
    <a class="nav-item nav-link @if (($menu_section ?? null) == 'categ') active @endif" href="{{ route('admin.posts.categ') }}"><i class="bi bi-diagram-3"></i> {{ __('Categories') }}</a>
    @endcan 

    @can('view', App\Models\PostTag::class)
    <a class="nav-item nav-link @if (($menu_section ?? null) == 'tags') active @endif" href="{{ route('admin.posts.tags') }}"><i class="bi bi-tag"></i> {{ __('Tags') }}</a>
    @endcan 

    @if (Auth::user()->role == 'admin')
        <a class="nav-item nav-link @if (($menu_section ?? null) == 'config') active @endif" href="{{ route('admin.posts.config') }}"><i class="bi bi-gear"></i> {{ __('Settings') }}</a>
    @endif
</nav>
