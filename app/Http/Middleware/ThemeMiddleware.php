<?php

/**
 * Clevada - Content Management System and Website Builder
 *
 * Copyright (C) 2024  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @author      Chimilevschi Iosif Gabriel <contact@clevada.com>
 * 
 * 
 * IMPORTANT: DO NOT edit this file manually. All changes will be lost after software update.
 */


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use View;
use App;
use Config as LaravelConfig;
use App\Models\Config;
use App\Models\ConfigLang;
use App\Models\ThemeConfig;
use App\Models\Language;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Language
        $lang = $request->segment(1);

        if (strlen($lang) === 2 && Language::where(['code' => $lang, 'status' => 'active'])->exists()) {
            app()->setLocale($lang);
            setlocale(LC_ALL, $lang);
        }

        // general config
        $config = Config::config();

        // theme config
        $theme_config = ThemeConfig::config();

        // config depending on language
        $config_lang = ConfigLang::config();

        // Views variables
        View::share('locale', config('app.locale'));
        View::share('config', $config);
        View::share('config_lang', $config_lang);
        View::share('theme_config', $theme_config);
        View::share('request_path', $request->path() ?? null);

        return $next($request);
    }
}
