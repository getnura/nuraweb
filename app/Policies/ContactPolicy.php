<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Auth\Access\Response;

class ContactPolicy
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
        return UserPermission::checkPermission($user->id, 'Contact', 'viewAny');
    }
    

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $message = null): bool
    {
        if (!UserPermission::checkPermission($user->id, 'Contact', 'viewAny')) return false; // viewAny permission is required to delete item

        if (UserPermission::checkPermission($user->id, 'Contact', 'delete')) return true; // check if user has delete permission        
        else return false;

    }
}
