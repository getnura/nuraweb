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


use App\Models\DriveFile;
use App\Models\User;
use App\Models\Config;
use App\Models\ThemeFooterBlock;
use App\Models\Block;
use App\Models\Page;
use App\Models\Language;

if (!function_exists('thumb')) {
	function thumb($file_code)
	{
		$file = DriveFile::where('code', $file_code)->value('file');

		$pos = strrpos($file, '/');

		if ($pos !== false) {
			$file = substr_replace($file, '/thumb_', $pos, 1);
		}

		if (!$file) return asset('assets/img/no-image.png');

		return asset("uploads/" . $file);
	}
}

if (!function_exists('thumb_square')) {
	function thumb_square($file_code)
	{
		$file = DriveFile::where('code', $file_code)->value('file');

		$pos = strrpos($file, '/');

		if ($pos !== false) {
			$file = substr_replace($file, '/thumb_square_', $pos, 1);
		}

		if (!$file) return asset('assets/img/no-image.png');

		return asset('uploads/' . $file);
	}
}

if (!function_exists('thumb_rectangle')) {
	function thumb_rectangle($file_code)
	{
		$file = DriveFile::where('code', $file_code)->value('file');

		$pos = strrpos($file, '/');

		if ($pos !== false) {
			$file = substr_replace($file, '/thumb_rectangle_', $pos, 1);
		}

		if (!$file) return asset('assets/img/no-image.png');

		return asset('uploads/' . $file);
	}
}


if (!function_exists('image')) {
	function image($file_code)
	{
		$file = DriveFile::where('code', $file_code)->value('file');

		if (!$file) return asset('assets/img/no-image.png');

		return asset("uploads/" . $file);
	}
}

if (!function_exists('avatar')) {
	function avatar($user_id)
	{
		$avatar = User::where('id', $user_id)->value('avatar');

		if (!$avatar) return asset('assets/img/no-avatar.png');
		else return asset('uploads/avatars/' . $avatar);
	}
}


if (!function_exists('avatar_icon')) {
	function avatar_icon($user_id)
	{
		$avatar = User::where('id', $user_id)->value('avatar');

		if (!$avatar) return asset('assets/img/no-avatar-icon.png');

		return asset('uploads/avatars/thumb_' . $avatar);
	}
}



// format date
if (!function_exists('date_locale')) {
	function date_locale($date, $format = null)
	{

		$date_format = Config::config()->date_format ?? '%B %e, %Y';

		if (!$format || $format == 'date') {
			return strftime($date_format, strtotime($date));
		}

		if ($format == 'datetime') {
			return strftime($date_format . ', %H:%M', strtotime($date));
		}

		if ($format == 'datetimefull') {
			return date_format(new DateTime($date), $date_format . ', H:i:s');
		}

		if ($format == 'daymonth') {
			return date_format(new DateTime($date), 'j M');
		}

		if ($format == 'time') {
			return date_format(new DateTime($date), 'H:i');
		}

		if ($format == 'timefull') {
			return date_format(new DateTime($date), 'H:i:s');
		}

		return;
	}
}


// menu links
if (!function_exists('menu_links')) {
	function menu_links()
	{
		if (Config::config()->menu_links ?? null) $menu_links = unserialize((Config::config()->menu_links));
		else $menu_links = array();
		$menu_links = json_decode(json_encode($menu_links));

		return $menu_links ?? array();
	}
}


// Page details. (from pages_content table)
if (!function_exists('page')) {
	function page($id)
	{
		$page = Page::where('active', 1)->where('id', $id)->first();
		if (!$page) return null;

		if ($page->parent_id) { // page is child of a parent page		
			$parent = Page::where('active', 1)->where('id', $page->parent_id)->first();
			if (!$parent) return null;
			$page->url = route('child_page', ['slug' => $page->slug, 'parent_slug' => $page->parent_slug]);
		} else
			$page->url = route('page', ['slug' => $page->slug]);

		$page->child_pages = Page::where('parent_id', $page->id)
			->where('active', 1)
			->orderBy('title')
			->paginate(100);

		return $page;
	}
}



// Get blocks for a specific content (post, page, sidebar, global (top / bottom sections), ....)
if (!function_exists('content_blocks')) {
	function content_blocks($module, $content_id, $show_hidden = null)
	{
		$blocks = Block::where(['module' => $module, 'content_id' => $content_id]);

		if (!$show_hidden) $blocks = $blocks->where('blocks.hide', 0);

		$blocks = $blocks->orderBy('position')->get();

		if (!$blocks) return array();

		return $blocks ?? array();
	}
}


