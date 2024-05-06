<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.theme') }}">{{ __('Appearance') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Footer') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.theme.includes.menu-theme')
            </div>

        </div>

    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif


        <i class="bi bi-info-circle"> {!! __('You can change style (colors, sizes and fonts) for each theme but <b>the footer layout and content is identical for all themes</b>.') !!}</i>

        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold mt-3 mb-2">{{ __('Footer layout') }}</h5>

                <form action="{{ route('admin.theme.footer') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 col-xl-5 col-md-6">
                            <div class="form-group">
                                <label>{{ __('Choose primary footer layout') }}</label><br>
                                <select class="form-select" name="themes_footer_columns">
                                    <option @if (($config->themes_footer_columns ?? null) == '1') selected @endif value="1">{{ __('One column') }}</option>
                                    <option @if (($config->themes_footer_columns ?? null) == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                    <option @if (($config->themes_footer_columns ?? null) == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                    <option @if (($config->themes_footer_columns ?? null) == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                </select>
                                <div class="text-muted small">{{ __('Select number of columns for primary footer') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='themes_footer2_show'>
                                    <input class="form-check-input" type="checkbox" id="themes_footer2_show" name="themes_footer2_show" @if ($config->themes_footer2_show ?? null) checked @endif>
                                    <label class="form-check-label" for="themes_footer2_show">{{ __('Show secondary footer') }}</label>
                                </div>
                                <div class="text-muted small">{{ __('This footer is below main footer') }}</div>
                            </div>

                            <script>
                                $('#themes_footer2_show').change(function() {
                                    select = $(this).prop('checked');
                                    if (select)
                                        document.getElementById('hidden_div_footer2').style.display = 'block';
                                    else
                                        document.getElementById('hidden_div_footer2').style.display = 'none';
                                })
                            </script>

                            <div id="hidden_div_footer2" style="display: @if ($config->themes_footer2_show ?? null) block @else none @endif">
                                <div class="row">
                                    <div class="col-12 col-xl-5 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Choose footer layout') }}</label><br>
                                            <select class="form-select" name="tpl_footer2_columns">
                                                <option @if (($config->themes_footer2_columns ?? null) == '1') selected @endif value="1">{{ __('One column') }}</option>
                                                <option @if (($config->themes_footer2_columns ?? null) == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                                <option @if (($config->themes_footer2_columns ?? null) == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                                <option @if (($config->themes_footer2_columns ?? null) == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                            </select>
                                            <div class="text-muted small">{{ __('Select number of columns for primary footer') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="redirect_to" value="footer">
                    <button type="submit" class="btn btn-primary mt-1">{{ __('Update footer layout') }}</button>
                </form>


            </div>



            <div class="col-md-6">
                <div class="fw-bold fs-5 mt-2 mb-3">{{ __('Footer content') }}</div>

                <div class="d-grid gap-2 d-md-block mb-3">
                    <a class="btn btn-gear" href="{{ route('admin.theme.footer.content', ['footer' => 'primary']) }}"><i class="bi bi-pencil-square"></i> {{ __('Manage primary footer content') }}</a>
                    @if ($config->themes_footer2_show ?? null)
                        <a class="btn btn-gear ms-2" href="{{ route('admin.theme.footer.content', ['footer' => 'secondary']) }}"><i class="bi bi-pencil-square"></i>
                            {{ __('Manage secondary footer content') }}</a>
                    @endif
                </div>
            </div>
        </div>



    </div>
    <!-- end card-body -->

</div>
