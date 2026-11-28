<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/**
 * PRD-05 FIN - Order & request.
 * Owner dan Admin (sudah lolos middleware auth + active) boleh melihat dan mengelola transaksi.
 * Pembatalan transaksi hanya Owner (FIN-FR-016). Tidak ada hapus permanen (FIN-BR-009).
 */
class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /** Ubah status pengerjaan (kecuali pembatalan). */
    public function update(User $user, Order $order): bool
    {
        return true;
    }

    /** Pembatalan transaksi: Owner saja. */
    public function cancel(User $user, Order $order): bool
    {
        return $user->isOwner();
    }

    /**
     * Review, penawaran, dan konversi custom request. Request tidak punya model policy sendiri,
     * jadi dicek lewat kemampuan tanpa model: Gate::authorize('manageRequests', Order::class).
     */
    public function manageRequests(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }

    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
