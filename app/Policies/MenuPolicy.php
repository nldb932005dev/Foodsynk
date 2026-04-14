<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // el controller filtra por user_id
    }

    public function view(User $user, Menu $menu): bool
    {
        return $user->id === $menu->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->id === $menu->user_id;
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->id === $menu->user_id;
    }

    public function restore(User $user, Menu $menu): bool
    {
        return false;
    }

    public function forceDelete(User $user, Menu $menu): bool
    {
        return false;
    }
}
