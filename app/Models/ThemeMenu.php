<?php

/*
 * NuraWeb - Free and Open Source Website Builder
 *
 * Copyright (C) 2024  Chimilevschi Iosif Gabriel, https://nurasoftware.com.
 *
 * LICENSE:
 * NuraWeb is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2024, Chimilevschi Iosif Gabriel, https://nurasoftware.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @author      Chimilevschi Iosif Gabriel <office@nurasoftware.com>
 * 
 * 
 * IMPORTANT: DO NOT edit this file manually. All changes will be lost after software update.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeMenu extends Model
{
    protected $fillable = [
        'parent_id',
        'type',
        'value',
        'position',
        'new_tab',
        'btn_id',
        'css_class',
        'icon'
    ];

    protected $table = 'theme_menu';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public static function generate_menu_links()
    {
        $links = ThemeMenu::whereNull('parent_id')->orderBy('position')->get();

        $permalinks = Config::config()->permalinks ?? null;
        $permalinks = unserialize($permalinks);

        $items = array();

        foreach ($links as $link) {

            $dropdown = array();

            if ($link->type == 'home')
                $url = route('home');

            if ($link->type == 'contact') {
                $permalink_contact = $permalinks['contact'] ?? 'contact';
                $url = route('home') . '/' . $permalink_contact;
            }
            if ($link->type == 'page') {
                $page = Page::where('id', $link->value)->first();
                if ($page)
                    $url = route('page', ['slug' => $page->slug ?? null]);
                else $url = '#';
            }

            if ($link->type == 'custom')
                $url = $link->value;

            if ($link->type == 'dropdown') {
                $url = '#';

                $dropdown_links = ThemeMenu::where('parent_id', $link->id)->orderBy('position')->get();
                foreach ($dropdown_links as $dropdown_link) {

                    if ($dropdown_link->type == 'home')
                        $dropdown_url = route('home');

                    if ($dropdown_link->type == 'contact') {
                        $permalink_contact = $permalinks['contact'] ?? 'contact';
                        $dropdown_url = route('home') . '/' . $permalink_contact;
                    }

                    if ($dropdown_link->type == 'page') {
                        $page = Page::where('id', $dropdown_link->value)->first();
                        if ($page) {
                            $dropdown_url = route('page', ['slug' => $page->slug ?? null]);
                        } else $dropdown_url = '#';
                    }

                    if ($dropdown_link->type == 'custom')
                        $dropdown_url = $dropdown_link->value;

                    $dropdown[] = array('label' => $dropdown_link->label ?? '#', 'url' => $dropdown_url ?? null, 'new_tab' => $dropdown_link->new_tab, 'icon' => $dropdown_link->icon);
                }
            }

            $items[] = array('label' => $link->label ?? '#', 'url' => $url ?? '#', 'dropdown' => $dropdown, 'type' => $link->type, 'css_class' => $link->css_class, 'new_tab' => $link->new_tab, 'icon' => $link->icon);
        }

        Config::update_config('menu_links', serialize($items));

        return null;
    }
}
