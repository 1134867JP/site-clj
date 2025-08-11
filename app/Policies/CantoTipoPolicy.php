<?php

namespace App\Policies;

use App\Models\CantoTipo;
use App\Models\User;

class CantoTipoPolicy
{
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, CantoTipo $tipo): bool { return true; }

    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, CantoTipo $tipo): bool { return $user->isAdmin(); }
    public function delete(User $user, CantoTipo $tipo): bool { return $user->isAdmin(); }
}
