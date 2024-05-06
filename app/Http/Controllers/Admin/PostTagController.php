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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PostTag;
use App\Models\Tools;
use App\Models\PostType;
use App\Models\TaxonomyTerm;

class PostTagController extends Controller
{

    public function __construct(Request $request)
    {
        $this->type = $request->type ?? 'post';

        $this->taxonomy_terms = TaxonomyTerm::with('taxonomies')->where(['post_type' => $this->type, 'active' => 1, 'admin_filter' => 1])->orderBy('position')->get();

        $this->post_type = PostType::where(['type' => $this->type, 'active' => 1])->first();

        $this->middleware(function ($request, $next) {
            if ((PostType::where('type', $this->type))->doesntExist()) return redirect(route('admin'));
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {
        $search_terms = $request->search_terms;

        $tags = PostTag::where('post_type', $this->type);

        if ($search_terms) $tags = $tags->where('post_tags.tag', 'like', "%$search_terms%");

        $tags = $tags->orderBy('tag')->paginate(25);

        return view('admin.index', [
            'view_file' => 'posts.tags',
            'active_menu' => $this->type ?? null,
            'active_submenu' => 'posts',
            'menu_section' => 'tags',
            'tags' => $tags,
            'search_terms' => $search_terms,
            'type' => $this->type ?? null, // String (post type)
            'taxonomy_terms' => $this->taxonomy_terms ?? null, // Object
            'post_type' => $this->post_type ?? null, // Object  
        ]);
    }



    /**
     * Create new tag
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'tag' => 'required',
        ]);

        if ($validator->fails()) return redirect(route('admin.tags.index', ['type' => $this->type]))->withErrors($validator)->withInput();

        $slug = Str::slug($request->tag, '-');

        if (PostTag::where('slug', $slug)->where('post_type', $this->type)->exists()) return redirect(route('admin.tags.index', ['post_type' => $this->type]))->with('error', 'duplicate');

        PostTag::create([
            'post_type' => $request->type ?? 'post',
            'tag' => $request->tag,
            'slug' => $slug,
        ]);

        Tools::generateSitemap();

        return redirect(route('admin.tags.index', ['post_type' => $this->type]))->with('success', 'created');
    }



    /**
     * Update tag
     */
    public function update(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'tag' => 'required',
        ]);

        if ($validator->fails()) return redirect(route('admin.tags.index', ['post_type' => $this->type]))->withErrors($validator)->withInput();

        $slug = Str::slug($request->tag, '-');

        if (PostTag::where('slug', $slug)->where('post_type', $this->type)->where('id', '!=', $request->id)->exists()) return redirect(route('admin.tags.index', ['post_type' => $this->type]))->with('error', 'duplicate');

        PostTag::where('id', $request->id)->update([
            'tag' => $request->tag,
            'slug' => $slug,
        ]);

        Tools::generateSitemap();

        return redirect(route('admin.tags.index', ['post_type' => $this->type]))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $tag = PostTag::find($request->id);
        if (!$tag) return redirect(route('admin.tags.index', ['post_type' => $this->type]));

        PostTag::where('id', $request->id)->delete();

        Tools::generateSitemap();

        return redirect(route('admin.tags.index', ['post_type' => $this->type]))->with('success', 'deleted');
    }
}
