<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Bersama (koordinator) — Pencatat audit log.
 * Signature disepakati di AI_CONTEXT bagian 7 poin 8, jangan diubah tanpa koordinasi.
 *
 * Contoh pemakaian (FIN):
 *   app(AuditLogger::class)->log('payment.void', $payment, $request->reason, [
 *       'status' => ['old' => 'valid', 'new' => 'void'],
 *   ]);
 */
class AuditLogger
{
    public function log(string $action, Model $subject, ?string $reason = null, array $changes = []): AuditLog
    {
        return AuditLog::create([
            'actor_user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'reason' => $reason,
            'changes' => $changes ?: null,
            'ip_address' => Request::ip(),
        ]);
    }
}
