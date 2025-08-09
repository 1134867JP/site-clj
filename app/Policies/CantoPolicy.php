<?php

namespace App\Policies;

use App\Models\Canto;
use App\Models\User;

class CantoPolicy
{
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, Canto $canto): bool { return true; }

    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Canto $canto): bool { return $user->isAdmin(); }
    public function delete(User $user, Canto $canto): bool { return $user->isAdmin(); }
}
