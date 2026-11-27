<?php

namespace App\Policies;

use App\Models\OrderRequest;
use App\Models\User;

/**
 * PRD-05 FIN - Custom order request.
 * Owner dan Admin (sudah lolos middleware auth + active) boleh meninjau, membuat penawaran,
 * dan mengonversi. Batasan alur (status, sekali konversi) dijaga StatusTransitionService dan RequestConversionService.
 */
class OrderRequestPolicy
{
    public function view(User $user, OrderRequest $request): bool
    {
        return true;
    }

    public function update(User $user, OrderRequest $request): bool
    {
        return true;
    }

    public function convert(User $user, OrderRequest $request): bool
    {
        return true;
    }
}
