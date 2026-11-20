<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Alur persetujuan konten (Post & Album).
 *
 * - Super Admin           : langsung tayang (kecuali memilih "Simpan Draft").
 * - Admin Unit + approval : draft -> kirim -> pending -> disetujui/dikembalikan.
 * - Admin Unit tanpa approval (setting require_approval = 0): langsung tayang.
 */
class ContentWorkflow
{
    public static function approvalRequired(): bool
    {
        return setting('require_approval', '1') === '1';
    }

    public static function canPublishDirectly(User $user): bool
    {
        return $user->isSuperAdmin() || ! self::approvalRequired();
    }

    /**
     * Tentukan atribut status berdasarkan tombol yang ditekan ("draft" atau "submit").
     */
    public function statusFor(User $user, string $action, ?Model $model = null): array
    {
        if ($action === 'draft') {
            // "Simpan Draft" selalu menarik konten dari halaman publik.
            return ['status' => 'draft'];
        }

        if (self::canPublishDirectly($user)) {
            return [
                'status' => 'published',
                'published_at' => $model?->published_at ?? now(),
                'approved_by' => $user->isSuperAdmin() ? $user->id : $model?->approved_by,
                'catatan_revisi' => null,
            ];
        }

        return ['status' => 'pending'];
    }

    public function approve(Model $model, User $by): void
    {
        $model->forceFill([
            'status' => 'published',
            'published_at' => $model->published_at && $model->published_at->isPast() ? $model->published_at : now(),
            'approved_by' => $by->id,
            'catatan_revisi' => null,
        ])->save();
    }

    public function reject(Model $model, User $by, string $note): void
    {
        $model->forceFill([
            'status' => 'rejected',
            'catatan_revisi' => $note,
            'approved_by' => null,
        ])->save();
    }
}
