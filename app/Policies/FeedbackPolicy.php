<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function viewAny(?User $user): bool { return $user->isAdmin(); }
    public function view(?User $user, Feedback $tipo): bool { return $user->isAdmin(); }

    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Feedback $tipo): bool { return $user->isAdmin(); }
    public function delete(User $user, Feedback $tipo): bool { return $user->isAdmin(); }
}