// Get blocks for a specific footer column
if (!function_exists('footer_blocks')) {
	function footer_blocks($footer, $col, $show_hidden = null)
	{
		// get footer layout (number of columns)
		if ($footer == 'primary') $layout = Config::config()->footer_columns ?? 1;
		if ($footer == 'secondary') $layout = Config::config()->footer2_columns ?? 1;

		$blocks = ThemeFooterBlock::where('footer', $footer)->where('layout', $layout)->where('col', $col);

		if (!$show_hidden) $blocks = $blocks->where('hide', 0);

		$blocks = $blocks->orderBy('position')->get();

		return $blocks;
	}
}


// show content block 
if (!function_exists('block')) {
	function block($id)
	{
		$data = array('content' => null, 'content_extra' => null);

		$block = Block::where('id', $id)->where('hide', 0)->first();
		if (!$block) return (object)$data;

		$data = array('content' => $block->content ?? null, 'header' => $block->header ?? null);

		return (object)$data;
	}
}


// show footer block 
if (!function_exists('footer_block')) {
	function footer_block($id)
	{

		$data = array('block_extra' => null, 'content' => null, 'content_extra' => null);

		$block = ThemeFooterBlock::find($id);
		if (!$block) return (object)$data;

		$data = array('content' => $block->content ?? null, 'header' => $block->header ?? null, 'block_extra' => (object)unserialize($block->extra));

		return (object)$data;
	}
}


// generate flag image from language code
if (!function_exists('flag')) {
	function flag($identificator)
	{
		if (is_int($identificator))
			$lang = Language::where('id', $identificator)->first();
		else
			$lang = Language::where('code', $identificator)->first();

		if (!$lang) return null;

		if (file_exists(public_path('/assets/img/flags/' . $lang->code . '.png')))
			return '<img alt="' . $lang->name . '" title="' . $lang->name . '" class="img-flag" src="' . asset("assets/img/flags/$lang->code.png") . '">';
		else
			return '(' . $lang->code . ')';
	}
}


// create breadcrumb for categories
if (!function_exists('breadcrumb_items')) {
	function breadcrumb_items($categ_id)
	{
		$categ = PostCateg::where('id', $categ_id)->first();
		if (!$categ) return array();

		$items[] = array('id' => $categ->id, 'title' => $categ->title, 'slug' => $categ->slug, 'url' => route('posts.categ', ['categ_slug' => $categ->slug]), 'active' => $categ->active, 'icon' => $categ->icon, 'count_tree_items' => $categ->count_tree_items ?? null, 'count_tree_topics' => $categ->count_tree_topics ?? null, 'count_tree_posts' => $categ->count_tree_posts ?? null);

		$parent_id = $categ->parent_id;
		if ($parent_id) {
			$items = array_merge($items, breadcrumb_items($parent_id));
		}

		$items = json_decode(json_encode($items)); // array to object;
		return ($items);
	}
}


if (!function_exists('breadcrumb')) {
	function breadcrumb($categ_id)
	{
		if (!$categ_id)
			return array();
		if (!is_array(breadcrumb_items($categ_id)))
			return array();

		return array_reverse(breadcrumb_items($categ_id));
	}
}



// get ad details
if (!function_exists('ad')) {
	function ad($id)
	{
		$ad = Ad::where('id', $id)->where('active', 1)->first();
		if (!$ad) return null;

		if ($ad->type == 'code') return $ad->content;

		if ($ad->new_tab == 1) $target = "_blank";
		else $target = "_self";

		$data = '<img alt="' . ($ad->alt ?? 'Ad') . '" src="' . image($ad->content) . '">';

		if ($ad->url) $data = '<a target="' . $target . '" href="https://' . $ad->url . '">' . $data . '</a>';

		return $data ?? null;
	}
}


// get poll details
if (!function_exists('poll')) {
	function poll($id = null)
	{
		if (!$id) {
			// Get last active poll
			$poll = Poll::withCount('votes')->with('options')->where('active', 1)->where('hidden', 0)->latest('id')->first();
		} else
			$poll = Poll::withCount('votes')->with('options')->where('active', 1)->where('id', $id)->first();

		return $poll;
	}
}

// get poll vote details
if (!function_exists('poll_vote')) {
	function poll_vote($id)
	{
		$ip = \Request::ip();

		$poll = Poll::with('options')->where('active', 1)->where('id', $id)->first();
		if (!$poll) return null;

		if ($poll->login_required == 1)
			$vote = PollVote::where('poll_id', $poll->id)->where('user_id', Auth::user()->id ?? null)->first();
		else
			$vote = PollVote::where('poll_id', $poll->id)->where('ip',  $ip ?? null)->first();

		return $vote;
	}
}