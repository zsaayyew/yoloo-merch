<?php

namespace App\Policies;

use App\Models\Refund;
use App\Models\User;

/**
 * PRD-05 FIN - Refund (FIN-FR-019, FIN-BR-019). Hanya Owner yang mencatat refund.
 * Refund tidak pernah dihapus (FIN-BR-009).
 */
class RefundPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Refund $refund): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Refund $refund): bool
    {
        return false;
    }

    public function delete(User $user, Refund $refund): bool
    {
        return false;
    }

    public function restore(User $user, Refund $refund): bool
    {
        return false;
    }

    public function forceDelete(User $user, Refund $refund): bool
    {
        return false;
    }
}
