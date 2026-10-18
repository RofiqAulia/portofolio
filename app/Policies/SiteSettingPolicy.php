<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

final class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, SiteSetting $setting): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return $user->isAdmin();
    }
}
