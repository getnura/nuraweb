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
use App\Models\SysLang;

class PageContent extends Model
{
    protected $fillable = [
        'page_id',
        'lang_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
    ];

    protected $table = 'page_content';

    protected $appends = ['url', 'lang_status', 'meta_title', 'meta_description'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

  
    public function default_language()
    {
        return $this->belongsTo(Language::get_default_language(), 'lang_id');
    }


    public function getUrlAttribute()
    {
        // check if page is child of another page
        $page_id = $this->page_id;
        $parent_id = Page::where('id', $page_id)->value('parent_id');        
        
        if($parent_id) {            
            $parent_slug = PageContent::where('page_id', $parent_id)->where('lang_id', $this->lang_id)->value('slug');  
            $url = route('child_page', ['lang' => (Language::get_default_language()->id != $this->lang_id) ? Language::get_language_from_id($this->lang_id)->code : null, 'slug' => $this->slug, 'parent_slug' => $parent_slug]);
        }
        else
        $url = route('page', ['lang' => (Language::get_default_language()->id != $this->lang_id) ? Language::get_language_from_id($this->lang_id)->code : null, 'slug' => $this->slug]);

        return $url;
    }

    public function get_language_status_attribute()
    {        
        return Language::get_language_from_id($this->lang_id)->status;
    }

  

}
