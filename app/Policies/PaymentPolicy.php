<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

/**
 * PRD-05 FIN - Pembayaran (FIN-FR-008, FIN-FR-009).
 * Admin dan Owner boleh mencatat; Void hanya Owner. Payment tidak pernah dihapus (FIN-BR-009).
 */
class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Payment $payment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function void(User $user, Payment $payment): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Payment $payment): bool
    {
        return false;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }

    public function restore(User $user, Payment $payment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return false;
    }
}
