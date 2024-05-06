<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="{{ $config->site_author ?? 'Nura Software - https://nurasoftware.com' }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

@include($templatePath . '.includes.template-head')

<!-- Favicon -->
@if ($config->favicon ?? null)
    <link rel="shortcut icon" href="{{ asset('uploads/' . $config->favicon) }}">
@else
    <link rel="shortcut icon" href="{{ config('app.cdn') }}/img/favicon.png">
@endif

<!-- Bootstrap Icons -->
<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

@if ($config->use_icons_boxicons ?? null)
    <!-- BooxIcons -->
    <link rel="preload" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' as="style" onload="this.onload=null;this.rel='stylesheet'">
@endif

@if ($config->popup_enabled ?? null)
    <link rel="preload" href="{{ config('app.cdn') }}/css/cookie.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
@endif

@if (($config->google_analytics_id ?? null) && ($config->google_analytics_enabled ?? null))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $config->google_analytics_id ?? null }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ $config->google_analytics_id ?? null }}');
    </script>
@endif

<!-- Include CSS -->
@php
    $css_global = file_get_contents(config('app.cdn') . '/css/global.css');
    $css_default = file_get_contents(config('app.cdn') . '/templates/' . $activeTemplate . '/css/default.css');
    $css_custom = file_get_contents(asset("custom/$activeTemplate.css"));
    echo '<style>' . $css_global . '</style>';
    echo '<style>' . $css_default . '</style>';
    echo '<style>' . $css_custom . '</style>';
@endphp
