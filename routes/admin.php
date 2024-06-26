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

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Admin
use App\Http\Controllers\Admin\AjaxController as AdminAjaxController;
use App\Http\Controllers\Admin\BlockController as AdminBlockController;
use App\Http\Controllers\Admin\CoreController as AdminCoreController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ThemeController as AdminThemeController;
use App\Http\Controllers\Admin\ThemeFooterController as AdminThemeFooterController;
use App\Http\Controllers\Admin\ThemeButtonController as AdminThemeButtonController;
use App\Http\Controllers\Admin\ThemeStyleController as AdminThemeStyleController;
use App\Http\Controllers\Admin\ThemeMenuController as AdminThemeMenuController;
use App\Http\Controllers\Admin\LangController as AdminLangController;
use App\Http\Controllers\Admin\TranslateController as AdminTranslateController;

Route::get('account/admin', [AdminCoreController::class, 'dashboard'])->name('admin');

Route::prefix('account/admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminCoreController::class, 'dashboard'])->name('dashboard');

    // Pages
    Route::resource('pages', AdminPageController::class)->parameters(['pages' => 'id']);
    Route::get('pages/{id}/content', [AdminPageController::class, 'content'])->name('pages.content')->where('id', '[0-9]+');
    Route::post('pages/{id}/content', [AdminPageController::class, 'content_update'])->name('pages.content.new')->where('id', '[0-9]+');
    Route::delete('pages/{id}/content/delete/{block_id}', [AdminPageController::class, 'content_destroy'])->name('pages.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');
    Route::post('pages/{id}/sortable', [AdminPageController::class, 'sortable'])->name('pages.sortable')->where('id', '[0-9]+');
    Route::post('pages/{id}/ajaxPublishSwitch', [AdminPageController::class, 'ajaxPublishSwitch'])->name('pages.ajaxPublishSwitch')->where('id', '[0-9]+');

    // Profile
    Route::get('profile', [AdminCoreController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminCoreController::class, 'profile_update']);
    Route::delete('profile/delete-avatar', [AdminCoreController::class, 'profile_delete_avatar'])->name('profile.delete_avatar');

    // Tools
    Route::get('tools', [AdminCoreController::class, 'tools'])->name('tools');
    Route::post('tools', [AdminCoreController::class, 'tools_action'])->middleware(['password.confirm']);
    Route::get('tools/update-install', [AdminCoreController::class, 'update_install'])->name('tools.update.install');

    // Activity log
    Route::get('activity', [AdminCoreController::class, 'activity'])->name('activity');

    // Contact page and contact form
    Route::get('contact/config', [AdminContactController::class, 'config'])->name('contact.config');
    Route::put('contact/config', [AdminContactController::class, 'update_config']);

    Route::get('contact/trash', [AdminContactController::class, 'trash'])->name('contact.trash');

    Route::get('contact/fields', [AdminContactController::class, 'fields'])->name('contact.fields');
    Route::post('contact/fields/add-field', [AdminContactController::class, 'add_field'])->name('contact.add_field');
    Route::get('contact/fields/update-field/{field_id}', [AdminContactController::class, 'show_field'])->name('contact.show_field')->where('field_id', '[0-9]+');
    Route::put('contact/fields/update-field/{field_id}', [AdminContactController::class, 'update_field'])->name('contact.update_field')->where('field_id', '[0-9]+');
    Route::delete('contact/fields/delete-field/{field_id}', [AdminContactController::class, 'destroy_field'])->name('contact.delete_field')->where('field_id', '[0-9]+');
    Route::post('contact/fields/sortable-fields', [AdminContactController::class, 'sortable_fields'])->name('contact.sortable_fields');

    Route::get('contact', [AdminContactController::class, 'index'])->name('contact');
    Route::get('contact/{id}', [AdminContactController::class, 'show'])->name('contact.show')->where('id', '[0-9]+');
    Route::get('contact/{id}/to_trash', [AdminContactController::class, 'to_trash'])->name('contact.to_trash')->where('id', '[0-9]+');
    Route::get('contact/{id}/mark', [AdminContactController::class, 'mark'])->name('contact.mark')->where('id', '[0-9]+');
    Route::post('contact/multiple-action', [AdminContactController::class, 'multiple_action'])->name('contact.multiple_action');
    Route::delete('contact/{id}/delete', [AdminContactController::class, 'to_trash'])->name('contact.delete')->where('id', '[0-9]+');

    // Languages
    Route::resource('languages', AdminLangController::class)->parameters(['languages' => 'id']);

    // Translates routes        
    Route::get('translates', [AdminTranslateController::class, 'index'])->name('translates');
    Route::get('translate-lang', [AdminTranslateController::class, 'translate_lang'])->name('translate_lang');
    Route::post('translates/create_key', [AdminTranslateController::class, 'create_key'])->name('translates.create_key');
    Route::post('translates/update_key', [AdminTranslateController::class, 'update_key'])->name('translates.update_key');
    Route::post('translates/delete_key', [AdminTranslateController::class, 'delete_key'])->name('translates.delete_key');
    Route::post('translates/update_translate', [AdminTranslateController::class, 'update_translate'])->name('translates.update_translate');
    Route::get('translates/regenerate_lang_file', [AdminTranslateController::class, 'regenerate_lang_file'])->name('translates.regenerate_lang_file');
    Route::post('translates/scan_template', [AdminTranslateController::class, 'scan_template'])->name('translates.scan_template');

    // Theme    
    Route::get('theme', [AdminThemeController::class, 'index'])->name('theme');

    Route::get('theme/logo', [AdminThemeController::class, 'logo'])->name('theme.logo');
    Route::post('theme/logo', [AdminThemeController::class, 'update_logo']);

    Route::get('theme/custom-code', [AdminThemeController::class, 'custom_code'])->name('theme.custom_code');
    Route::post('theme/custom-code',  [AdminThemeController::class, 'update_custom_code']);

    Route::get('theme/menu/dropdown', [AdminThemeMenuController::class, 'index_dropdowns'])->name('theme.menu.dropdown');
    Route::post('theme/menu/dropdown', [AdminThemeMenuController::class, 'store_dropdown']);
    Route::put('theme/menu/dropdown', [AdminThemeMenuController::class, 'update_dropdown']);
    Route::delete('theme/menu/dropdown', [AdminThemeMenuController::class, 'destroy_dropdown']);
    Route::post('theme/menu/sortable-dropdowns/{parent_link_id}', [AdminThemeMenuController::class, 'sortable_dropdowns'])->name('theme.menu.sortable_dropdowns')->where('parent_link_id', '[0-9]+');
    Route::resource('theme/menu', AdminThemeMenuController::class)->names(['index' => 'theme.menu', 'create' => 'theme.menu.create', 'show' => 'theme.menu.show'])->parameters(['menu' => 'id']);
    Route::post('theme/menu/sortable', [AdminThemeMenuController::class, 'sortable'])->name('theme.menu.sortable');

    // Theme footer content routes
    Route::get('theme/footer', [AdminThemeFooterController::class, 'index'])->name('theme.footer');
    Route::put('theme/footer', [AdminThemeFooterController::class, 'update']);

    Route::get('theme/footer/{footer}/content', [AdminThemeFooterController::class, 'content'])->name('theme.footer.content')->where(['footer' => '[a-z0-9_-]+']);

    Route::post('theme/footer/{footer}/content', [AdminThemeFooterController::class, 'update_content'])->where(['footer' => '[a-z0-9_-]+']);

    Route::post('theme/footer/{footer}/{col}/sortable', [AdminThemeFooterController::class, 'sortable'])->name('theme.footer.sortable')->where('footer', '[a-z0-9_-]+')->where('col', '[0-9]+');
    Route::delete('theme/footer/delete/{block_id}', [AdminThemeFooterController::class, 'delete_content'])->name('theme.footer.content.delete')->where('block_id', '[0-9]+');
    Route::get('theme/footer/block/{id}', [AdminThemeFooterController::class, 'block'])->name('theme.footer.block')->where('id', '[0-9]+');
    Route::put('theme/footer/block/{id}', [AdminThemeFooterController::class, 'block_update'])->where('id', '[0-9]+');

    // Buttons
    Route::resource('theme/buttons', AdminThemeButtonController::class)->names(['index' => 'theme.buttons.index', 'create' => 'theme.buttons.create', 'show' => 'theme.buttons.show'])->parameters(['buttons' => 'id']);

    // Styles
    Route::get('theme/styles', [AdminThemeStyleController::class, 'index'])->name('theme.styles.index');
    Route::get('theme/styles/{style}', [AdminThemeStyleController::class, 'show'])->name('theme.styles.show')->where('style', '[0-9a-zA-Z_-]+');
    Route::put('theme/styles/{style}', [AdminThemeStyleController::class, 'update'])->where('style', '[0-9a-zA-Z_-]+');

    Route::post('theme/styles', [AdminThemeStyleController::class, 'store_custom']);
    Route::get('theme/custom-styles/{id}', [AdminThemeStyleController::class, 'show_custom'])->name('theme.custom_styles.show')->where('id', '[0-9]+');
    Route::put('theme/custom-styles/{id}', [AdminThemeStyleController::class, 'update_custom'])->where('id', '[0-9a-zA-Z_-]+');
    Route::delete('theme/custom-styles/{id}', [AdminThemeStyleController::class, 'destroy_custom'])->where('id', '[0-9]+');

    // Config
    Route::get('/config/{module}', [AdminCoreController::class, 'module'])->name('config')->where(['module' => '[a-z0-9_-]+']);
    Route::post('/config/{module}', [AdminCoreController::class, 'update_module'])->where(['module' => '[a-z0-9_-]+']);

    // Blocks routes
    Route::resource('blocks', AdminBlockController::class)->parameters(['blocks' => 'id']);

    // Other routes
    Route::get('ajax/{source}', [AdminAjaxController::class, 'fetch'])->name('ajax')->where('source', '[a-z0-9_-]+');
    Route::get('preview-style/{id}', [AdminCoreController::class, 'preview_style'])->name('preview-style')->where('id', '[a-z0-9_-]+');
    Route::get('tools/generate-sitemap', [AdminCoreController::class, 'generate_sitemap'])->name('sitemap.generate');
});
