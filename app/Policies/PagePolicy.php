<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Auth\Access\Response;

class PagePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role == 'admin') return true;

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return UserPermission::checkPermission($user->id, 'Page', 'viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Page $page = null): bool
    {
        if (UserPermission::checkPermission($user->id, 'Page', 'viewAny')) return true; // if user can viewAny, then can view this
        else return UserPermission::checkPermission($user->id, 'Page', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!UserPermission::checkPermission($user->id, 'Page', 'view')) return false; // view permission is required to create item

        return UserPermission::checkPermission($user->id, 'Page', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Page $page = null): bool
    {
        if (!UserPermission::checkPermission($user->id, 'Page', 'view')) return false; // view permission is required to update item

        if (!UserPermission::checkPermission($user->id, 'Page', 'update')) return false; // check if user has update permission
        elseif (UserPermission::checkPermission($user->id, 'Page', 'viewAny')) return true; // if user can viewAny, then can update this
        else return $user->id === $page->user_id; // can update own item
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Page $page = null): bool
    {
        if (!UserPermission::checkPermission($user->id, 'Page', 'view')) return false; // view permission is required to delete item

        if (!UserPermission::checkPermission($user->id, 'Page', 'delete')) return false; // check if user has delete permission
        elseif (UserPermission::checkPermission($user->id, 'Page', 'viewAny')) return true; // if user can viewAny, then can delete this
        else return $user->id === $page->user_id; // can delete own item

    }
}
