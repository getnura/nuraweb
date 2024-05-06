<div id="footer">
    <!-- ======= Primary Footer ======= -->
    <div class="style_footer">
        <div class="@if($templateConfig->website_container_fluid ?? null)container-fluid @else container-xxl @endif">
            @php
                $footer_columns = $config->tpl_footer_columns ?? 1;
            @endphp

            @switch($footer_columns)
                @case('1')
                    @include("web.includes.footer-1-col", ['footer' => 'primary'])
                @break

                @case('2')
                    @include("web.includes.footer-2-cols", ['footer' => 'primary'])
                @break

                @case('3')
                    @include("web.includes.footer-3-cols", ['footer' => 'primary'])
                @break

                @case('4')
                    @include("web.includes.footer-4-cols", ['footer' => 'primary'])
                @break
            @endswitch
        </div>
    </div>


    @if ($config->tpl_footer2_show ?? null)
        <!-- ======= Secondary Footer ======= -->
        <div class="style_footer2">
            <div class="@if($templateConfig->website_container_fluid ?? null)container-fluid @else container-xxl @endif">
                @php
                    $footer_columns = $config->tpl_footer2_columns ?? 1;
                @endphp

                @switch($footer_columns)
                    @case('1')
                        @include("web.includes.footer-1-col", ['footer' => 'secondary'])
                    @break

                    @case('2')
                        @include("web.includes.footer-2-cols", ['footer' => 'secondary'])
                    @break

                    @case('3')
                        @include("web.includes.footer-3-cols", ['footer' => 'secondary'])
                    @break

                    @case('4')
                        @include("web.includes.footer-4-cols", ['footer' => 'secondary'])
                    @break
                @endswitch
            </div>
        </div>
    @endif
</div>

@if ($config->popup_enabled ?? null)
    @php
        $popup_title_key = 'popup_title_' . active_lang()->id;
        $popup_content_key = 'popup_content_' . active_lang()->id;
        $popup_destination_page_label_key = 'popup_destination_page_label_' . active_lang()->id;
        $popup_destination_page_url_key = 'popup_destination_page_url_' . active_lang()->id;
        $popup_button_label_key = 'popup_button_label_' . active_lang()->id;

        $popup_content = $config->$popup_content_key;
        $popup_content = str_replace(array("\r", "\n", "<br>"), '', $popup_content);

    @endphp
    <script async src="{{ config('app.cdn') }}/js/cookies.js"></script>
    <script>
        var options = {
            title: '{{ $config->$popup_title_key ?? __("Accept terms") }}',
            message: "{{ $popup_content ?? __("Accept terms") }}",
            delay: 600,
            expires: {{ $config->popup_days ?? 30 }}, // 30 days default
            link: '{{ $config->$popup_destination_page_url_key ?? "#" }}',           
            uncheckBoxes: true,
            acceptBtnLabel: '{{ $config->$popup_button_label_key ?? __("I agree") }}',
            moreInfoLabel: '{{ $config->$popup_destination_page_label_key ?? "#" }}',
        }

        $(document).ready(function() {
            $('body').ihavecookies(options);            

            $('#ihavecookiesBtn').on('click', function() {
                $('body').ihavecookies(options, 'reinit');
            });
        });
    </script>
@endif

@include($templatePath.".includes.template-footer")
