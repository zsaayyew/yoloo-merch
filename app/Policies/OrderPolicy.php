<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/**
 * PRD-05 FIN - Order & request.
 * Owner dan Admin (sudah lolos middleware auth + active) boleh melihat daftar dan detail.
 * Aksi ubah/hapus dibuka bertahap saat fitur FIN berikutnya dikerjakan.
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
        return false;
    }

    public function update(User $user, Order $order): bool
    {
        return false;
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
